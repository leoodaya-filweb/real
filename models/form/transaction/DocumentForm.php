<?php

namespace app\models\form\transaction;

use Yii;
use app\models\File;
use app\models\Transaction;

class DocumentForm extends \yii\base\Model
{
    public $transaction_id;
    public $file_token;

    public $_transaction;
    public $_file;

    public $unlink = true;

    public function rules()
    {
        return [
            [['transaction_id', 'file_token'], 'required'],
            ['file_token', 'string'],
            ['transaction_id', 'integer'],
            ['transaction_id', 'exist', 
                'targetClass' => 'app\models\Transaction', 
                'targetAttribute' => 'id'
            ],
            ['file_token', 'exist', 
                'targetClass' => 'app\models\File', 
                'targetAttribute' => 'token',
                'on' => 'remove'
            ],
        ];
    }

    public function getTransaction()
    {
        if ($this->_transaction == null) {
            $this->_transaction = Transaction::findOne($this->transaction_id);
        }

        return $this->_transaction;
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
            $transaction = $this->getTransaction();
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