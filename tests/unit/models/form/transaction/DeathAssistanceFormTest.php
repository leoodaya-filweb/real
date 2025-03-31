<?php

namespace tests\unit\models\form\transaction;

use app\models\Transaction;
use app\models\form\transaction\DeathAssistanceForm;

class DeathAssistanceFormTest extends \Codeception\Test\Unit
{
    public function data()
    {
        return [
            'member_id' => 1,
            'amount' => 99,
            'claimant' => 'claimant',
            'name_of_deceased' => 'name_of_deceased',
            'caused_of_death' => 'caused_of_death',
            'id_of_deceased' => 2,
            'relation_type' => Transaction::CLIENT_IS_PATIENT,
            'relation_to_patient' => 'father',
            'client_category' => 'Client Category',
            'recommended_services_assistance' => Transaction::MEDICAL_ASSISTANCE_CASH
        ];
    }

    public function testValid()
    {
        $model = new DeathAssistanceForm($this->data());
        expect_that($model->save());

        $this->tester->seeRecord('app\models\Transaction', [
            'member_id' => 1,
            'transaction_type' => Transaction::DEATH_ASSISTANCE,
            'amount' => 99
        ]);
    }

    public function testMemberIdInvalid()
    {
        $model = new DeathAssistanceForm(['member_id' => 'invalid']);
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testMemberIdNotExisting()
    {
        $model = new DeathAssistanceForm(['member_id' => 99999999]);
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testMemberIdRequired()
    {
        $model = new DeathAssistanceForm(['member_id' => '']);
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testTransactionIdRequiredOnUpdate()
    {
        $model = new DeathAssistanceForm(['transaction_id' => '']);
        $model->scenario = 'update';

        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');
    }

    public function testTransactionIdInvalidOnUpdate()
    {
        $model = new DeathAssistanceForm(['transaction_id' => 'invalid']);
        $model->scenario = 'update';
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');


        $model = new DeathAssistanceForm(['transaction_id' => 9999999]);
        $model->scenario = 'update';
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');
    }

    public function testAmountIsLessThanOne()
    {
        $model = new DeathAssistanceForm($this->data());
        $model->amount = 0;
        
        expect_not($model->save());
        expect($model->errors)->hasKey('amount');
    }

    public function testRequiredFieldsOnDeathAssistance()
    {
        $model = new DeathAssistanceForm($this->data());
        $model->amount = '';
        $model->claimant = '';
        $model->name_of_deceased = '';
        $model->caused_of_death = '';
        $model->id_of_deceased = '';
        
        expect_not($model->save());
        expect($model->errors)->hasKey('amount');
        expect($model->errors)->hasKey('claimant');
        expect($model->errors)->hasKey('name_of_deceased');
        expect($model->errors)->hasKey('caused_of_death');
        expect($model->errors)->hasKey('id_of_deceased');
    }

    public function testIdOfDeceasedInvalid()
    {
        $model = new DeathAssistanceForm($this->data());
        $model->claimant = 'claimant';
        $model->name_of_deceased = 'name_of_deceased';
        $model->caused_of_death = 'caused_of_death';
        $model->id_of_deceased = 'invalid';
        expect_not($model->save());

        expect($model->errors)->hasKey('id_of_deceased');
    }

    public function testIdOfDeceasedNotExisting()
    {
        $model = new DeathAssistanceForm($this->data());
        $model->claimant = 'claimant';
        $model->name_of_deceased = 'name_of_deceased';
        $model->caused_of_death = 'caused_of_death';
        $model->id_of_deceased = 999999;
        expect_not($model->save());

        expect($model->errors)->hasKey('id_of_deceased');
    }

    public function testValidDeathAssistance()
    {
        $model = new DeathAssistanceForm($this->data());
        $model->claimant = 'claimant';
        $model->name_of_deceased = 'name_of_deceased';
        $model->caused_of_death = 'caused_of_death';
        $model->id_of_deceased = 1;
        expect_that($model->save());
    }

    public function testInvalidRelationType()
    {
        $model = new DeathAssistanceForm($this->data());
        $model->relation_type = 99999;
        expect_not($model->save());
        expect($model->errors)->hasKey('relation_type');
    }
}