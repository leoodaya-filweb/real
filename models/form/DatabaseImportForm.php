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
use app\models\Database;
use yii\db\Expression;

class DatabaseImportForm extends \yii\base\Model
{
    const ALLOWED_EXTENSIONS = ['csv', 'xlsx'];
    const HEADERS = [
        'SYSTEM_ID',
        'PRIORITY_SECTOR',
        'SECTOR_ID',
        'LAST_NAME',
        'FIRST_NAME',
        'MIDDLE_NAME',
        'GENDER',
        'AGE',
        'DATE_OF_BIRTH',
        'CIVIL_STATUS',
        'EDUC_ATTAINMENT',
        'OCCUPATION',
        'MONTHLY_INCOME',
        'OTHER_SOURCE_INCOME',
        'HOUSE_NO',
        'STREET',
        'BARANGAY',
        'MUNICIPALITY',
        'DATE_REGISTERED',
        'CONTACT_NO',
        'PENSIONER',
        'RELATION_WHERE',
        'AMOUNT_OF_PENSION',
        'LIVING_WITH_WHOM',
        'RELATION',
        'RELATION_OCCUPATION',
        'RELATION_INCOME',
        'STATUS',
        'ENCODED_BY',
        'EDITED_BY',
        'DATE_OF_APPLICATION',
        'BIRTH_PLACE',
        'BIRTH_CERTIFICATE',
        'ETHNICITY',
        'SOURCE_OF_INCOME',
        'SLP_BENEFICIARY',
        'RELIGION',
        'MCCT_BENEFICIARY',
        'REMARKS',
        'TYPE_OF_DISABILITY',
        'PUROK',
        'SITIO',
        'LANDMARK',
        'OTHER_CONTACT_NO',
        'OTHER_INCOME_SOURCE_AMOUNT',
        'SOGIE',
        'EMAIL',
        'FATHERS_NAME',
        'MOTHERS_NAME',
        'SCHOOL_NAME_LAST_ATTENDED',
        'SCHOOL_YEAR_LAST_ATTENDED',
        'ORGANIZATION_NAME',
        'POSITION',
        'PWD_TYPE',
        'STATUS_OF_EMPLOYMENT',
        'TYPES_OF_EMPLOYMENT',
        'CATEGORY_OF_EMPLOYMENT',
        'ORG_AFFILIATED',
        'ORG_CONTACT_PERSON',
        'ORG_OFFICE_ADDRESS',
        'ORG_TEL_NO',
        'SSS_NO',
        'GSIS_NO',
        'PAGIBIG_NO',
        'PSN_NO',
        'PHILHEALTH_NO',
        'FATHER_LASTNAME',
        'FATHER_FIRSTNAME',
        'FATHER_MIDDLENAME',
        'MOTHER_LASTNAME',
        'MOTHER_FIRSTNAME',
        'MOTHER_MIDDLENAME',
        'GUARDIAN_LASTNAME',
        'GUARDIAN_FIRSTNAME',
        'GUARDIAN_MIDDLENAME',
        'ACCOMPLISHED_BY',
        'REPRESENTATIVE_LASTNAME',
        'REPRESENTATIVE_FIRSTNAME',
        'REPRESENTATIVE_MIDDLENAME',
        'CERTIFYING_PHYSICIAN_LASTNAME',
        'CERTIFYING_PHYSICIAN_FIRSTNAME',
        'CERTIFYING_PHYSICIAN_MIDDLENAME',
        'LICENSE_NO',
        'PROCESSING_OFFICER_LASTNAME',
        'PROCESSING_OFFICER_FIRSTNAME',
        'PROCESSING_OFFICER_MIDDLENAME',
        'APPROVING_OFFICER_LASTNAME',
        'APPROVING_OFFICER_FIRSTNAME',
        'APPROVING_OFFICER_MIDDLENAME',
        'ENCODER_LASTNAME',
        'ENCODER_FIRSTNAME',
        'ENCODER_MIDDLENAME',
        'REPORTING_UNIT',
        'CONTROL_NO',
        'NAME_SUFFIX',
        'PREFERRED_NAME',
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

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
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

                                $unique_keys = ['priority_sector', 'sector_id'];
                                $query_checker = [];

                                foreach (self::HEADERS as $attribute) {
                                    $dates = [
                                        'date_of_birth',
                                        'date_registered',
                                        'date_of_application',
                                    ];

                                    $content = trim($d[$headers[strtoupper($attribute)]]);
                                    $attrib = strtolower($attribute);

                                    if ($content) {
                                        // check if date column
                                        if (in_array($attrib, $dates)) {
                                            // check if valid date
                                            $row[$attrib] = $this->validateDate($content) ? date('Y-m-d', strtotime($content)) : null;
                                        }else{
                                            $row[$attrib] = $content;
                                        }
                                            
                                        // get unique identifiers
                                        if (in_array($attrib, $unique_keys)) {
                                            $query_checker[$attrib] = $row[$attrib];
                                        }
                                    }else{
                                        $row[$attrib] = null;
                                    }

                                    // $row[strtolower($attribute)] = (in_array(strtolower($attribute), $dates)) ? date('Y-m-d', strtotime(trim($d[$headers[strtoupper($attribute)]]))): trim($d[$headers[strtoupper($attribute)]]);
                                }

                                $row['record_status'] = Database::RECORD_ACTIVE;
                                $row['created_by'] = $this->user_id;
                                $row['updated_by'] = $this->user_id;
                                $row['created_at'] = new Expression('UTC_TIMESTAMP');
                                $row['updated_at'] = new Expression('UTC_TIMESTAMP');

                                if (!empty($query_checker)) {
                                    
                                    if (!Database::find()->where($query_checker)->one()) {
                                        $data[] = $row;
                                    }

                                }
                            }
                        }
                    }

                    if ($data) {
                        $arr = array_chunk($data, 1000);
                        $columns = array_keys($data[0]);
                        foreach ($arr as $r) {
                            App::createCommand()
                                ->batchInsert(Database::tableName(), $columns, $r)
                                ->execute();
                        }
                    }

                    Queue::push(new NotificationJob([
                        'user_id' => $this->user_id,
                        'type' => Notification::IMPORT_DATABASE,
                        'message' => 'Database was imported successfully!',
                        'link' => Url::to(['database/index']),
                    ]));
                    
                    return true;
                }

                return false;
            }
        } 
        catch (\yii\base\Exception $e) {
            $this->addError('database', $e->getMessage());
            return $this->errors;
        }
    }
}