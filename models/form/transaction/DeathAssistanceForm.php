<?php

namespace app\models\form\transaction;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\models\Member;
use app\models\Transaction;

class DeathAssistanceForm extends \yii\base\Model
{
    public $transaction_id;
    public $member_id;
    public $files = [];
    public $amount = 5000;
    public $remarks = 'New Transaction';

    public $claimant;
    public $relation_type;
    public $name_of_deceased;
    public $caused_of_death;
    public $id_of_deceased;
    public $relation_to_patient;

    public $client_category;
    public $recommended_services_assistance;

    public $_deceased;
    public $_member;
    public $_transaction;

    public function rules()
    {
        return [
            [['member_id', 'relation_to_patient'], 'required'],
            [['member_id',], 'integer'],
            ['member_id', 'exist', 'targetClass' => 'app\models\Member', 'targetAttribute' => 'id'],
            [['files', 'remarks', ], 'safe'],
            ['amount', 'number', 'min' => 1],
            [['transaction_id'], 'required', 'on' => 'update'],
            ['transaction_id', 'exist', 
                'targetClass' => 'app\models\Transaction', 
                'targetAttribute' => 'id',
                'on' => 'update'
            ],
            // ['amount', 'validateAmount'],
            [['claimant', 'name_of_deceased', 'caused_of_death', 'id_of_deceased', 'amount'], 'required'],
            [['claimant', 'name_of_deceased', 'relation_to_patient'], 'string', 'max' => 225],
            ['caused_of_death', 'safe'],
            ['id_of_deceased', 'integer'],
            ['id_of_deceased', 'validateIdOfDeceased'],
            [['recommended_services_assistance'], 'required'],
            [['client_category'], 'safe'],
            ['relation_type', 'required'],
            ['relation_type', 'integer'],
            ['relation_type', 'default', 'value' => Transaction::MEMBER_OF_HOUSEHOLD],
            ['relation_type', 'in', 'range' => array_keys(App::keyMapParams('patient_relation_types'))],
        ];
    }

    public function validateIdOfDeceased($attribute, $params)
    {
        if ($this->id_of_deceased) {
            if (($member = Member::findOne($this->id_of_deceased)) == null) {
                $this->addError($attribute, 'No member found.');
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

    public function setTheClaimant()
    {
        if (($member = $this->member) != null) {
            $this->claimant = $member->fullname;
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
            $this->claimant = $transaction->claimant;
            $this->name_of_deceased = $transaction->name_of_deceased;
            $this->caused_of_death = $transaction->caused_of_death;
            $this->id_of_deceased = $transaction->id_of_deceased;
            $this->client_category =  $transaction->client_category;
            $this->recommended_services_assistance =  $transaction->recommended_services_assistance;
            $this->relation_to_patient =  $transaction->relation_to_patient;
            $this->relation_type = $this->relation_type ?: Transaction::MEMBER_OF_HOUSEHOLD;
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
            $transaction->transaction_type = Transaction::DEATH_ASSISTANCE;
            $transaction->files = $this->files;
            $transaction->remarks = $this->remarks;
            $transaction->amount = $this->amount;
            $transaction->claimant = $this->claimant;
            $transaction->name_of_deceased = $this->name_of_deceased;
            $transaction->caused_of_death = $this->caused_of_death;
            $transaction->id_of_deceased = $this->id_of_deceased;
            $transaction->client_category =  $this->client_category;
            $transaction->recommended_services_assistance =  $this->recommended_services_assistance;
            $transaction->relation_to_patient =  $this->relation_to_patient;
            $transaction->relation_type =  $this->relation_type;

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

    public function getDeceasedMember()
    {
        if ($this->_deceased == null) {
            $this->_deceased = Member::findOne($this->id_of_deceased);
        }

        return $this->_deceased;
    }

    public function getPatientViewUrlPersonalInformationTab()
    {
        if (($patient = $this->getDeceasedMember()) != null) {
            return $patient->viewUrlPersonalInformationTab;
        }
    }

    public function getBtnPatientProfile()
    {
        return Html::a('View Profile', $this->patientViewUrlPersonalInformationTab, [
            'class' => 'btn btn-sm btn-outline-primary font-weight-bold',
            'target' => '_blank',
            'style' => 'padding: 3px 8px;'
        ]);
    }
}