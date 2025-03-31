<?php

use app\models\File;
use yii\db\Expression;

$model = new \app\helpers\FixtureData(function($params) {
    return [
        'name' => $params['name'] ?? 'default-image_200', 
        'extension' => $params['extension'] ?? 'png',
        'size' => $params['size'] ?? 1606,
        'location' => $params['location'] ?? 'default/default-image_200.png',
        'tag' => 'setting',
        'token' => $params['token'] ?? 'default-6ccb4a66-0ca3-46c7-88dd-default',
        'record_status' => File::RECORD_ACTIVE,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => new Expression('UTC_TIMESTAMP'),
        'updated_at' => new Expression('UTC_TIMESTAMP'),
    ];
});

$model->add('profile');

$model->add('backup', [
    'name' => 'default-backup', 
    'extension' => 'sql',
    'tag' => 'backup',
    'size' => 81341,
    'location' => 'default/default-backup.sql',
    'token' => 'default-OxFBeC2Dzw1624513904-default',
]);

$model->add('household', [
    'name' => 'household', 
    'extension' => 'csv',
    'size' => 8000,
    'location' => 'default/tests/household.csv',
    'tag' => 'household',
    'token' => 'household-OxFBeC2Dzw1624513904-household',
]);
$model->add('household-test-update', [
    'name' => 'household-test-update', 
    'extension' => 'csv',
    'size' => 8000,
    'location' => 'default/tests/household-test-update.csv',
    'tag' => 'household',
    'token' => 'household-test-update-OxFBeC2Dzw1624513904-household-test-update',
]);

$model->add('invalid-household', [
    'name' => 'invalid-household-fromat', 
    'extension' => 'csv',
    'size' => 1000,
    'location' => 'default/tests/invalid-household-fromat.csv',
    'tag' => 'household',
    'token' => 'invalid-household-OxFBeC2Dzw1624513904-invalid-household',
]);

$model->add('members', [
    'name' => 'members', 
    'extension' => 'csv',
    'size' => 2000,
    'location' => 'default/tests/members.csv',
    'tag' => 'member',
    'token' => 'members-OxFBeC2Dzw1624513904-members',
]);

$model->add('members-test-update', [
    'name' => 'members-test-update', 
    'extension' => 'csv',
    'size' => 8000,
    'location' => 'default/tests/members-test-update.csv',
    'tag' => 'member',
    'token' => 'members-test-update-OxFBeC2Dzw1624513904-members-test-update',
]);


$model->add('household-map-icon', [
    'name' => 'household-map-icon', 
    'extension' => 'png',
    'size' => 2000,
    'location' => 'default/household-map-icon.png',
    'tag' => 'setting',
    'token' => 'household-map-icon-6ccb4a66-0ca3-46c7-88dd-household-map-icon',
]);

$model->add('municipal_id-template', [
    'name' => 'municipal_id-template', 
    'extension' => 'png',
    'size' => 2000,
    'location' => 'default/municipal_id-template.png',
    'tag' => 'setting',
    'token' => 'municipal_id-template-6ccb4a66-0ca3-46c7-88dd-municipal_id-template',
]);

$model->add('municipality-logo', [
    'name' => 'municipality-logo', 
    'extension' => 'png',
    'size' => 2000,
    'location' => 'default/municipality-logo.png',
    'tag' => 'setting',
    'token' => 'municipality-logo-6ccb4a66-0ca3-46c7-88dd-municipality-logo',
]);

$model->add('social-welfare-logo', [
    'name' => 'social-welfare-logo', 
    'extension' => 'png',
    'size' => 2000,
    'location' => 'default/social-welfare-logo.png',
    'tag' => 'setting',
    'token' => 'social-welfare-logo-6ccb4a66-0ca3-46c7-88dd-social-welfare-logo',
]);

$model->add('inactive', ['name' => 'default-inactive'], [
    'record_status' => File::RECORD_INACTIVE,
    'token' => 'inactive-OxFBeC2Dzw1624513904-inactive',
]);


$model->add('survey', [
    'name' => 'survey', 
    'extension' => 'csv',
    'size' => 8000,
    'location' => 'default/tests/survey.csv',
    'tag' => 'survey',
    'token' => 'survey-OxFBeC2Dzw1624513904-survey',
]);
$model->add('invalid-survey', [
    'name' => 'invalid-survey-format', 
    'extension' => 'csv',
    'size' => 1000,
    'location' => 'default/tests/invalid-survey-format.csv',
    'tag' => 'survey',
    'token' => 'invalid-survey-OxFBeC2Dzw1624513904-invalid-survey',
]);

$model->add('database-sample-import-file', [
    'name' => 'database-sample-import-file', 
    'extension' => 'csv',
    'size' => 8000,
    'location' => 'default/tests/database-sample-import-file.csv',
    'tag' => 'database',
    'token' => 'database-sample-import-file-OxFBeC2Dzw1624513904-database-sample-import-file',
]);
$model->add('invalid-database-sample-import-file', [
    'name' => 'invalid-database-sample-import-file', 
    'extension' => 'csv',
    'size' => 1000,
    'location' => 'default/tests/invalid-database-sample-import-file.csv',
    'tag' => 'database',
    'token' => 'invalid-database-sample-import-file-OxFBeC2Dzw1624513904-invalid-database-sample-import-file',
]);

return $model->getData();