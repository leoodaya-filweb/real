<?php

use app\models\CivilStatus;
use app\models\EducationalAttainment;
use app\models\Member;
use app\models\Relation;
use app\models\Sex;
use yii\db\Expression;

$model = new \app\helpers\FixtureData(function($params) {
    return [
		'household_id' => 1,
		'last_name' => 'Last Name',
		'middle_name' => 'Middle Name',
		'first_name' => 'First Name',
		'sex' => Sex::male(),
		'birth_date' => '1979-06-16',
		'age' => 43,
		'birth_place' => 'GMA Cavite',
		'civil_status' => CivilStatus::single(),
		'email' => 'testemail@gmail.com',
		'contact_no' => '09124449532',
		'telephone_no' => '1234-123-1234',
        'head' => Member::FAMILY_HEAD_YES,
        'relation' => Relation::head(),
		'documents' => json_encode(['default-6ccb4a66-0ca3-46c7-88dd-default']),
        'educational_attainment' => (EducationalAttainment::find()->one())->value,
        'record_status' => Member::RECORD_ACTIVE,
		'created_by' => 1,
	    'updated_by' => 1,
		'created_at' => new Expression('UTC_TIMESTAMP'),
        'updated_at' => new Expression('UTC_TIMESTAMP'),
        'social_pension_status' => Member::NOT_SOCIAL_PENSIONER
    ];
});


$model->add('1', [], [
	'qr_id' => 'qr-code-1',
	'token' => 'token-1111',
	'slug' => 'slug-1111',
]);
$model->add('inactive', [], [
	'qr_id' => 'qr-code-2',
	'record_status' => Member::RECORD_INACTIVE,
	'token' => 'token-2222',
	'slug' => 'slug-2222',
    'head' => Member::FAMILY_HEAD_NO,
    'relation' => Relation::spouse(),
]);

$model->add('draft', [], [
	'qr_id' => 'qr-code-3',
	'household_id' => 3,
	'record_status' => Member::RECORD_DRAFT,
	'token' => 'token-3333',
	'slug' => 'slug-3333',
    'head' => Member::FAMILY_HEAD_NO,
    'relation' => Relation::spouse(),
]);

$model->add('draft-head', [], [
	'qr_id' => 'qr-code-4',
	'household_id' => 2,
	'record_status' => Member::RECORD_DRAFT,
	'token' => 'token-4444',
	'slug' => 'slug-4444',
    'head' => Member::FAMILY_HEAD_YES,
]);


$model->add('test', [], [
	'qr_id' => 'test-4',
	'household_id' => 1,
	'token' => 'test-4444',
	'slug' => 'test-4444',
    'head' => Member::FAMILY_HEAD_YES,
]);

$model->add('active-1', [], [
	'qr_id' => 'test-5',
	'household_id' => 1,
	'token' => 'test-5555',
	'slug' => 'test-5555',
]);

$model->add('senior', [], [
	'qr_id' => 'qr-code-46589',
	'token' => 'token-46589',
	'slug' => 'slug-46589',
	'birth_date' => '1910-06-16',
	'age' => 112
]);

$model->add('social-pensioner', [], [
	'qr_id' => 'qr-code-social-pensioner',
	'token' => 'token-social-pensioner',
	'slug' => 'slug-social-pensioner',
	'social_pension_status' => Member::SOCIAL_PENSIONER
]);

return $model->getData();