<?php

namespace tests\unit\models;

use app\models\Medicine;

class MedicineTest extends \Codeception\Test\Unit
{
    protected function data($replace=[])
    {
        return array_replace([
            'transaction_id' => 1,
            'name' => 'Name',
            'price' => 100,
            'quantity' => 100,
            'unit' => 'litres',
            'record_status' => Medicine::RECORD_ACTIVE
        ], $replace);
    }

    public function testCreateSuccess()
    {
        $model = new Medicine($this->data());
        expect_that($model->save());
    }

    public function testNoInactiveDataAccessRoleUserCreateInactiveData()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'no_inactive_data_access_role_user'
        ]));

        $data = $this->data(['record_status' => Medicine::RECORD_INACTIVE]);

        $model = new Medicine($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');

        \Yii::$app->user->logout();
    }

    public function testCreateNoData()
    {
        $model = new Medicine();
        expect_not($model->save());
    }

    public function testCreateInvalidRecordStatus()
    {
        $data = $this->data(['record_status' => 3]);

        $model = new Medicine($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testUpdateSuccess()
    {
        $model = $this->tester->grabRecord('app\models\Medicine', [
            'record_status' => Medicine::RECORD_ACTIVE
        ]);
        $model->record_status = 1;
        expect_that($model->save());
    }

    public function testDeleteSuccess()
    {
        $model = $this->tester->grabRecord('app\models\Medicine', [
            'record_status' => Medicine::RECORD_ACTIVE
        ]);
        expect_that($model->delete());
    }

    public function testActivateData()
    {
        $model = $this->tester->grabRecord('app\models\Medicine', [
            'record_status' => Medicine::RECORD_INACTIVE
        ]);
        expect_that($model);

        $model->activate();
        expect_that($model->save());
    }

    public function testGuestDeactivateData()
    {
        $model = $this->tester->grabRecord('app\models\Medicine', [
            'record_status' => Medicine::RECORD_ACTIVE
        ]);
        expect_that($model);

        $model->inactivate();
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testInvalidTransactionId()
    {
        $data = $this->data(['transaction_id' => 3456789]);

        $model = new Medicine($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');
    }
}