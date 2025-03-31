<?php

namespace app\behaviors;

use app\helpers\App;
use app\models\Role;
use app\models\User;
use app\models\Queue;
use app\models\Budget;
use app\models\Member;
use yii\db\Expression;
use app\models\Database;
use app\models\Medicine;
use yii\db\ActiveRecord;
use app\models\Transaction;
use app\models\Notification;
use yii\helpers\ArrayHelper;
use app\jobs\NotificationJob;
use app\models\TransactionLog;
use app\models\SocialPensioner;

class TransactionBehavior extends \yii\base\Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'eventAfterUpdate',
            ActiveRecord::EVENT_AFTER_INSERT => 'eventAfterInsert',
            ActiveRecord::EVENT_BEFORE_INSERT => 'eventBeforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'eventBeforeUpdate',
        ];
    }

    public function clientIsPatient()
    {
        if ($this->owner->relation_type == Transaction::CLIENT_IS_PATIENT) {
            $this->owner->relation_to_patient = null;
            $this->owner->patient_name = null;
            $this->owner->patient_id = 0;
        }

        $this->owner->patient_id = $this->owner->patient_id ?: 0;
    }

    public function checkMedicalProcedure()
    {
        if ($this->owner->emergency_welfare_program == Transaction::AICS_MEDICAL) {
            $this->owner->laboratory_procedure_requested = null;
            $this->owner->destination_province = null;
            $this->owner->destination_municipality = null;
        }
    }

    public function checkLaboratoryProcedure()
    {
        if ($this->owner->emergency_welfare_program == Transaction::AICS_LABORATORY_REQUEST) {
            $this->owner->medical_procedure_requested = null;
            $this->owner->destination_province = null;
            $this->owner->destination_municipality = null;
        }
    }

    public function checkBalikBayan()
    {
        if ($this->owner->emergency_welfare_program == Transaction::BALIK_PROBINSYA_PROGRAM) {
            $this->owner->medical_procedure_requested = null;
            $this->owner->laboratory_procedure_requested = null;
        }
    }

    public function setTheClaimant()
    {
        $this->owner->claimant = $this->owner->memberFullname;
    }

    public function eventBeforeUpdate($event)
    {
        $this->clientIsPatient();
        $this->checkMedicalProcedure();
        $this->setTheClaimant();
        $this->checkLaboratoryProcedure();
        $this->checkBalikBayan();
    }

    public function eventBeforeInsert($event)
    {
        $this->clientIsPatient();
        $this->checkMedicalProcedure();
        $this->setTheClaimant();
        $this->checkLaboratoryProcedure();
        $this->checkBalikBayan();

        if (($member = $this->owner->member) != null) {
            if ($member->isSocialPensioner) {
                $this->owner->addError('member_id', 'Member is already a pensioner');
            }
        }
    }

    public function checkDeceasedMember()
    {
        $model = $this->owner;

        if ($model->isDeathAssistance) {
            $member = Member::findOne($model->id_of_deceased);

            if ($member) {
                if ($member->living_status == Member::ALIVE) {
                    $member->living_status = Member::DECEASED;
                    $member->save();
                }
            }
        }
    }

    public function eventAfterInsert($event)
    {
        $model = $this->owner;

        $this->insertLog($model);

        $this->insertQueue(User::find()->mswdo_head()->all(), [
            'type' => Notification::NEW_TRANSACTION,
            'message' => "{$model->memberFullname} was request Medical Transaction",
            'link' => $model->getViewUrl(false),
        ]);

        $this->checkDeceasedMember();
    }

    public function addSeniorToDatabase()
    {
        if ($this->owner->status == Transaction::COMPLETED 
            && $this->owner->isSeniorCitizenIdApplication
            && $this->owner->member->database == null) {

            $member = $this->owner->member;

            $database = new Database([
                'last_name' => $member->last_name,
                'first_name' => $member->first_name,
                'middle_name' => $member->middle_name,
                'priority_sector' => Database::SC_ID,
                'sector_id' => 'SC-' . time(),
                'date_of_birth' => $member->birth_date,
                'birth_place' => $member->birth_place ?: 'None',
                'civil_status' => $member->civilStatusName,
                'date_registered' => App::formatter()->asDateToTimezone($this->owner->updated_at, 'Y-m-d'),
                'barangay' => $member->barangayName ?: 'None',
                'municipality' => App::setting('address')->municipalityName,
                'gender' => $member->sexLabel,
                'age' => $member->currentAge,
                'educ_attainment' => $member->educationalAttainmentLabel,
                'occupation' => $member->occupationName,
                'monthly_income' => (int)$member->income,
                'other_income_source_amount' => $member->source_of_income,
                'house_no' => $member->household ? $member->household->blkNo: '',
                'street' => $member->household ? $member->household->streetName: '',
                'pensioner' => $member->isPensioner ? 'Yes': 'No',
                'relation_where' => $member->pensioner_from,

                'amount_of_pension' => (int)$member->pension_amount,

                'id_cards' => $member->id_cards,
                'documents' => $member->documents,
            ]);

            if ($member->senior_citizen_id) {
                array_push($database->id_cards, $member->senior_citizen_id);
            }

            if (($familyCompositions = $member->myFamilyCompositions) != null) {
                $fc = [];

                for ($i=0; $i < 10; $i++) { 

                    $fc[$i]['no'] = ($i + 1);

                    if (isset($familyCompositions[$i])) {
                        $d = $familyCompositions[$i];
                        $fc[$i]['name'] = strtoupper($d->name) ?? '';
                        $fc[$i]['birth_date'] = $d->birth_date ?? '';
                        $fc[$i]['age'] = $d->currentAge ?? '';
                        $fc[$i]['civil_status'] = $d->civilStatusName ?? '';
                        $fc[$i]['relationship'] = $d->relationName ?? '';
                        $fc[$i]['occupation'] = $d->occupationName ?? '';
                        $fc[$i]['income'] = (int)$d->income;
                    }
                    else {
                        $fc[$i]['name'] = '';
                        $fc[$i]['birth_date'] = '';
                        $fc[$i]['age'] = '';
                        $fc[$i]['civil_status'] = '';
                        $fc[$i]['relationship'] = '';
                        $fc[$i]['occupation'] = '';
                        $fc[$i]['income'] = '';
                    }
                }

                    
                $database->family_composition = $fc;
            }

            $database->save();
        }
    }

    public function addToSocialPensioner()
    {
        if ($this->owner->status == Transaction::COMPLETED && $this->owner->isSocialPension) {
            $member = $this->owner->member;
            $household = $member->household;

            $model = new SocialPensioner([
                'qr_id' => $member->qr_id,
                'last_name' => $member->last_name,
                'middle_name' => $member->middle_name,
                'first_name' => $member->first_name,
                'sex' => $member->sex,
                'birth_date' => $member->birth_date,
                'birth_place' => $member->birth_place ?: 'None',
                'civil_status' => $member->civil_status,
                'email' => $member->email,
                'contact_no' => $member->contact_no,
                'house_no' => $household->blkNo,
                'street' => $household->streetName,
                'barangay' => $household->barangayName,
                'sitio' => $household->sitio,
                'purok' => $household->purok_no,
                'educational_attainment' => $member->educationalAttainmentLabel,
                'occupation' => $member->occupation,
                'income' => (int) $member->income,
                'source_of_income' => $member->source_of_income,
                'date_registered' => App::formatter()->asDateToTimezone('', 'Y-m-d'),
                'photo' =>  $member->photo,
                'documents' => $member->documents,
                'status' => SocialPensioner::PENDING
                // 'pwd_score' => 0.2,
                // 'senior_score' => 0.2,
                // 'solo_parent_score' => 0.2,
                // 'solo_member_score' => 0.25,
            ]);
            $model->is_pwd = $member->isPwd;
            $model->is_senior = $member->isSeniorAge;
            $model->is_solo_parent = $member->isSoloParent;
            $model->is_solo_member = $member->isSoloMember;

            if ($model->save()) {
            }
            else {
                $this->owner->addError('social_pensioner', $model->errorSummary);
            }
        }
    }

    public function eventAfterUpdate($event)
    {
        $model = $this->owner;

        if ($model->emergency_welfare_program != Transaction::AICS_MEDICAL_MEDICINE) {
            Medicine::deleteAll(['transaction_id' => $model->id]);
        }

        $this->checkDeceasedMember();

        if (isset($event->changedAttributes['status'])) {
            if ($this->owner->isSocialPension && $this->owner->status == Transaction::COMPLETED) {
                Member::updateAll(
                    ['social_pension_status' => Member::SOCIAL_PENSIONER],
                    ['id' => $this->owner->member_id],
                );
                $this->addToSocialPensioner();
            }
            
            $this->addSeniorToDatabase();
        }

        /*if (isset($event->changedAttributes['status'])) {
            $this->insertLog($model);

            $mho_status = [
                Transaction::MHO_APPROVED,
                Transaction::MHO_DECLINED,
            ];
            if (in_array($model->status, $mho_status)) {
                $this->insertQueue(User::find()->mswdo_clerk()->all(),  [
                    'type' => Notification::MHO_TRANSACTION,
                    'message' => $model->transactionTypeName,
                    'link' => $model->getViewUrl(false),
                ]);
            }

            if ($model->status == Transaction::PAYMENT_COMPLETED) {
                $this->insertQueue(User::find()->mswdo_clerk()->all(),  [
                    'type' => Notification::TREASURER_TRANSACTION,
                    'message' => $model->transactionTypeName,
                    'link' => $model->getViewUrl(false),
                ]);
            }

            if ($model->status == Transaction::MSWDO_CLERK_APPROVED) {
                if ($model->isSeniorCitizenIdApplication) {
                    $this->insertQueue(User::find()->treasurer()->all(), [
                        'type' => Notification::CLERK_TRANSACTION,
                        'message' => $model->transactionTypeName,
                        'link' => $model->getViewUrl(false),
                    ]);
                }
                else {
                    $this->insertQueue(User::find()->mswdo_head()->all(), [
                        'type' => Notification::CLERK_TRANSACTION,
                        'message' => $model->transactionTypeName,
                        'link' => $model->getViewUrl(false),
                    ]);
                }
            }

            if ($model->status == Transaction::MSWDO_HEAD_APPROVED) {
                $this->insertQueue(User::find()->mayor()->all(), [
                    'type' => Notification::MSWDO_HEAD_TRANSACTION,
                        'message' => $model->transactionTypeName,
                    'link' => $model->getViewUrl(false),
                ]);
            }

            if ($model->status == Transaction::MAYOR_APPROVED) {
                $this->insertQueue(User::find()->budget_officer()->all(), [
                    'type' => Notification::MAYOR_TRANSACTION,
                    'message' => $model->transactionTypeName,
                    'link' => $model->getViewUrl(false),
                ]);
            }

            if ($model->status == Transaction::BUDGET_OFFICER_CERTIFIED) {
                $this->insertQueue(User::find()->accounting_officer()->all(), [
                    'type' => Notification::BUDGET_OFFICER_TRANSACTION,
                    'message' => $model->transactionTypeName,
                    'link' => $model->getViewUrl(false),
                ]);

                $this->insertQueue(User::find()->mswdo_clerk()->all(), [
                    'type' => Notification::BUDGET_OFFICER_TRANSACTION,
                    'message' => $model->transactionTypeName,
                    'link' => $model->getViewUrl(false),
                ]);

                $budget = new Budget();
                $budget->type = $model->transaction_type;
                $budget->specific_to = Budget::TRANSACTION;
                $budget->model_id = $model->id;
                $budget->subtract($model->amount);
            }

            if ($model->status == Transaction::ACCOUNTING_COMPLETED) {
                $this->insertQueue(User::find()->disbursing_officer()->all(), [
                    'type' => Notification::ACCOUNTING_TRANSACTION,
                    'message' => $model->transactionTypeName,
                    'link' => $model->getViewUrl(false),
                ]);
            }
            
            if ($model->status == Transaction::DISBURSED) {
                $this->insertQueue(User::find()->accounting_officer()->all(), [
                    'type' => Notification::DISBURSING_TRANSACTION,
                    'message' => $model->transactionTypeName,
                    'link' => $model->getViewUrl(false),
                ]);
            }
        }*/
    }

    public function insertQueue($users, $data)
    {
        $user_id = array_keys(ArrayHelper::map($users, 'id', 'id'));

        $data['user_id'] = array_unique($user_id);


        if (is_array($data['user_id'])) {
            foreach ($data['user_id'] as $user_id) {
                $result = $this->insertNotification($user_id, $data);
            }
        }
        else {
            $result = $this->insertNotification($data['user_id'], $data);
        }

        // Queue::push(new NotificationJob($data));
    }

    public function insertNotification($user_id, $data)
    {
        $identity = App::identity();

        $notification = new Notification([
            'status' => Notification::STATUS_UNREAD,
            'record_status' => Notification::RECORD_ACTIVE,
            'user_id' => $user_id,
            'type' => $data['type'],
            'link' => $data['link'],
            'message' => $data['message'],
        ]);
        $notification->created_by = $identity ? $identity->id: 0;
        $notification->updated_by = $identity ? $identity->id: 0;
        $notification->created_at = new Expression('UTC_TIMESTAMP');
        $notification->updated_at = new Expression('UTC_TIMESTAMP');

        return $notification->save();
    }

    protected function insertLog($transaction)
    {
        $log = new TransactionLog([
            'transaction_id' => $transaction->id,
            'status' => $transaction->status,
            'remarks' => $transaction->remarks
        ]);

        if ($log->save()) {
            // code...
        }
        else {
            $this->owner->addError('transaction_log', $log->errors);
        }
    }
}