<?php

namespace app\models\form;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Yii;
use app\helpers\App;
use app\helpers\Url;
use app\jobs\NotificationJob;
use app\models\Barangay;
use app\models\File;
use app\models\Household;
use app\models\Municipality;
use app\models\Notification;
use app\models\Province;
use app\models\Queue;
use app\models\Region;
use yii\db\Expression;

class BulkImportHouseholdForm extends \yii\base\Model
{
    const ALLOWED_EXTENSIONS = ['csv'];
    const HEADERS = [
        'main.id',
        'main.uploader',
        'main.workgroup',
        'main.creator',
        'main.deviceSerial',
        'main.transferDate',
        'androidDisplayName',
        'androidDescription',
        'geopoint_hh_ind',
        'geopoint_hh.longitude',
        'geopoint_hh.latitude',
        'geopoint_hh.altitude',
        'geopoint_hh.accuracy',
        'regn',
        'prov',
        'mun',
        'zone',
        'brgy',
        'purok',
        'street',
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

    public function getRegionId($no)
    {
        $model = Region::findOne(['no' => $no]);
        return ($model)? $model->id: 0;
    }

    public function getProvinceId($no)
    {
        $model = Province::findOne(['no' => $no]);
        return ($model)? $model->id: 0;
    }

    public function getMunicipalityId($no)
    {
        $model = Municipality::findOne(['no' => $no]);
        return ($model)? $model->id: 0;
    }

    public function getBarangayId($no)
    {
        $model = Barangay::findOne(['no' => $no]);
        return ($model)? $model->id: 0;
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
                                    'no' => trim($d[$headers['main.id']]),
                                    'transfer_date' => trim($d[$headers['main.transferDate']]),
                                    'longitude' => trim((string) $d[$headers['geopoint_hh.longitude']]),
                                    'latitude' => trim((string) $d[$headers['geopoint_hh.latitude']]),
                                    'altitude' => trim((string) $d[$headers['geopoint_hh.altitude']]),
                                    'region_id' => $this->getRegionId($d[$headers['regn']]),
                                    'province_id' => $this->getProvinceId($d[$headers['prov']]),
                                    'municipality_id' => $this->getMunicipalityId($d[$headers['mun']]),
                                    'zone_no' => $d[$headers['zone']],
                                    'barangay_id' => $this->getBarangayId($d[$headers['brgy']]),
                                    'purok_no' => $d[$headers['purok']],
                                    'street' => (string) $d[$headers['street']],
                                    'token' => $this->token($sheetKey, $rowKey),
                                    'record_status' => Household::RECORD_ACTIVE,
                                    'created_by' => $this->user_id,
                                    'updated_by' => $this->user_id,
                                    'created_at' => new Expression('UTC_TIMESTAMP'),
                                    'updated_at' => new Expression('UTC_TIMESTAMP'),
                                ];

                                if (($household = Household::findOneAsArray(['no' => $row['no']])) != null) {
                                    $row['token'] = $household['token'];
                                    $row['record_status'] = $household['record_status'];
                                    $row['created_by'] = $household['created_by'];
                                    $row['created_at'] = $household['created_at'];
                                    // Household::updateAllNoLogs($row, ['id' => $household['id']]);
                                    App::createCommand()
                                        ->update(Household::tableName(), $row, ['id' => $household['id']])
                                        ->execute();
                                }
                                else {
                                    $data[] = $row;
                                }
                            }
                        }
                    }

                    if ($data) {
                        $arr = array_chunk($data, 1000);
                        $columns = array_keys($data[0]);
                        foreach ($arr as $r) {
                            App::createCommand()
                                ->batchInsert(Household::tableName(), $columns, $r)
                                ->execute();
                        }
                    }

                    Queue::push(new NotificationJob([
                        'user_id' => $this->user_id,
                        'type' => Notification::IMPORT_HOUSEHOLD,
                        'message' => 'Households was imported successfully!',
                        'link' => Url::to(['household/index']),
                    ]));
                    
                    return true;
                }

                return false;
            }
        } 
        catch (\yii\base\Exception $e) {
            $this->addError('household', $e->getMessage());
            return $this->errors;
        }
    }

    public function token($sheetKey, $rowKey)
    {
        return implode('', [
            implode('-', [
                App::randomString(10),
                time(),
            ]),
            $sheetKey,
            $rowKey
        ]);
    }
}