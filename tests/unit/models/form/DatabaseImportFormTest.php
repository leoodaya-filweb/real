<?php

namespace tests\unit\models\form;

use app\helpers\App;
use app\models\Database;
use app\models\form\DatabaseImportForm;

class DatabaseImportFormTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'developer'
        ]));
    }
    
    protected function data($replace=[])
    {
        return array_replace([
            'file_token' => 'database-sample-import-file-OxFBeC2Dzw1624513904-database-sample-import-file',
        ], $replace);
    }

    public function testParse()
    {
        $model = new DatabaseImportForm($this->data());

        $data = $model->getData();
        expect(count($data))->equals(1);
        expect(count($data[1]))->equals(3);
    }

    public function testSave()
    {
        $model = new DatabaseImportForm($this->data());

        expect_that($model->save());

        $this->tester->seeRecord('app\models\Database', [
            'last_name' => 'Atendido Jr.',
            'first_name' => 'Timoteo',
            'middle_name' => 'Avellaneda',
            'pensioner' => 'No',
            'date_of_birth' => '1955-08-30'
        ]);

        $this->tester->seeRecord('app\models\Database', [
            'last_name' => 'Mijares',
            'first_name' => 'Jesus',
            'middle_name' => 'Avellano',
            'pensioner' => 'Yes',
            'relation_where' => 'SSS'
        ]);
    }


    public function testInvalidExtension()
    {
        $model = new DatabaseImportForm([
            'file_token' => 'default-6ccb4a66-0ca3-46c7-88dd-default',
            'scenario' => 'contentValidation'
        ]);
        expect_not($model->save());
        expect($model->errors)->hasKey('file_token');
    }

    public function testInvalidContentFormat()
    {
        $model = new DatabaseImportForm([
            'file_token' => 'invalid-database-sample-import-file-OxFBeC2Dzw1624513904-invalid-database-sample-import-file',
            'scenario' => 'contentValidation'
        ]);
        expect_not($model->save());
        expect($model->errors)->hasKey('file_token');
    }
}