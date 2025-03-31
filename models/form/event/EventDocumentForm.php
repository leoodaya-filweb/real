<?php

namespace app\models\form\event;

use Yii;
use app\models\File;
use app\models\Event;

class EventDocumentForm extends \yii\base\Model
{
    public $event_id;
    public $file_token;

    public $_event;
    public $_file;

    public $unlink = true;

    public function rules()
    {
        return [
            [['event_id', 'file_token'], 'required'],
            ['file_token', 'string'],
            ['event_id', 'integer'],
            ['event_id', 'exist', 
                'targetClass' => 'app\models\Event', 
                'targetAttribute' => 'id'
            ],
            ['file_token', 'exist', 
                'targetClass' => 'app\models\File', 
                'targetAttribute' => 'token',
                'on' => 'remove'
            ],
        ];
    }

    public function getEvent()
    {
        if ($this->_event == null) {
            $this->_event = Event::findOne($this->event_id);
        }

        return $this->_event;
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
            $transaction = $this->getEvent();
            $files = $transaction->files;

            if ($this->scenario == 'remove') {
                if (($key = array_search($this->file_token, $files)) !== false) {
                    unset($files[$key]);
                }
            }
            else {
                array_push($files, $this->file_token);
            }

            $transaction->files = $files;
            
            $transaction->_canUpdate = true;
            if ($transaction->save()) {
                if ($this->scenario == 'remove') {
                    if (($file = $this->getFile()) != null) {
                        $file->unlink = $this->unlink;
                        $file->delete();
                    }
                }
                return $transaction;
            }
            else {
                $this->addError('transaction', $transaction->errors);
            }
        }
    }
}