<?php

namespace tests\unit\models;

use app\models\PostActivityReport;

class PostActivityReportTest extends \Codeception\Test\Unit
{
    protected function data($replace=[])
    {
        return array_replace([
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
            'record_status' => PostActivityReport::RECORD_ACTIVE
        ], $replace);
    }

    public function testCreateSuccess()
    {
        $model = new PostActivityReport($this->data());
        expect_that($model->save());
    }

    public function testNoInactiveDataAccessRoleUserCreateInactiveData()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'no_inactive_data_access_role_user'
        ]));

        $data = $this->data(['record_status' => PostActivityReport::RECORD_INACTIVE]);

        $model = new PostActivityReport($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');

        \Yii::$app->user->logout();
    }

    public function testCreateNoData()
    {
        $model = new PostActivityReport();
        expect_not($model->save());
    }

    public function testCreateInvalidRecordStatus()
    {
        $data = $this->data(['record_status' => 3]);

        $model = new PostActivityReport($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testUpdateSuccess()
    {
        $model = $this->tester->grabRecord('app\models\PostActivityReport', [
            'record_status' => PostActivityReport::RECORD_ACTIVE
        ]);
        $model->record_status = 1;
        expect_that($model->save());
    }

    public function testDeleteSuccess()
    {
        $model = $this->tester->grabRecord('app\models\PostActivityReport', [
            'record_status' => PostActivityReport::RECORD_ACTIVE
        ]);
        expect_that($model->delete());
    }

    public function testActivateData()
    {
        $model = $this->tester->grabRecord('app\models\PostActivityReport', [
            'record_status' => PostActivityReport::RECORD_INACTIVE
        ]);
        expect_that($model);

        $model->activate();
        expect_that($model->save());
    }

    public function testGuestDeactivateData()
    {
        $model = $this->tester->grabRecord('app\models\PostActivityReport', [
            'record_status' => PostActivityReport::RECORD_ACTIVE
        ]);
        expect_that($model);

        $model->inactivate();
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }
}