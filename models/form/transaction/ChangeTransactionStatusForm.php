<?php

namespace app\models\form\transaction;

use Yii;
use app\helpers\App;
use app\models\Transaction;

class ChangeTransactionStatusForm extends \yii\base\Model
{
    public $transaction_id;
    public $status;
    public $remarks;

    public $_transaction;

    public function rules()
    {
        return [
            [['transaction_id', 'status', 'remarks'], 'required'],
            [['remarks'], 'string'],
            [['transaction_id', 'status'], 'integer'],
            ['transaction_id', 'exist', 
                'targetClass' => 'app\models\Transaction', 
                'targetAttribute' => 'id'
            ],
            ['status', 'in', 'range' => array_keys(App::keyMapParams('transaction_status'))]
        ];
    }

    public function getTransaction()
    {
        if ($this->_transaction == null) {
            $this->_transaction = Transaction::findOne($this->transaction_id);
        }

        return $this->_transaction;
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = $this->getTransaction();
            $transaction->status = $this->status;
            $transaction->remarks = $this->remarks;
            if ($transaction->save()) {
                return $transaction;
            }
            else {
                $this->addError('transaction', $transaction->errors);
            }
        }
    }
}