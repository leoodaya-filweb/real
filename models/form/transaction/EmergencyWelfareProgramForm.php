<?php

namespace app\models\form\transaction;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\models\Medicine;
use app\models\Member;
use app\models\Transaction;

class EmergencyWelfareProgramForm extends \yii\base\Model
{
    public $transaction_id;
    public $member_id;
    public $files = [];
    public $amount;
    public $emergency_welfare_program;
    public $remarks = 'New Transaction';
    public $claimant;
    public $medicine;

    public $patient_name;
    public $relation_type;
    public $relation_to_patient;
    public $diagnosis;

    public $client_category;
    public $recommended_services_assistance;

    public $medical_procedure_requested;
    public $laboratory_procedure_requested;
    public $destination_province;
    public $destination_municipality;

    public $referral_to;
    public $other_rsa;

    public $patient_id;

    public $_patient;
    public $_member;
    public $_transaction;
    public $_medicines;

    public function rules()
    {
        $AICS_MEDICAL = Transaction::AICS_MEDICAL;
        $AICS_LABORATORY_REQUEST = Transaction::AICS_LABORATORY_REQUEST;
        $AICS_MEDICAL_MEDICINE = Transaction::AICS_MEDICAL_MEDICINE;
        $CLIENT_IS_PATIENT = Transaction::CLIENT_IS_PATIENT;
        $BALIK_PROBINSYA_PROGRAM = Transaction::BALIK_PROBINSYA_PROGRAM;
        $MEDICAL_ASSISTANCE_LAB_REQUEST = Transaction::MEDICAL_ASSISTANCE_LAB_REQUEST;
        $OTHER_RSA = Transaction::OTHER_RSA;

        $MEDICAL_ASSISTANCE_CASH = Transaction::MEDICAL_ASSISTANCE_CASH;
        $BURIAL_ASSISTANCE = Transaction::BURIAL_ASSISTANCE;
        $TRANSPORTATION_ASSISTANCE = Transaction::TRANSPORTATION_ASSISTANCE;

        return [
            [['member_id', 'emergency_welfare_program'], 'required'],
            [['member_id', 'emergency_welfare_program'], 'integer'],
            ['emergency_welfare_program', 'in', 'range' => array_keys(App::keyMapParams('emergency_welfare_programs'))],
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
            ['medicine', 'safe'],
            [['patient_name', 'relation_to_patient', 'diagnosis'], 'string', 'max' => 225],
            [['diagnosis'], 'required', 'when' => function($model) {
                return $model->emergency_welfare_program == Transaction::AICS_MEDICAL
                    || $model->emergency_welfare_program == Transaction::AICS_LABORATORY_REQUEST
                    || $model->emergency_welfare_program == Transaction::AICS_MEDICAL_MEDICINE;
            }, 'whenClient' => "function (attribute, value) {
                let val = $('#emergencywelfareprogramform-emergency_welfare_program').val();
                return val == {$AICS_MEDICAL} || val == {$AICS_LABORATORY_REQUEST} || val == {$AICS_MEDICAL_MEDICINE};
            }"],
            [['recommended_services_assistance'], 'required'],
            [['client_category'], 'safe'],
            ['relation_type', 'required'],
            ['relation_type', 'integer'],
            ['relation_type', 'default', 'value' => Transaction::CLIENT_IS_PATIENT],
            ['relation_type', 'in', 'range' => array_keys(App::keyMapParams('patient_relation_types'))],
            [['relation_to_patient', 'patient_name'], 'required', 'when' => function($model) {
                return $model->relation_type != Transaction::CLIENT_IS_PATIENT;
            }, 'whenClient' => "function (attribute, value) {
                let val = document.querySelector(\".relation-type:checked\").value;
                return val != {$CLIENT_IS_PATIENT};
            }"],
            ['patient_id', 'integer'],
            ['patient_id', 'validatePatientId'],

            ['medical_procedure_requested', 'required', 'when' => function($model) {
                return $model->emergency_welfare_program == Transaction::AICS_MEDICAL;
            }, 'whenClient' => "function (attribute, value) {
                return $('#emergencywelfareprogramform-emergency_welfare_program').val() == {$AICS_MEDICAL};
            }"],
            ['laboratory_procedure_requested', 'required', 'when' => function($model) {
                return $model->emergency_welfare_program == Transaction::AICS_LABORATORY_REQUEST;
            }, 'whenClient' => "function (attribute, value) {
                return $('#emergencywelfareprogramform-emergency_welfare_program').val() == {$AICS_LABORATORY_REQUEST};
            }"],
            [['destination_province', 'destination_municipality'], 'required', 'when' => function($model) {
                return $model->emergency_welfare_program == Transaction::BALIK_PROBINSYA_PROGRAM;
            }, 'whenClient' => "function (attribute, value) {
                return $('#emergencywelfareprogramform-emergency_welfare_program').val() == {$BALIK_PROBINSYA_PROGRAM};
            }"],

            ['referral_to', 'required', 'when' => function($model) {
                return $model->recommended_services_assistance == Transaction::MEDICAL_ASSISTANCE_LAB_REQUEST;
            }, 'whenClient' => "function (attribute, value) {
                return $('#emergencywelfareprogramform-recommended_services_assistance').val() == {$MEDICAL_ASSISTANCE_LAB_REQUEST};
            }"],

            ['other_rsa', 'required', 'when' => function($model) {
                return $model->recommended_services_assistance == Transaction::OTHER_RSA;
            }, 'whenClient' => "function (attribute, value) {
                return $('#emergencywelfareprogramform-recommended_services_assistance').val() == {$OTHER_RSA};
            }"],
            [
                [
                    'medical_procedure_requested' , 
                    'laboratory_procedure_requested', 
                    'destination_province', 
                    'destination_municipality'
                ], 
                'string', 'max' => 225
            ],
            ['amount', 'required', 'when' => function($model) {
                return $model->recommended_services_assistance == Transaction::MEDICAL_ASSISTANCE_CASH 
                    || $model->recommended_services_assistance == Transaction::BURIAL_ASSISTANCE 
                    || $model->recommended_services_assistance == Transaction::TRANSPORTATION_ASSISTANCE; 
            }, 'whenClient' => "function (attribute, value) {
                let val = $('#emergencywelfareprogramform-recommended_services_assistance').val();
                return val == {$MEDICAL_ASSISTANCE_CASH} || val == {$BURIAL_ASSISTANCE} || val == {$TRANSPORTATION_ASSISTANCE};
            }"],
        ];
    }

    public function validatePatientId($attribute, $params)
    {
        if ($this->patient_id) {
            $patient = Member::findOne($this->patient_id);

            if ($patient == null) {
                $this->addError($attribute, 'Patient Id invalid');
            }
        }
    }

    public function setTheClaimant()
    {
        
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

    public function getPatientViewUrl()
    {
        if (($patient = $this->getPatient()) != null) {
            return $patient->viewUrl;
        }
    }

    public function getPatientViewUrlPersonalInformationTab()
    {
        if (($patient = $this->getPatient()) != null) {
            return $patient->viewUrlPersonalInformationTab;
        }
    }

    

    public function getPatient()
    {
        if ($this->_patient == null) {
            if (($transaction = $this->getTransaction()) != null) {
                $this->_patient = $transaction->patient;
            }
        }

        return $this->_patient;
    }

    public function init()
    {
        parent::init();

        if (($transaction = $this->getTransaction()) != null) {
            $this->member_id = $transaction->member_id;
            // $this->transaction_type = Transaction::EMERGENCY_WELFARE_PROGRAM;
            $this->emergency_welfare_program = $transaction->emergency_welfare_program;
            $this->files = $transaction->files;
            $this->remarks = $transaction->remarks;
            $this->amount = $transaction->amount;
            $this->claimant = $transaction->claimant;
            $this->patient_name = $transaction->patient_name;
            $this->relation_to_patient = $transaction->relation_to_patient;
            $this->diagnosis =  $transaction->diagnosis;
            $this->client_category = $transaction->client_category;
            $this->recommended_services_assistance = $transaction->recommended_services_assistance;
            $this->relation_type = $transaction->relation_type;
            $this->patient_id = $transaction->patient_id;

            $this->medical_procedure_requested =  $transaction->medical_procedure_requested;
            $this->laboratory_procedure_requested =  $transaction->laboratory_procedure_requested;
            $this->destination_province =  $transaction->destination_province;
            $this->destination_municipality =  $transaction->destination_municipality;
            
            $this->referral_to =  $transaction->referral_to;
            $this->other_rsa =  $transaction->other_rsa;
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
            $transaction->transaction_type = Transaction::EMERGENCY_WELFARE_PROGRAM;
            $transaction->emergency_welfare_program = $this->emergency_welfare_program;
            $transaction->files = $this->files;
            $transaction->remarks = $this->remarks;
            $transaction->amount = $this->amount;
            $transaction->claimant = $this->claimant;
            $transaction->patient_name =  $this->patient_name;
            $transaction->relation_to_patient =  $this->relation_to_patient;
            $transaction->diagnosis =  $this->diagnosis;
            $transaction->client_category =  $this->client_category;
            $transaction->recommended_services_assistance =  $this->recommended_services_assistance;
            $transaction->relation_type =  $this->relation_type;
            $transaction->patient_id =  $this->patient_id;
            $transaction->medical_procedure_requested =  $this->medical_procedure_requested;
            $transaction->laboratory_procedure_requested =  $this->laboratory_procedure_requested;
            $transaction->destination_province =  $this->destination_province;
            $transaction->destination_municipality =  $this->destination_municipality;
            $transaction->referral_to =  $this->referral_to;
            $transaction->other_rsa =  $this->other_rsa;


            if ($transaction->save()) {
                $this->saveMedicine($transaction);

                return $transaction;
            }
            else {
                $this->addError('transaction', $transaction->errors);
            }
        }
    }

    public function saveMedicine($transaction)
    {
        if ($this->medicine && is_array($this->medicine)) {
            $medicines = $this->medicine;

            foreach ($medicines['name'] as $key => $name) {
                $model = new Medicine([
                    'transaction_id' => $transaction->id,
                    'name' => (string)$medicines['name'][$key],
                    'quantity' => (int)$medicines['quantity'][$key],
                    'unit' => (string)$medicines['unit'][$key],
                ]);
                $model->save();
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
        $searchModel->transaction_type = Transaction::EMERGENCY_WELFARE_PROGRAM;
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

    public function getMedicines()
    {
        if ($this->_medicines == null) {
            $this->_medicines = Medicine::findAll(['transaction_id' => $this->transaction_id]);
        }

        return $this->_medicines;
    }

    public function getBtnClientProfile()
    {
        return Html::a('View Profile', $this->member->viewUrlPersonalInformationTab, [
            'class' => 'btn btn-sm btn-outline-primary font-weight-bold',
            'target' => '_blank',
            'style' => 'padding: 3px 8px;'
        ]);
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