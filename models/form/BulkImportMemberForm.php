<?php

namespace app\models\form;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Yii;
use app\helpers\App;
use app\helpers\Url;
use app\jobs\NotificationJob;
use app\models\File;
use app\models\Household;
use app\models\Member;
use app\models\Notification;
use app\models\Queue;
use yii\db\Expression;
use yii\helpers\Inflector;

class BulkImportMemberForm extends \yii\base\Model
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
                    $data = [];

                    foreach ($sheets as $sheetKey => $sheet) {
                        foreach ($sheet as $rowKey => $d) {
                            if ($rowKey != 1) {
                                $row = [
                                    'household_id' => $this->getHouseholdId($d[$headers['main.id']]),
                                    'last_name' => trim($d[$headers['msname']]),
                                    'middle_name' => trim($d[$headers['mmname']]),
                                    'first_name' => trim($d[$headers['mfname']]),
                                    'birth_date' => date('Y-m-d', strtotime($d[$headers['birth_date']])),
                                    'sex' => (int)$d[$headers['sex']],
                                    'relation' => (int)$d[$headers['reln']] ?: 0,
                                    'civil_status' => (int)$d[$headers['civstat']],
                                    'educational_attainment' => (int)$d[$headers['educal']] ?: 0,
                                    'occupation' => $d[$headers['occup']] ?: NULL,
                                    'income' => (int)$d[$headers['wagcshm']] ?: 0,
                                    'pwd' => (int)$d[$headers['pwd_ind']] ?: Member::PWD_NO,
                                    'solo_parent' => (int)$d[$headers['solo_parent']] ?: Member::SOLO_PARENT_NO,
                                    'pwd_type' => (int)$d[$headers['pwd_type']] ?: 0,
                                    'voter' => (int)$d[$headers['regvotind']] ?: Member::VOTER_NO,
                                ];

                                $row['head'] = $this->head($row);
                                $row['pensioner'] = $this->pensioner($d, $headers);

                                $row['qr_id'] = $this->qrId($sheetKey, $rowKey, $row);
                                $row['token'] = $this->token($sheetKey, $rowKey);
                                $row['slug'] = Inflector::slug(implode('-', [
                                    $row['first_name'],
                                    $row['middle_name'],
                                    $row['last_name'],
                                ]));

                                $row['record_status'] = Member::RECORD_ACTIVE;
                                $row['created_by'] = $this->user_id;
                                $row['updated_by'] = $this->user_id;
                                $row['created_at'] = new Expression('UTC_TIMESTAMP');
                                $row['updated_at'] = new Expression('UTC_TIMESTAMP');

                                $condition = [
                                    'household_id' => $row['household_id'],
                                    'last_name' => $row['last_name'],
                                    'middle_name' => $row['middle_name'],
                                    'first_name' => $row['first_name'],
                                    'birth_date' => $row['birth_date'],
                                    'sex' => $row['sex'],
                                ];

                                $members = Member::find()
                                    ->where(['household_id' => $row['household_id']])
                                    ->count();

                                if ($members > 1) {
                                    Member::updateAll(
                                        ['solo_member' => Member::SOLO_MEMBER_NO],
                                        ['household_id' => $row['household_id']],
                                    );
                                    
                                    $row['solo_member'] = Member::SOLO_MEMBER_NO;
                                }
                                else {
                                    $row['solo_member'] = Member::SOLO_MEMBER_YES;
                                }

                                if (($member = Member::findOneAsArray($condition)) != null) {
                                    $row['qr_id'] = $member['qr_id'];
                                    $row['slug'] = $member['slug'];
                                    $row['token'] = $member['token'];
                                    $row['record_status'] = $member['record_status'];
                                    $row['created_by'] = $member['created_by'];
                                    $row['created_at'] = $member['created_at'];

                                    App::createCommand()
                                        ->update(Member::tableName(), $row, ['id' => $member['id']])
                                        ->execute();
                                    // Member::updateAllNoLogs($row, ['id' => $member['id']]);
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
                                ->batchInsert(Member::tableName(), $columns, $r)
                                ->execute();
                        }
                    }

                    Queue::push(new NotificationJob([
                        'user_id' => $this->user_id,
                        'type' => Notification::IMPORT_MEMBER,
                        'message' => 'Members was imported successfully!',
                        'link' => Url::to(['member/index']),
                    ]));
                    
                    return true;
                }

                return false;
            }
        } 
        catch (\yii\base\Exception $e) {
            $this->addError('member', $e->getMessage());
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

    public function qrId($sheetKey, $rowKey, $row)
    {
        return implode('', [
            implode('-', [
                $this->initial($row),
                time(),
            ]),
            $sheetKey,
            $rowKey
        ]);
    }

    public function pensioner($d, $headers)
    {
        $pensioner = Member::NOT_PENSIONER;

        if (isset($d[$headers['ynotlookjob_o']]) 
            && $d[$headers['ynotlookjob_o']] == 'PENSIONER') {
            $pensioner = Member::PENSIONER;
        }

        if (isset($d[$headers['occup']]) && $d[$headers['occup']] == 'Pensioner') {
            $pensioner = Member::PENSIONER;
        }

        return $pensioner;
    }

    public function head($row)
    {
        return ($row['relation'] == 1)? Member::FAMILY_HEAD_YES: Member::FAMILY_HEAD_NO;
    }

    public function initial($row)
    {
        preg_match_all('/(?<=\b)[a-z]/i',$this->name($row), $matches);
        return strtoupper(implode('', $matches[0]));
    }

    public function name($row)
    {
        return implode(' ', array_filter([
            $row['first_name'],
            $row['last_name'],
        ]));
    }
}