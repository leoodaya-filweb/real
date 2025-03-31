<?php

namespace tests\unit\models;

use app\models\Region;
use yii\db\Expression;
use app\models\Barangay;
use app\models\Province;
use app\models\Household;
use app\models\Municipality;

class HouseholdTest extends \Codeception\Test\Unit
{
    protected function data($replace=[])
    {
        return array_replace([
            'scenario' => 'manual',
            'no' => '222222',
            'transfer_date' => '2015-02-16 13:35:36.342',
            'longitude' => '122.6030581489',
            'latitude' => '14.6696698109',
            'altitude' => '65.9',
            'region_id' => Region::getRegion4aId(),
            'province_id' => Province::getCalabarzonId(),
            'municipality_id' => Municipality::getRealId(),
            'barangay_id' => Barangay::getPoblacion1Id(),
            'zone_no' => 99,
            'purok_no' => '6',
            'blk_no' => '9B',
            'lot_no' => 2,
            'street' => 'NONE',
            'record_status' => Household::RECORD_ACTIVE,
            'created_at' => new Expression('UTC_TIMESTAMP'),
            'updated_at' => new Expression('UTC_TIMESTAMP'),
        ], $replace);
    }

    public function testCreateSuccess()
    {
        $model = new Household($this->data());
        expect_that($model->save());
    }

    public function testNoInactiveDataAccessRoleUserCreateInactiveData()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'no_inactive_data_access_role_user'
        ]));


        $model = new Household($this->data());
        $model->record_status = Household::RECORD_INACTIVE;

        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');

        \Yii::$app->user->logout();
    }

    public function testCreateNoData()
    {
        $model = new Household();
        expect_not($model->save());
    }

    public function testCreateInvalidRecordStatus()
    {
        $data = $this->data();

        $model = new Household($data);
        $model->record_status = Household::RECORD_INACTIVE;
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testUpdateSuccess()
    {
        $model = $this->tester->grabRecord('app\models\Household', [
            'record_status' => Household::RECORD_ACTIVE
        ]);
        $model->record_status = 1;
        expect_that($model->save());
    }

    public function testDeleteSuccess()
    {
        $model = $this->tester->grabRecord('app\models\Household', [
            'no' => '123456789'
        ]);
        expect_that($model->delete());
    }

    public function testActivateData()
    {
        $model = $this->tester->grabRecord('app\models\Household', [
            'record_status' => Household::RECORD_INACTIVE
        ]);
        expect_that($model);

        $model->activate();
        expect_that($model->save());
    }

    public function testGuestDeactivateData()
    {
        $model = $this->tester->grabRecord('app\models\Household', [
            'record_status' => Household::RECORD_ACTIVE
        ]);
        expect_that($model);

        $model->inactivate();
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testNoExceedLimit()
    {
        $model = new Household($this->data(['no' => 99999999999999999999]));
        expect_not($model->save());
        expect($model->errors)->hasKey('no');
    }
}