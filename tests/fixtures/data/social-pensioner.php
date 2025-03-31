<?php

use app\models\CivilStatus;
use app\models\Sex;
use app\models\SocialPensioner;
use yii\db\Expression;

$model = new \app\helpers\FixtureData(function($params) {
    return [
		'qr_id' => 'qr-code-1',
		'last_name' => 'Last Name',
		'middle_name' => 'Middle Name',
		'first_name' => 'First Name',
		'name_suffix' => 'Jr',
		'sex' => Sex::male(),
		'age' => 43,
		'birth_date' => '1979-06-16',
		'birth_place' => 'GMA Cavite',
		'civil_status' => CivilStatus::single(),
		'email' => 'testemail@gmail.com',
		'contact_no' => '09124449532',
		'house_no' => '9B',
		'street' => 'NONE',
		'barangay' => 'Poblacion I (Barangay 1)',
		'sitio' => 'Sitio',
		'purok' => '6',
		'educational_attainment' => 'No Grade',
		'occupation' => 'Occupation',
		'income' => 20000,
		'source_of_income' => 'Salary',
		'date_registered' => '2022-08-31',
		'photo' => '',
		'documents' => '',
		'pwd_score' => 0.2,
		'senior_score' => 0.2,
		'solo_parent_score' => 0.2,
		'solo_member_score' => 0.25,
		'accessibility_score' => 0.15,
		'record_status' => SocialPensioner::RECORD_ACTIVE,
        'created_by' => 1,
        'updated_by' => 1,
		'created_at' => new Expression('UTC_TIMESTAMP'),
        'updated_at' => new Expression('UTC_TIMESTAMP'),
        'slug' => 'first-name-last-name'
    ];
});

$model->add('1');
$model->add('inactive', [], [
	'qr_id' => '',
	'last_name' => 'test',
    'slug' => 'first-name-test',
	'record_status' => SocialPensioner::RECORD_INACTIVE
]);

return $model->getData();