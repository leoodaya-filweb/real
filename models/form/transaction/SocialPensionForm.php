<?php

namespace app\models\form\transaction;

use Yii;
use app\helpers\App;
use app\models\Member;
use app\models\Transaction;

class SocialPensionForm extends \yii\base\Model
{
    public $transaction_id;
    public $member_id;
    public $files = [];
    public $amount = 0;
    public $remarks = 'New Transaction';
    public $client_category = 'Senior citizen';

    public $_member;
    public $_transaction;

    public function rules()
    {
        return [
            [['member_id', 'files'], 'required'],
            [['member_id',], 'integer'],
            ['member_id', 'exist', 'targetClass' => 'app\models\Member', 'targetAttribute' => 'id'],
            [['files', 'remarks', ], 'safe'],
            ['amount', 'number'],
            [['transaction_id'], 'required', 'on' => 'update'],
            ['transaction_id', 'exist', 
                'targetClass' => 'app\models\Transaction', 
                'targetAttribute' => 'id',
                'on' => 'update'
            ],
            // ['amount', 'validateAmount'],
            ['member_id', 'validateMemberId'],
        ];
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['files'] = 'Requirement Files';

        return $labels;
    }

    public function validateMemberId($attribute, $params)
    {
        if (($member = $this->getMember()) != null) {
            if ($member->isSocialPensioner) {
                $this->addError($attribute, 'Member is already a pensioner');
            }
        }
    }

    public function validateAmount($attribute, $params)
    {
        $budget = App::setting('budget');

        if ($this->amount > $budget->totalAmount) {
            $this->addError($attribute, 'Amount is greater than the usable budget for this year.');
        }
    }

    public function getMember()
    {
        if ($this->_member == null) {
            $this->_member = Member::findOne($this->member_id);
        }

        return $this->_member;
    }

    public function init()
    {
        parent::init();

        if (($transaction = $this->getTransaction()) != null) {
            $this->member_id = $transaction->member_id;
            $this->files = $transaction->files;
            $this->amount = $transaction->amount;
            $this->remarks =  $transaction->remarks;
        }
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
            $transaction = $this->getTransaction() ?: new Transaction();
            $transaction->member_id = $this->member_id;
            $transaction->transaction_type = Transaction::SOCIAL_PENSION;
            $transaction->files = $this->files;
            $transaction->remarks = $this->remarks;
            $transaction->amount = $this->amount;
           
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

    public function search($params = [])
    {
        $member = $this->getMember();

        $searchModel = new TransactionSearch();
        $searchModel->member_id = $member->id;
        $searchModel->pagination = 10;
        $searchModel->transaction_type = Transaction::DEATH_ASSISTANCE;
        $dataProvider = $searchModel->search(['TransactionSearch' => $params]);

        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
    }

    public function getImageFiles()
    {
        if (($transaction = $this->getTransaction()) != null) {
            return $transaction->imageFiles;
        }
    }
}