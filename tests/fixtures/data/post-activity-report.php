<?php

use app\models\PostActivityReport;
use yii\db\Expression;

$model = new \app\helpers\FixtureData(function($params) {
    return [
		'date' => 'Date',
		'for' => 'For',
		'subject' => 'Subject',
		'title' => 'Title',
		'location' => 'Location',
		'date_of_activity' => 'Date Of Activity',
		'concerned_office' => 'Concerned Office',
		'highlights_of_activity' => 'Highlights Of Activity',
		'description' => 'Description',
		'photos' => 'Photos',
		'preapared_by' => 'Preapared By',
		'noted_by' => 'Noted By',
		'record_status' => PostActivityReport::RECORD_ACTIVE,
        'created_by' => 1,
        'updated_by' => 1,
		'created_at' => new Expression('UTC_TIMESTAMP'),
        'updated_at' => new Expression('UTC_TIMESTAMP'),
    ];
});

$model->add('1');
$model->add('inactive', [], [
	'record_status' => PostActivityReport::RECORD_INACTIVE
]);

return $model->getData();