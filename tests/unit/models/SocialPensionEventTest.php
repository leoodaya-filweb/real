<?php

namespace tests\unit\models;

use app\models\SocialPensionEvent;
use app\models\EventCategory;
use yii\db\Expression;

class SocialPensionEventTest extends \Codeception\Test\Unit
{
    protected function data($replace=[])
    {
        return array_replace([
            'scenario' => SocialPensionEvent::SCENARIO_SOCIAL_PENSION,
            'name' => 'Name',
            'description' => 'Description',
            'beneficiaries' => json_encode([]),
            'status' => SocialPensionEvent::ONGOING,
            'amount' => 100,
            'type' => SocialPensionEvent::ASSISTANCE,
            'assistance_type' => SocialPensionEvent::CASH,
            'photo' => '',
            'record_status' => SocialPensionEvent::RECORD_ACTIVE,
            'date_from' => date('Y-m-d H:i:s'),
            'category_type' => SocialPensionEvent::SOCIAL_PENSION_CATEGORY,
            'date_to' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+ 1month')),
            'no_of_pensioner' => 100,
            'social_pension_fund' => SocialPensionEvent::LOCAL_FUND,
        ], $replace);
    }

    public function testCreateSuccess()
    {
        $model = new SocialPensionEvent($this->data());
        expect_that($model->save());
    }


    public function testInvalidStatus()
    {
        $model = new SocialPensionEvent($this->data());
        $model->status = 19999;
        expect_not($model->save());
        expect($model)->hasKey('status');
    }

    public function testNoInactiveDataAccessRoleUserCreateInactiveData()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'no_inactive_data_access_role_user'
        ]));

        $data = $this->data(['record_status' => SocialPensionEvent::RECORD_INACTIVE]);

        $model = new SocialPensionEvent($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');

        \Yii::$app->user->logout();
    }

    public function testCreateNoData()
    {
        $model = new SocialPensionEvent([
            'scenario' => SocialPensionEvent::SCENARIO_DEFAULT
        ]);
        
        expect_not($model->save());
    }

    public function testCreateInvalidRecordStatus()
    {
        $data = $this->data(['record_status' => 3]);

        $model = new SocialPensionEvent($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testUpdateSuccess()
    {
        $model = $this->tester->grabRecord('app\models\SocialPensionEvent', [
            'record_status' => SocialPensionEvent::RECORD_ACTIVE
        ]);
        $model->record_status = 1;
        expect_that($model->save());
    }

    public function testDeleteSuccess()
    {
        $model = $this->tester->grabRecord('app\models\SocialPensionEvent', [
            'category_type' => SocialPensionEvent::SOCIAL_PENSION_CATEGORY
        ]);
        expect_that($model->delete());
    }

    public function testActivateData()
    {
        $model = $this->tester->grabRecord('app\models\SocialPensionEvent', [
            'category_type' => SocialPensionEvent::SOCIAL_PENSION_CATEGORY
        ]);
        expect_that($model);

        $model->activate();
        expect_that($model->save());
    }

    public function testGuestDeactivateData()
    {
        $model = $this->tester->grabRecord('app\models\SocialPensionEvent', [
            'record_status' => SocialPensionEvent::RECORD_ACTIVE
        ]);
        expect_that($model);

        $model->inactivate();
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testAmountInvalid()
    {
        $model = new SocialPensionEvent($this->data(['amount' => 'invalid']));
        expect_not($model->save());
        expect($model->errors)->hasKey('amount');
    }

    public function testBenificiaryTypeInvalid()
    {
        $model = new SocialPensionEvent($this->data(['type' => 'invalid']));
        expect_not($model->save());
        expect($model->errors)->hasKey('type');
    }

    public function testBenificiaryTypeNotExisting()
    {
        $model = new SocialPensionEvent($this->data(['type' => 376]));
        expect_not($model->save());
        expect($model->errors)->hasKey('type');
    }

    public function testDaterangeInvalid()
    {
        $model = new SocialPensionEvent($this->data());
        $model->date_from = '04/22/2022 4:58 PM';
        $model->date_to = '03/22/2022 4:58 PM';
        expect_not($model->save());
        expect($model->errors)->hasKey('date_from');
        expect($model->errors)->hasKey('date_to');
    }

    public function testAttendeesTypeInvalid()
    {
        $model = new SocialPensionEvent($this->data());
        $model->category_type = 379;
        expect_not($model->save());
        expect($model->errors)->hasKey('category_type');
    }

    public function testSocialPensionFundInvalid()
    {
        $model = new SocialPensionEvent($this->data());
        $model->social_pension_fund = 'invalid';
        expect_not($model->save());
        expect($model->errors)->hasKey('social_pension_fund');

        $model = new SocialPensionEvent($this->data());
        $model->social_pension_fund = 9999;
        expect_not($model->save());
        expect($model->errors)->hasKey('social_pension_fund');
    }

    public function testNoOfPensionersInvalid()
    {
        $model = new SocialPensionEvent($this->data());
        $model->no_of_pensioner = 'invalid';
        expect_not($model->save());
        expect($model->errors)->hasKey('no_of_pensioner');

        $model = new SocialPensionEvent($this->data());
        $model->no_of_pensioner = 0;
        expect_not($model->save());
        expect($model->errors)->hasKey('no_of_pensioner');
    }
}