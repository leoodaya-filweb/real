<?php

namespace app\models\form\transaction;

use Yii;
use app\models\Transaction;
use app\widgets\ObligationRequest;

class ObligationRequestForm extends \yii\base\Model
{
    public $transaction_id;
    public $obligation_request;

    public $_transaction;
    public $_member;

    public function rules()
    {
        return [
            [['transaction_id', 'obligation_request',], 'required'],
            ['obligation_request', 'string'],
            ['transaction_id', 'integer'],
            ['transaction_id', 'exist', 'targetClass' => 'app\models\Transaction', 'targetAttribute' => 'id'],
        ];
    }

    public function getTransaction()
    {
        if ($this->_transaction == null) {
            $this->_transaction = Transaction::findOne($this->transaction_id);
        }

        return $this->_transaction;
    }

    public function getMember()
    {
        if ($this->_member == null) {
            if (($transaction = $this->getTransaction()) != null) {
                $this->_member = $transaction->member;
            }
        }

        return $this->_member;
    }

    public function init()
    {
        parent::init();
        if (($member = $this->getMember()) != null) {
            $this->obligation_request = ObligationRequest::widget([
                'transaction' => $this->getTransaction(),
                'contentOnly' => true
            ]);
        }
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = $this->getTransaction();
            $transaction->obligation_request = $this->obligation_request;
            $transaction->obligation_request_status = Transaction::DOCUMENT_CLERK_CREATED;
            if ($transaction->save()) {
                return $transaction;
            }
            else {
                $this->addError('transaction', $transaction->errors);
            }
        }
    }
}