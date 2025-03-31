<?php

namespace app\models\form;

use Yii;
use app\models\File;
use app\models\Region;
use app\models\Barangay;
use app\models\Province;
use app\models\Household;
use app\models\Municipality;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ImportHouseholdForm extends \yii\base\Model
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
                    $models = [];

                    foreach ($sheets as $sheetKey => $sheet) {
                        foreach ($sheet as $rowKey => $d) {
                            if ($rowKey != 1) {
                                $condition = ['no' => $d[$headers['main.id']]];

                                $model = Household::findOne($condition) ?: new Household($condition);
                                $model->transfer_date = $d[$headers['main.transferDate']];
                                $model->longitude = (string) $d[$headers['geopoint_hh.longitude']];
                                $model->latitude = (string) $d[$headers['geopoint_hh.latitude']];
                                $model->altitude = (string) $d[$headers['geopoint_hh.altitude']];
                                $model->region_id = $this->getRegionId($d[$headers['regn']]);
                                $model->province_id = $this->getProvinceId($d[$headers['prov']]);
                                $model->municipality_id = $this->getMunicipalityId($d[$headers['mun']]);
                                $model->zone_no = $d[$headers['zone']];
                                $model->barangay_id = $this->getBarangayId($d[$headers['brgy']]);
                                $model->purok_no = $d[$headers['purok']];
                                $model->street = (string) $d[$headers['street']];
                                $model->record_status = Household::RECORD_ACTIVE;
                                $model->logAfterSave = false;
                                if ($model->save()) {
                                    array_push($models, $model);
                                }
                                else {
                                    $errors = $model->errors;
                                    $errors['sheetKey'] = $sheetKey;
                                    $errors['rowKey'] = $rowKey;
                                    $this->addError('household', $errors);
                                }
                            }
                        }
                    }
                    
                    return $models;
                }

                return false;
            }
        } 
        catch (\yii\base\Exception $e) {
            $this->addError('household', $e->getMessage());
            return $this->errors;
        }
    }
}