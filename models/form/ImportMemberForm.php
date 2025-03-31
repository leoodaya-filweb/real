<?php

namespace app\models\form;

use Yii;
use app\models\File;
use app\models\Member;
use app\models\Household;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ImportMemberForm extends \yii\base\Model
{
    const ALLOWED_EXTENSIONS = ['csv'];
    const HEADERS = [
        'main.id',
        'hpq_mem.id',
        'memno',
        'msname',
        'mfname',
        'mmname',
        'nucfam',
        'reln',
        'reln_o',
        'sex',
        'birth_date',
        'age',
        'age_yr',
        'birth_reg',
        'civstat',
        'ethgrp',
        'ethgrp_o',
        'ofw',
        'mlenresid',
        'country_resid',
        'country_resid_o',
        'prov_resid_code',
        'mun_resid_code',
        'brgy_resid_code',
        'mun_resid_txt',
        'brgy_resid_txt',
        'educind',
        'gradel',
        'sch_type',
        'gradel_calc',
        'ynotsch',
        'ynotsch_o',
        'educal',
        'psced7',
        'course_o',
        'literind',
        'regvotind',
        'voted_last_election',
        'jobind',
        'entrepind',
        'njob',
        'occup',
        'psoc4',
        'indust',
        'psic4',
        'jstatus',
        'work_ddhrs',
        'work_wkhrs',
        'fadd_work_hrs',
        'fxtra_wrk',
        'workcl',
        'fjob',
        'first_fjob',
        'jsearch_meth',
        'jsearch_meth_o',
        'wks_fjob',
        'ynotlookjob',
        'ynotlookjob_o',
        'lastlookjob',
        'joppind',
        'wtwind',
        'wagcshm',
        'wagkndm',
        'sss_ind',
        'pregind',
        'solo_parent',
        'pwd_ind',
        'pwd_type',
        'pwd_type_o',
        'pwd_id',
        'scid_ind',
        'mcrimeind',
        'mtheftind',
        'mrapeind',
        'minjurind',
        'mcarnapind',
        'mcattrustlind',
        'mocrimind',
        'mocrim',
        'mtheftloc',
        'mrapeloc',
        'minjurloc',
        'mcarnaploc',
        'mcattrustlloc',
        'mocrimloc',
        'mnutind',
        'mnutind_date',
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
                                }
                            }
                            else {
                                $this->addError($attribute, 'Invalid Content Format.');
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

    public function getHouseholdId($no)
    {
        $model = Household::findOne(['no' => $no]);

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
                                $condition = [
                                    'household_id' => $this->getHouseholdId($d[$headers['main.id']]),
                                    'last_name' => (string)$d[$headers['msname']],
                                    'middle_name' => (string)$d[$headers['mmname']],
                                    'first_name' => (string)$d[$headers['mfname']],
                                    'birth_date' => (string)$d[$headers['birth_date']],
                                ];
                                
                                $model = Member::findOne($condition) ?: new Member($condition);

                                $model->sex = $d[$headers['sex']];
                                $model->relation = (int)$d[$headers['reln']] ?: 0;
                                $model->head = ($model->relation == 1)? 
                                    Member::FAMILY_HEAD_YES: Member::FAMILY_HEAD_NO;
                                $model->civil_status = $d[$headers['civstat']];
                                $model->educational_attainment = $d[$headers['educal']] ?: 0;
                                $model->occupation = $d[$headers['occup']] ?: NULL;
                                $model->income = (int)$d[$headers['wagcshm']] ?: 0;

                                $model->pensioner = Member::NOT_PENSIONER;
                                
                                if (isset($d[$headers['ynotlookjob_o']]) 
                                    && $d[$headers['ynotlookjob_o']] == 'PENSIONER') {
                                    $model->pensioner = Member::PENSIONER;
                                }

                                if (isset($d[$headers['occup']]) 
                                    && $d[$headers['occup']] == 'Pensioner') {
                                    $model->pensioner = Member::PENSIONER;
                                }
                                
                                $model->logAfterSave = false;

                                if ($model->save()) {
                                    array_push($models, $model);
                                }
                                else {
                                    $errors = $model->errors;
                                    $errors['sheetKey'] = $sheetKey;
                                    $errors['rowKey'] = $rowKey;
                                    $this->addError('member', $errors);
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
            $this->addError('member', $e->getMessage());
            return $this->errors;
        }
    }
}