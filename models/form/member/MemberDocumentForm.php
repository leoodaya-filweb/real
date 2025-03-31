<?php

namespace app\models\form\member;

use Yii;
use app\models\File;
use app\models\Member;

class MemberDocumentForm extends \yii\base\Model
{
    public $member_id;
    public $file_token;

    public $attribute = 'documents';

    public $_member;
    public $_file;

    public $unlink = true;

    public function rules()
    {
        return [
            [['member_id', 'file_token', 'attribute'], 'required'],
            ['file_token', 'string'],
            ['member_id', 'integer'],
            ['member_id', 'exist', 
                'targetClass' => 'app\models\Member', 
                'targetAttribute' => 'id'
            ],
            ['file_token', 'exist', 
                'targetClass' => 'app\models\File', 
                'targetAttribute' => 'token',
                'on' => 'remove'
            ],
            ['attribute', 'string'],
            ['attribute', 'in', 'range' => [
                'documents',
                'id_cards'
            ]],
        ];
    }

    public function getMember()
    {
        if ($this->_member == null) {
            $this->_member = Member::findOne($this->member_id);
        }

        return $this->_member;
    }

    public function getFile()
    {
        if ($this->_file == null) {
            $this->_file = File::findByToken($this->file_token);
        }

        return $this->_file;
    }


    public function save()
    {
        if ($this->validate()) {
            $member = $this->getMember();

            $arr = $member->{$this->attribute};

            if ($this->scenario == 'remove') {
                if (($key = array_search($this->file_token, $arr)) !== false) {
                    unset($arr[$key]);
                }
            }
            else {
                array_push($arr, $this->file_token);
            }

            $member->{$this->attribute} = $arr;
            
            if ($member->save()) {
                if ($this->scenario == 'remove') {
                    if (($file = $this->getFile()) != null) {
                        $file->unlink = $this->unlink;
                        $file->delete();
                    }
                }
                return $member;
            }
            else {
                $this->addError('member', $member->errors);
            }
        }
    }
}