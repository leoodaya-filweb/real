<?php

namespace tests\unit\models;

use app\models\UnPlannedAttendeesEvent;
use app\models\EventCategory;
use yii\db\Expression;

class UnPlannedAttendeesEventTest extends \Codeception\Test\Unit
{
    protected function data($replace=[])
    {
        return array_replace([
            'scenario' => UnPlannedAttendeesEvent::SCENARIO_UNPLANNED,
            'name' => 'Name',
            'description' => 'Description',
            'beneficiaries' => json_encode([]),
            'status' => UnPlannedAttendeesEvent::ONGOING,
            'amount' => 100,
            'type' => UnPlannedAttendeesEvent::ASSISTANCE,
            'assistance_type' => UnPlannedAttendeesEvent::CASH,
            'photo' => '',
            'record_status' => UnPlannedAttendeesEvent::RECORD_ACTIVE,
            'date_from' => date('Y-m-d H:i:s'),
            'category_type' => UnPlannedAttendeesEvent::UN_PLANNED_CATEGORY,
            'date_to' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+ 1month')),
            'category_id' => (EventCategory::find()->one())->id,
        ], $replace);
    }

    public function testCreateSuccess()
    {
        $model = new UnPlannedAttendeesEvent($this->data());
        expect_that($model->save());
    }


    public function testInvalidStatus()
    {
        $model = new UnPlannedAttendeesEvent($this->data());
        $model->status = 19999;
        expect_not($model->save());
        expect($model)->hasKey('status');
    }

    public function testNoInactiveDataAccessRoleUserCreateInactiveData()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'no_inactive_data_access_role_user'
        ]));

        $data = $this->data(['record_status' => UnPlannedAttendeesEvent::RECORD_INACTIVE]);

        $model = new UnPlannedAttendeesEvent($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');

        \Yii::$app->user->logout();
    }

    public function testCreateNoData()
    {
        $model = new UnPlannedAttendeesEvent([
            'scenario' => UnPlannedAttendeesEvent::SCENARIO_DEFAULT
        ]);
        
        expect_not($model->save());
    }

    public function testCreateInvalidRecordStatus()
    {
        $data = $this->data(['record_status' => 3]);

        $model = new UnPlannedAttendeesEvent($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testUpdateSuccess()
    {
        $model = $this->tester->grabRecord('app\models\UnPlannedAttendeesEvent', [
            'record_status' => UnPlannedAttendeesEvent::RECORD_ACTIVE
        ]);
        $model->record_status = 1;
        expect_that($model->save());
    }

    public function testDeleteSuccess()
    {
        $model = $this->tester->grabRecord('app\models\UnPlannedAttendeesEvent', [
            'category_type' => UnPlannedAttendeesEvent::UN_PLANNED_CATEGORY
        ]);
        expect_that($model->delete());
    }

    public function testActivateData()
    {
        $model = $this->tester->grabRecord('app\models\UnPlannedAttendeesEvent', [
            'category_type' => UnPlannedAttendeesEvent::UN_PLANNED_CATEGORY
        ]);
        expect_that($model);

        $model->activate();
        expect_that($model->save());
    }

    public function testGuestDeactivateData()
    {
        $model = $this->tester->grabRecord('app\models\UnPlannedAttendeesEvent', [
            'record_status' => UnPlannedAttendeesEvent::RECORD_ACTIVE
        ]);
        expect_that($model);

        $model->inactivate();
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testAmountInvalid()
    {
        $model = new UnPlannedAttendeesEvent($this->data(['amount' => 'invalid']));
        expect_not($model->save());
        expect($model->errors)->hasKey('amount');
    }

    public function testBenificiaryTypeInvalid()
    {
        $model = new UnPlannedAttendeesEvent($this->data(['type' => 'invalid']));
        expect_not($model->save());
        expect($model->errors)->hasKey('type');
    }

    public function testBenificiaryTypeNotExisting()
    {
        $model = new UnPlannedAttendeesEvent($this->data(['type' => 376]));
        expect_not($model->save());
        expect($model->errors)->hasKey('type');
    }

    public function testDaterangeInvalid()
    {
        $model = new UnPlannedAttendeesEvent($this->data());
        $model->date_from = '04/22/2022 4:58 PM';
        $model->date_to = '03/22/2022 4:58 PM';
        expect_not($model->save());
        expect($model->errors)->hasKey('date_from');
        expect($model->errors)->hasKey('date_to');
    }

    public function testCategoryIdInvalid()
    {
        $model = new UnPlannedAttendeesEvent($this->data());
        $model->category_id = 379;
        expect_not($model->save());
        expect($model->errors)->hasKey('category_id');
    }

    public function testAttendeesTypeInvalid()
    {
        $model = new UnPlannedAttendeesEvent($this->data());
        $model->category_type = 379;
        expect_not($model->save());
        expect($model->errors)->hasKey('category_type');
    }
}