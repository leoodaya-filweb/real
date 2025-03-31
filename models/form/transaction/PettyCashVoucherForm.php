<?php

namespace app\models\form\transaction;

use Yii;
use app\models\Transaction;
use app\widgets\PettyCashVoucher;

class PettyCashVoucherForm extends \yii\base\Model
{
    public $transaction_id;
    public $petty_cash_voucher;

    public $_transaction;
    public $_member;

    public function rules()
    {
        return [
            [['transaction_id', 'petty_cash_voucher',], 'required'],
            ['petty_cash_voucher', 'string'],
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
            $this->petty_cash_voucher = PettyCashVoucher::widget([
                'transaction' => $this->getTransaction(),
                'contentOnly' => true
            ]);
        }
    }

    public function save()
    {
        if ($this->validate()) {
            $transaction = $this->getTransaction();
            $transaction->petty_cash_voucher = $this->petty_cash_voucher;
            $transaction->petty_cash_voucher_status = Transaction::DOCUMENT_CLERK_CREATED;
            if ($transaction->save()) {
                return $transaction;
            }
            else {
                $this->addError('transaction', $transaction->errors);
            }
        }
    }
}