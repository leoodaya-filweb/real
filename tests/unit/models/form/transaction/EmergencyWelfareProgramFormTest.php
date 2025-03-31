<?php

namespace tests\unit\models\form\transaction;

use app\models\Transaction;
use app\models\form\transaction\EmergencyWelfareProgramForm;

class EmergencyWelfareProgramFormTest extends \Codeception\Test\Unit
{
    private function data($replace=[])
    {
        return array_replace([
            'member_id' => 1,
            'claimant' => 'claimant',
            'patient_name' => 'Patient Name',
            'relation_to_patient' => 'Mother',
            'relation_type' => Transaction::CLIENT_IS_PATIENT,
            'diagnosis' => 'Acute Renal Failure',
            'client_category' => [
                'Children in need of special protection',
                'Youth in need of special protection'
            ],
            'recommended_services_assistance' => Transaction::MEDICAL_ASSISTANCE_CASH,
            'emergency_welfare_program' => Transaction::AICS_MEDICAL,
            'medical_procedure_requested' => 'medical_procedure_requested',
            'amount' => 99,
            'relation_type' => Transaction::CLIENT_IS_PATIENT
        ], $replace);
    }


    public function testValid()
    {
        $model = new EmergencyWelfareProgramForm($this->data());
        expect_that($model->save());

        $model = $this->tester->grabRecord('app\models\Transaction', [
            'member_id' => 1,
            'claimant' => 'claimant',
            'emergency_welfare_program' => Transaction::AICS_MEDICAL,
            'patient_name' => 'Patient Name',
            'relation_type' => Transaction::CLIENT_IS_PATIENT,
            'relation_to_patient' => 'Mother',
            'diagnosis' => 'Acute Renal Failure',
            'amount' => 99,
            'client_category' => json_encode([
                'Children in need of special protection',
                'Youth in need of special protection'
            ]),
            'recommended_services_assistance' => Transaction::MEDICAL_ASSISTANCE_CASH,
            'medical_procedure_requested' => 'medical_procedure_requested',
        ]);
    }

    public function testMemberIdInvalid()
    {
        $model = new EmergencyWelfareProgramForm($this->data(['member_id' => 'invalid']));
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testMemberIdNotExisting()
    {
        $model = new EmergencyWelfareProgramForm($this->data(['member_id' => 99999999]));
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testMemberIdRequired()
    {
        $model = new EmergencyWelfareProgramForm($this->data(['member_id' => '']));
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testEmergencyWelfareProgramRequired()
    {
        $model = new EmergencyWelfareProgramForm($this->data(['emergency_welfare_program' => '']));
        expect_not($model->save());
        expect($model->errors)->hasKey('emergency_welfare_program');
    }

    public function testTransactionIdRequiredOnUpdate()
    {
        $model = new EmergencyWelfareProgramForm($this->data(['transaction_id' => '']));
        $model->scenario = 'update';

        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');
    }

    public function testTransactionIdInvalidOnUpdate()
    {
        $model = new EmergencyWelfareProgramForm($this->data(['transaction_id' => 'invalid']));
        $model->scenario = 'update';
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');


        $model = new EmergencyWelfareProgramForm($this->data(['transaction_id' => 9999999]));
        $model->scenario = 'update';
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');
    }

    public function testEmergencyWelfareProgramInvalid()
    {
        $model = new EmergencyWelfareProgramForm($this->data(['emergency_welfare_program' => 'invalid']));
        expect_not($model->save());
        expect($model->errors)->hasKey('emergency_welfare_program');
    }

    public function testEmergencyWelfareProgramNotExisting()
    {
        $model = new EmergencyWelfareProgramForm($this->data(['emergency_welfare_program' => 9999]));
        expect_not($model->save());
        expect($model->errors)->hasKey('emergency_welfare_program');
    }

    public function testWithMedicine()
    {
        $model = new EmergencyWelfareProgramForm($this->data());
        $model->medicine = [
            'name' => [3, 2, 1],
            'quantity' => [3, 2, 1],
            'unit' => ['kg', 'ml', 'cubic'],
        ];

        $transaction = $model->save();
        expect_that($transaction);

        $this->tester->seeRecord('app\models\Medicine', [
            'transaction_id' => $transaction->id,
            'name' => 3,
            'quantity' => 3,
            'unit' => 'kg',
        ]);
    }

    public function testRelationTypeInvalid()
    {
        $model = new EmergencyWelfareProgramForm($this->data(['relation_type' => 9999]));
        expect_not($model->save());
        expect($model->errors)->hasKey('relation_type');

        $model = new EmergencyWelfareProgramForm($this->data(['relation_type' => 'invalid']));
        expect_not($model->save());
        expect($model->errors)->hasKey('relation_type');
    }

    public function testMedicalProcedureIsRequiredWhenAicsMedical()
    {
        $model = new EmergencyWelfareProgramForm($this->data([
            'emergency_welfare_program' => Transaction::AICS_MEDICAL
        ]));

        $model->medical_procedure_requested = null;
        expect_not($model->save());
        expect($model->errors)->hasKey('medical_procedure_requested');
    }

    public function testLaboratoryRequestIsRequiredWhenAicsLaboratoryRequest()
    {
        $model = new EmergencyWelfareProgramForm($this->data([
            'emergency_welfare_program' => Transaction::AICS_LABORATORY_REQUEST
        ]));

        $model->laboratory_procedure_requested = null;
        expect_not($model->save());
        expect($model->errors)->hasKey('laboratory_procedure_requested');
    }

    public function testDestinationIsRequiredWhenBalikProbinsyaProgram()
    {
        $model = new EmergencyWelfareProgramForm($this->data([
            'emergency_welfare_program' => Transaction::BALIK_PROBINSYA_PROGRAM
        ]));

        $model->destination_province = null;
        $model->destination_municipality = null;

        expect_not($model->save());
        expect($model->errors)->hasKey('destination_province');
        expect($model->errors)->hasKey('destination_municipality');
    }

    public function testReferralToIsRequiredWhenAssistanceLaboratoryRequest()
    {
        $model = new EmergencyWelfareProgramForm($this->data([
            'recommended_services_assistance' => Transaction::MEDICAL_ASSISTANCE_LAB_REQUEST
        ]));

        $model->referral_to = null;
        
        expect_not($model->save());
        expect($model->errors)->hasKey('referral_to');
    }

    public function testOtherRsaIsRequiredWhenAssistanceOtherRsa()
    {
        $model = new EmergencyWelfareProgramForm($this->data([
            'recommended_services_assistance' => Transaction::OTHER_RSA
        ]));

        $model->other_rsa = null;
        
        expect_not($model->save());
        expect($model->errors)->hasKey('other_rsa');
    }
}