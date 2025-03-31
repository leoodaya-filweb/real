<?php

namespace app\models\form;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Yii;
use app\helpers\App;
use app\helpers\ArrayHelper;
use app\helpers\Url;
use app\jobs\NotificationJob;
use app\models\File;
use app\models\Notification;
use app\models\Queue;
use app\models\Specialsurvey;
use yii\db\Expression;

class SpecialsurveyImportForm extends \yii\base\Model
{
    const ALLOWED_EXTENSIONS = ['csv', 'xlsx'];
    const HEADERS = [
        'SURVEY_NAME',
        'LAST_NAME',
        'FIRST_NAME',
        'MIDDLE_NAME',
        'HOUSEHOLD_NO',
        'GENDER',
        'AGE',
        'DATE_OF_BIRTH',
        'CIVIL_STATUS',
        'HOUSE_NO',
        'SITIO',
        'PUROK',
        'BARANGAY',
        'MUNICIPALITY',
        'PROVINCE',
        'RELIGION',
        'CRITERIA1_COLOR_ID',
        'CRITERIA2_COLOR_ID',
        'CRITERIA3_COLOR_ID',
        'CRITERIA4_COLOR_ID',
        'CRITERIA5_COLOR_ID',
        'DATE_SURVEY',
        'REMARKS',
    ];

    public $file_token;
    public $user_id = 0;

    protected $_file;
    protected $_data;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file_token'], 'required'],
            [
                'file_token', 
                'exist',
                'targetClass' => 'app\models\File', 
                'targetAttribute' => 'token'
            ],
            ['file_token', 'validateFile', 'on' => 'contentValidation'],
            ['user_id', 'integer']
        ];
    }

    public function beforeValidate()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        return parent::beforeValidate();
    }

    public function getFile()
    {
        if ($this->_file == null) {
            $this->_file = File::findByToken($this->file_token);
        }

        return $this->_file;
    }

    public function validateFile($attribute, $params)
    {
        if (($file = $this->getFile()) != null) {

            if (! in_array($file->extension, self::ALLOWED_EXTENSIONS)) {
                $this->addError($attribute, 'Invalid Extension');
                return;
            }

            $sheets = $this->getData();

            foreach ($sheets as $sheet) {
                foreach ($sheet as $rowKey => $row) {
                    if ($rowKey == 1) {
                        foreach (self::HEADERS as $key => $value) {
                            if (isset($row[$key])) {
                                if ($row[$key] != $value) {
                                    $this->addError($attribute, 'Invalid Content Format.');
                                    break;
                                }
                            }
                            else {
                                $this->addError($attribute, 'Invalid Content Format.');
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    public function getData()
    {
        if ($this->_data == null) {
            $file = $this->getFile();

            $reader = ReaderEntityFactory::createReaderFromFile($file->rootPath);
            $reader->open($file->rootPath);

            $data = [];
            foreach ($reader->getSheetIterator() as $sheetKey => $sheet) {
                foreach ($sheet->getRowIterator() as $rowKey => $row) {
                    // do stuff with the row
                    $data[$sheetKey][$rowKey] = $row->toArray();
                }
            }

            $reader->close();

            $this->_data = $data;
        }

        return $this->_data;
    }

    public function save()
    {
        try {
            if ($this->validate()) {
                $headers = array_flip(self::HEADERS);

                if (($sheets = $this->getData()) != null) {
                    $data = [];

                    foreach ($sheets as $sheetKey => $sheet) {
                        foreach ($sheet as $rowKey => $d) {
                            if ($rowKey != 1) {
                                $row = [
                                    'survey_name' => trim($d[$headers['SURVEY_NAME']]),
                                    'last_name' => trim($d[$headers['LAST_NAME']]),
                                    'first_name' => trim($d[$headers['FIRST_NAME']]),
                                    'middle_name' => trim($d[$headers['MIDDLE_NAME']]),
                                    'household_no' => trim($d[$headers['HOUSEHOLD_NO']]),
                                    'gender' => trim($d[$headers['GENDER']]),
                                    'age' => trim($d[$headers['AGE']]),
                                    'date_of_birth' => date('Y-m-d', strtotime(trim($d[$headers['DATE_OF_BIRTH']]))),
                                    'civil_status' => trim($d[$headers['CIVIL_STATUS']]),
                                    'house_no' => trim($d[$headers['HOUSE_NO']]),
                                    'sitio' => trim($d[$headers['SITIO']]),
                                    'purok' => trim($d[$headers['PUROK']]),
                                    'barangay' => trim($d[$headers['BARANGAY']]),
                                    'municipality' => trim($d[$headers['MUNICIPALITY']]),
                                    'province' => trim($d[$headers['PROVINCE']]),
                                    'religion' => trim($d[$headers['RELIGION']]),
                                    'criteria1_color_id' => trim($d[$headers['CRITERIA1_COLOR_ID']]),
                                    'criteria2_color_id' => trim($d[$headers['CRITERIA2_COLOR_ID']]),
                                    'criteria3_color_id' => trim($d[$headers['CRITERIA3_COLOR_ID']]),
                                    'criteria4_color_id' => trim($d[$headers['CRITERIA4_COLOR_ID']]),
                                    'criteria5_color_id' => trim($d[$headers['CRITERIA5_COLOR_ID']]),

                                    'date_survey' => date('Y-m-d', strtotime(trim($d[$headers['DATE_SURVEY']]))),
                                    'remarks' => trim($d[$headers['REMARKS']]),
                                ];

                                $row['record_status'] = Specialsurvey::RECORD_ACTIVE;
                                $row['created_by'] = $this->user_id;
                                $row['updated_by'] = $this->user_id;
                                $row['created_at'] = new Expression('UTC_TIMESTAMP');
                                $row['updated_at'] = new Expression('UTC_TIMESTAMP');

                                $data[] = $row;
                            }
                        }
                    }

                    if ($data) {
                        $arr = array_chunk($data, 1000);
                        $columns = array_keys($data[0]);
                        foreach ($arr as $r) {
                            App::createCommand()
                                ->batchInsert(Specialsurvey::tableName(), $columns, $r)
                                ->execute();
                        }
                    }

                    Queue::push(new NotificationJob([
                        'user_id' => $this->user_id,
                        'type' => Notification::IMPORT_SURVEY,
                        'message' => 'Survey was imported successfully!',
                        'link' => Url::to(['specialsurvey/index']),
                    ]));
                    
                    return true;
                }

                return false;
            }
        } 
        catch (\yii\base\Exception $e) {
            $this->addError('survey', $e->getMessage());
            return $this->errors;
        }
    }
}