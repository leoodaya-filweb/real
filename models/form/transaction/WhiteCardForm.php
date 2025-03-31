<?php

namespace app\models\form\transaction;

use Yii;
use app\helpers\App;
use app\models\Transaction;

class WhiteCardForm extends \yii\base\Model
{
    public $transaction_id;
    public $white_card;

    public $_transaction;
    public $_member;

    public function rules()
    {
        return [
            [['transaction_id', 'white_card'], 'required'],
            [['white_card',], 'string'],
            [['transaction_id',], 'integer'],
            [['transaction_id',], 'exist', 'targetClass' => 'app\models\Transaction', 'targetAttribute' => 'id'],
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
            $transaction->white_card = $this->white_card;
            $transaction->white_card_status = Transaction::DOCUMENT_CLERK_CREATED;
            // $transaction->status = Transaction::WHITE_CARD_CREATED;
            $transaction->remarks = 'White Card was created.';
            if ($transaction->save()) {
                return $transaction;
            }
            else {
                $this->addError('transaction', $transaction->errors);
            }
        }
    }

    public function getIndexUrl()
    {
        return (new Transaction())->indexUrl;
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
        $reportTemplate = App::setting('reportTemplate');
        $reportTemplate->setData();

        if (($transaction = $this->getTransaction()) != null) {
            

            $member = $this->getMember();
            $member_m = $member;
            
            $patient = $transaction->patient;
            
            if($patient = $transaction->patient){
              $member = $patient;
            }
           // $transaction->patient_name && !$transaction->patient_id?:

            $content = $transaction->white_card ?: $reportTemplate->white_card;
            
            $rsa = ucwords($transaction->getRsa());

            $replace = [
                '[NAME]' => $transaction->patient_name && !$transaction->patient_id?$transaction->patient_name:$member->fullnameinitial,
                '[AGE]' => $transaction->patient_name && !$transaction->patient_id?'[AGE]':$member->currentAge,
                '[ADDRESS]' => ucfirst($member->address),
                '[BIRTHDAY]' => $transaction->patient_name && !$transaction->patient_id?'[BIRTHDAY]':$member->birthDate,
                '[CONTACT_NO]' => $member->contact_no?$member->contact_no:$member_m->contact_no,
                '[MEDICAL_PROBLEM]' => $transaction->diagnosis,
                '[ASSISTANCE]' => $rsa,
            ];

            $this->white_card = str_replace(array_keys($replace), array_values($replace), $content);
        }
    }
}