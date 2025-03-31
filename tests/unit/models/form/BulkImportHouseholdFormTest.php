<?php

namespace tests\unit\models\form;

use app\helpers\App;
use app\models\Region;
use app\models\Province;
use app\models\Municipality;
use app\models\form\BulkImportHouseholdForm;

class BulkImportHouseholdFormTest extends \Codeception\Test\Unit
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
            'file_token' => 'household-OxFBeC2Dzw1624513904-household',
        ], $replace);
    }

    public function testParse()
    {
        $model = new BulkImportHouseholdForm($this->data());

        $data = $model->getData();
        expect(count($data))->equals(1);
        expect(count($data[1]))->equals(4);
    }

    public function testSave()
    {
        $model = new BulkImportHouseholdForm($this->data());
        expect_that($model->save());

        $this->tester->seeRecord('app\models\Household', [
            'no' => 729760,
            // 'transfer_date' => date('Y-m-d H:i:s', strtotime('2015-02-16 13:35:36.343')),
            'longitude' => '121.6030581482',
            'latitude' => '14.6696698107',
            'altitude' => '65.8',
            'region_id' => Region::getRegion4aId(),
            'province_id' => Province::getCalabarzonId(),
            'municipality_id' => Municipality::getRealId(),
            'zone_no' => 99,
            'barangay_id' => 3,
            'purok_no' => 6,
            'street' => 'NONE'
        ]);

        $this->tester->seeRecord('app\models\Household', [
            'no' => 729800,
            // 'transfer_date' => date('Y-m-d H:i:s', strtotime('2015-02-16 13:39:37.421')),
            'longitude' => '121.6030081082',
            'latitude' => '14.6699229861',
            'altitude' => '38.3',
            'region_id' => Region::getRegion4aId(),
            'province_id' => Province::getCalabarzonId(),
            'municipality_id' => Municipality::getRealId(),
            'zone_no' => 99,
            'barangay_id' => 3,
            'purok_no' => 99,
            'street' => 'NONE'
        ]);
    }


    public function testImportUpdateSuccess()
    {
        $model = new BulkImportHouseholdForm($this->data());
        expect_that($model->save());


        $model = new BulkImportHouseholdForm($this->data());
        $model->file_token = 'household-test-update-OxFBeC2Dzw1624513904-household-test-update';
        expect_that($model->save());

        $this->tester->seeRecord('app\models\Household', [
            'no' => 729760,
            'longitude' => '100',
            'latitude' => '100',
            'altitude' => '100',
        ]);

        $this->tester->seeRecord('app\models\Household', [
            'no' => 729800,
            'longitude' => '200',
            'latitude' => '200',
            'altitude' => '200',
        ]);

        $this->tester->seeRecord('app\models\Household', [
            'no' => 729763,
            'longitude' => '300',
            'latitude' => '300',
            'altitude' => '300',
        ]);
    }

    public function testInvalidExtension()
    {
        $model = new BulkImportHouseholdForm([
            'file_token' => 'default-6ccb4a66-0ca3-46c7-88dd-default',
            'scenario' => 'contentValidation'
        ]);
        expect_not($model->save());
        expect($model->errors)->hasKey('file_token');
    }

    public function testInvalidContentFormat()
    {
        $model = new BulkImportHouseholdForm([
            'file_token' => 'invalid-household-OxFBeC2Dzw1624513904-invalid-household',
            'scenario' => 'contentValidation'
        ]);
        expect_not($model->save());
        expect($model->errors)->hasKey('file_token');
    }
}