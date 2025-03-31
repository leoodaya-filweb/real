<?php

use app\models\Transaction;
use yii\db\Expression;

$model = new \app\helpers\FixtureData(function($params) {
    return [
		'member_id' => 1,
		'patient_name' => 'Patient Name',
        'relation_to_patient' => 'Mother',
        'diagnosis' => 'Acute Renal Failure',
        'client_category' => json_encode([
            'Children in need of special protection',
            'Youth in need of special protection'
        ]),
		'transaction_type' => Transaction::EMERGENCY_WELFARE_PROGRAM,
		'emergency_welfare_program' => Transaction::AICS_LABORATORY_REQUEST,
		'amount' => 0,
		'status' => 1,
		'remarks' => 'Remarks',
		'files' => json_encode(['default-6ccb4a66-0ca3-46c7-88dd-default']),
		'record_status' => Transaction::RECORD_ACTIVE,
        'created_by' => 1,
        'updated_by' => 1,
        'recommended_services_assistance' => Transaction::MEDICAL_ASSISTANCE_CASH,
		'created_at' => new Expression('UTC_TIMESTAMP'),
        'updated_at' => new Expression('UTC_TIMESTAMP'),
    ];
});

$model->add('1', [], [
	'token' => 'token-1111'
]);
$model->add('inactive', [], [
	'member_id' => 1,
	'transaction_type' => Transaction::EMERGENCY_WELFARE_PROGRAM,
	'emergency_welfare_program' => Transaction::AICS_MEDICAL,
	'status' => 2,
	'record_status' => Transaction::RECORD_INACTIVE,
	'token' => 'token-2222'
]);

$model->add('social-pension-pending', [], [
	'member_id' => 6,
	'patient_name' => '',
    'relation_to_patient' => '',
    'diagnosis' => '',
    'client_category' => '',
    'recommended_services_assistance' => Transaction::OTHER_RSA,
	'transaction_type' => Transaction::SOCIAL_PENSION,
	'emergency_welfare_program' => 0,
	'status' => Transaction::MSWDO_HEAD_APPROVED,
	'social_pension_status' => Transaction::SOCIAL_PENSION_PENDING,
	'token' => 'token-3'
]);


return $model->getData();