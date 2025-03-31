<?php

namespace tests\unit\models;

use app\helpers\App;
use app\models\Member;
use app\models\Transaction;

class TransactionTest extends \Codeception\Test\Unit
{
    protected function data($replace=[])
    {
        return array_replace([
            'member_id' => 1,
            'claimant' => 'claimant',
            'patient_name' => 'Patient Name',
            'relation_to_patient' => 'Mother',
            'diagnosis' => 'Acute Renal Failure',
            'transaction_type' => Transaction::EMERGENCY_WELFARE_PROGRAM,
            'emergency_welfare_program' => Transaction::AICS_MEDICAL,
            'amount' => 0,
            'status' => 1,
            'remarks' => 'Remarks',
            'files' => '[]',
            'record_status' => Transaction::RECORD_ACTIVE
        ], $replace);
    }

    public function testCreateSuccess()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'developer'
        ]));

        $model = new Transaction($this->data());
        expect_that($model->save());

        // $queues = \app\models\Queue::find()->count();
        // expect($queues)->equals(3);
    }

    public function testNoInactiveDataAccessRoleUserCreateInactiveData()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'no_inactive_data_access_role_user'
        ]));

        $data = $this->data(['record_status' => Transaction::RECORD_INACTIVE]);

        $model = new Transaction($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');

        \Yii::$app->user->logout();
    }

    public function testCreateNoData()
    {
        $model = new Transaction();
        expect_not($model->save());
    }

    public function testCreateInvalidRecordStatus()
    {
        $data = $this->data(['record_status' => 3]);

        $model = new Transaction($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testUpdateSuccess()
    {
        $model = $this->tester->grabRecord('app\models\Transaction', [
            'record_status' => Transaction::RECORD_ACTIVE
        ]);
        $model->record_status = 1;
      
        expect_that($model->save());
    }

    public function testDeleteSuccess()
    {
        $model = $this->tester->grabRecord('app\models\Transaction', [
            'id' => 2
        ]);
        expect_that($model->delete());
    }

    public function testActivateData()
    {
        $model = $this->tester->grabRecord('app\models\Transaction', [
            'record_status' => Transaction::RECORD_INACTIVE
        ]);
        expect_that($model);

        $model->activate();
        expect_that($model->save());
    }

    public function testGuestDeactivateData()
    {
        $model = $this->tester->grabRecord('app\models\Transaction', [
            'record_status' => Transaction::RECORD_ACTIVE
        ]);
        expect_that($model);

        $model->inactivate();
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testMemberIdInvalid()
    {
        $model = new Transaction($this->data(['member_id' => 'invalid']));
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testMemberIdNotExisting()
    {
        $model = new Transaction($this->data(['member_id' => 99999999]));
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testTransactionTypeInvalid()
    {
        $model = new Transaction($this->data(['transaction_type' => 'invalid']));
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_type');
    }

    public function testTransactionTypeNotExisting()
    {
        $model = new Transaction($this->data(['transaction_type' => 99999999]));
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_type');
    }

    public function testEmergencyWelfareProgramInvalid()
    {
        $model = new Transaction($this->data(['emergency_welfare_program' => 'invalid']));
        expect_not($model->save());
        expect($model->errors)->hasKey('emergency_welfare_program');
    }

    public function testEmergencyWelfareProgramNotExisting()
    {
        $model = new Transaction($this->data(['emergency_welfare_program' => 99999999]));
        expect_not($model->save());
        expect($model->errors)->hasKey('emergency_welfare_program');
    }

    public function testEmergencyWelfareProgramRequiredWhenEmergencyTransactionType()
    {
        $model = new Transaction($this->data());
        $model->emergency_welfare_program = '';
        expect_not($model->save());
        expect($model->errors)->hasKey('emergency_welfare_program');
    }

    public function testStatusInvalid()
    {
        $model = new Transaction($this->data());
        $model->status = 'invalid';

        expect_not($model->save());
        expect($model->errors)->hasKey('status');
    }

    public function testStatusNotExisting()
    {
        $model = new Transaction($this->data());
        $model->status = 99999999;
        expect_not($model->save());
        expect($model->errors)->hasKey('status');
    }

    /*public function testAmountGreaterThanUsableBudget()
    {
        $model = new Transaction($this->data());
        $model->amount = 9999999;
        expect_not($model->save());
        expect($model->errors)->hasKey('amount');
    }*/

    /*public function testCompleteProcessed()
    {
        $user = \Yii::$app->user;
        $tester = $this->tester;

        // CLERK
        $user->login($tester->grabRecord('app\models\User', ['username' => 'mswdo-clerk']));
        $model = new Transaction($this->data());
        expect_that($model->save());
        $tester->seeRecord('app\models\Transaction', ['id' => $model->id]);
        $user->logout();

        // MHO
        $user->login($tester->grabRecord('app\models\User', ['username' => 'mho']));
        $model->process();
        $tester->seeRecord('app\models\Transaction', [
            'id' => $model->id,
            'status' => Transaction::MHO_PROCESSING
        ]);
        $model->status = Transaction::MHO_APPROVED;
        $model->save();
        $user->logout();


        // CLERK
        $user->login($tester->grabRecord('app\models\User', ['username' => 'mswdo-clerk']));
        $model->process();
        $tester->seeRecord('app\models\Transaction', [
            'id' => $model->id,
            'status' => Transaction::MSWDO_CLERK_PROCESSING
        ]);
        $model->status = Transaction::MSWDO_CLERK_APPROVED;
        $model->save();
        $user->logout();


        // HEAD
        $user->login($tester->grabRecord('app\models\User', ['username' => 'mswdo-head']));
        $model->process();
        $tester->seeRecord('app\models\Transaction', [
            'id' => $model->id,
            'status' => Transaction::MSWDO_HEAD_PROCESSING
        ]);
        $model->status = Transaction::MSWDO_HEAD_APPROVED;
        $model->save();
        $user->logout();

        // MAYOR
        $user->login($tester->grabRecord('app\models\User', ['username' => 'mayor']));
        $model->process();
        $tester->seeRecord('app\models\Transaction', [
            'id' => $model->id,
            'status' => Transaction::MAYOR_PROCESSING
        ]);
        $model->status = Transaction::MAYOR_APPROVED;
        $model->save();
        $user->logout();

        // BUDGET OFFICER
        $user->login($tester->grabRecord('app\models\User', ['username' => 'budget-officer']));
        $model->process();
        $tester->seeRecord('app\models\Transaction', [
            'id' => $model->id,
            'status' => Transaction::BUDGET_OFFICER_PROCESSING
        ]);
        $model->status = Transaction::BUDGET_OFFICER_CERTIFIED;
        $model->save();
        $user->logout();


        // ACCOUNTING OFFICER
        $user->login($tester->grabRecord('app\models\User', ['username' => 'accounting-officer']));
        $model->process();
        $tester->seeRecord('app\models\Transaction', [
            'id' => $model->id,
            'status' => Transaction::ACCOUNTING_OFFICER_PROCESSING
        ]);
        $model->status = Transaction::ACCOUNTING_COMPLETED;
        $model->save();
        $user->logout();

        // DISBURSING OFFICER
        $user->login($tester->grabRecord('app\models\User', ['username' => 'disbursing-officer']));
        $model->process();
        $tester->seeRecord('app\models\Transaction', [
            'id' => $model->id,
            'status' => Transaction::DISBURSING_OFFICER_PROCESSING
        ]);
        $model->status = Transaction::DISBURSED;
        $model->save();
        $user->logout();


        // ACCOUNTING OFFICER
        $user->login($tester->grabRecord('app\models\User', ['username' => 'accounting-officer']));
        $model->process();
        $tester->seeRecord('app\models\Transaction', [
            'id' => $model->id,
            'status' => Transaction::ACCOUNTING_OFFICER_PROOFING
        ]);
        $model->status = Transaction::COMPLETED;
        $model->save();
        $user->logout();
    }*/

    public function testRequiredFieldsOnDeathAssistance()
    {
        $model = new Transaction($this->data());
        $model->transaction_type = Transaction::DEATH_ASSISTANCE;
        $model->name_of_deceased = '';
        $model->caused_of_death = '';
        $model->id_of_deceased = '';
        expect_not($model->save());
        expect($model->errors)->hasKey('name_of_deceased');
        expect($model->errors)->hasKey('caused_of_death');
        expect($model->errors)->hasKey('id_of_deceased');
    }

    public function testIdOfDeceasedInvalid()
    {
        $model = new Transaction($this->data());
        $model->transaction_type = Transaction::DEATH_ASSISTANCE;
        $model->claimant = 'claimant';
        $model->name_of_deceased = 'name_of_deceased';
        $model->caused_of_death = 'caused_of_death';
        $model->id_of_deceased = 'invalid';
        expect_not($model->save());

        expect($model->errors)->hasKey('id_of_deceased');
    }

    public function testIdOfDeceasedNotExisting()
    {
        $model = new Transaction($this->data());
        $model->transaction_type = Transaction::DEATH_ASSISTANCE;
        $model->claimant = 'claimant';
        $model->name_of_deceased = 'name_of_deceased';
        $model->caused_of_death = 'caused_of_death';
        $model->id_of_deceased = 999999;
        expect_not($model->save());

        expect($model->errors)->hasKey('id_of_deceased');
    }

    public function testValidDeathAssistance()
    {
        $model = new Transaction($this->data());
        $model->transaction_type = Transaction::DEATH_ASSISTANCE;
        $model->claimant = 'claimant';
        $model->name_of_deceased = 'name_of_deceased';
        $model->caused_of_death = 'caused_of_death';
        $model->id_of_deceased = 1;
        expect_that($model->save());
    }

    public function testSocialPensionCompleted()
    {
        $model = $this->tester->grabRecord('app\models\Transaction', [
            'id' => 1
        ]);

        $model->transaction_type = Transaction::SOCIAL_PENSION;
        $model->social_pension_status = Transaction::DOCUMENT_CLERK_CREATED;
        $model->status = Transaction::COMPLETED;

        expect_that($model->save());

        $this->tester->seeRecord('app\models\Member', [
            'id' => $model->member_id,
            'social_pension_status' => Member::SOCIAL_PENSIONER
        ]);

        $member = $model->member;
        $household = $member->household;
        $this->tester->seeRecord('app\models\SocialPensioner', [
            'qr_id' => $member->qr_id,
            'last_name' => $member->last_name,
            'middle_name' => $member->middle_name,
            'first_name' => $member->first_name,
            'sex' => $member->sex,
        ]);
    }
}