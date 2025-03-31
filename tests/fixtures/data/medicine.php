<?php

use app\models\Medicine;
use yii\db\Expression;

$model = new \app\helpers\FixtureData(function($params) {
    return [
		'transaction_id' => 1,
		'name' => 'Name',
		'price' => 100,
		'quantity' => 100,
		'unit' => 'litres',
		'record_status' => Medicine::RECORD_ACTIVE,
        'created_by' => 1,
        'updated_by' => 1,
		'created_at' => new Expression('UTC_TIMESTAMP'),
        'updated_at' => new Expression('UTC_TIMESTAMP'),
    ];
});

$model->add('1');
$model->add('inactive', [], [
	'record_status' => Medicine::RECORD_INACTIVE
]);

return $model->getData();