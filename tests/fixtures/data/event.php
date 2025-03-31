<?php

use app\models\Event;
use app\models\EventCategory;
use yii\db\Expression;

$model = new \app\helpers\FixtureData(function($name) {
    return [
		'name' => $name,
		'description' => 'Description',
		'beneficiaries' => json_encode([]),
		'status' => Event::PENDING,
		'amount' => 100,
        'type' => Event::SEMINAR,
        'category_id' => (EventCategory::find()->one())->id,
		'photo' => '',
		'date_from' => new Expression('UTC_TIMESTAMP'),
        'date_to' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+ 1month')),
		'files' => json_encode(['default-6ccb4a66-0ca3-46c7-88dd-default']),
        'record_status' => Event::RECORD_ACTIVE,
		'created_by' => 1,
	    'updated_by' => 1,
		'created_at' => new Expression('UTC_TIMESTAMP'),
        'updated_at' => new Expression('UTC_TIMESTAMP'),
        'token' => 'token-1',
		'category_type' => Event::DEFAULT_CATEGORY,
    ];
});

$model->add('1', 'Name', [
    'token' => 'token-2',
]);
$model->add('test', 'test', [
    'token' => 'token-3',
]);
$model->add('inactive', 'Inactive', [
    'token' => 'token-4',
	'record_status' => Event::RECORD_INACTIVE
]);

$model->add('social-pension', 'social-pension', [
    'token' => 'social-pension',
    'category_id' => 0,
	'category_type' => Event::SOCIAL_PENSION_CATEGORY,
	'no_of_pensioner' => 100,
	'social_pension_fund' => Event::LOCAL_FUND,
]);

$model->add('unplanned-attendees', 'unplanned-attendees', [
    'token' => 'unplanned-attendees',
	'category_type' => Event::UN_PLANNED_CATEGORY,
]);

return $model->getData();