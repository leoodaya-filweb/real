<?php

namespace tests\unit\models;

use app\models\CivilStatus;
use app\models\Sex;
use app\models\SocialPensioner;
use yii\db\Expression;

class SocialPensionerTest extends \Codeception\Test\Unit
{
    protected function data($replace=[])
    {
        return array_replace([
            'qr_id' => 'qr-code-11',
            'last_name' => 'Last Name 11',
            'middle_name' => 'Middle Name',
            'first_name' => 'First Name',
            'name_suffix' => 'Jr',
            'sex' => Sex::male(),
            'age' => 43,
            'birth_date' => '1979-06-16',
            'birth_place' => 'GMA Cavite',
            'civil_status' => CivilStatus::single(),
            'email' => 'testemail@gmail.com',
            'contact_no' => '09124449532',
            'house_no' => '9B',
            'street' => 'NONE',
            'barangay' => 'Poblacion I (Barangay 1)',
            'sitio' => 'Sitio',
            'purok' => '6',
            'educational_attainment' => 'No Grade',
            'occupation' => 'Occupation',
            'income' => 20000,
            'source_of_income' => 'Salary',
            'date_registered' => '2022-08-31',
            'photo' => '',
            'documents' => '',
            // 'pwd_score' => 0.2,
            // 'senior_score' => 0.2,
            // 'solo_parent_score' => 0.2,
            // 'solo_member_score' => 0.25,
            'is_pwd' => true,
            'is_senior' => true,
            'is_solo_parent' => true,
            'is_solo_member' => true,
            'record_status' => SocialPensioner::RECORD_ACTIVE,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => new Expression('UTC_TIMESTAMP'),
            'updated_at' => new Expression('UTC_TIMESTAMP'),
        ], $replace);
    }

    public function testCreateSuccess()
    {
        $model = new SocialPensioner($this->data());
        expect_that($model->save());
        expect($model->pwd_score)->equals(0.2);
        expect($model->senior_score)->equals(0.2);
        expect($model->solo_parent_score)->equals(0.2);
        expect($model->solo_member_score)->equals(0.25);
    }

    public function testNoInactiveDataAccessRoleUserCreateInactiveData()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'no_inactive_data_access_role_user'
        ]));

        $data = $this->data(['record_status' => SocialPensioner::RECORD_INACTIVE]);

        $model = new SocialPensioner($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');

        \Yii::$app->user->logout();
    }

    public function testCreateNoData()
    {
        $model = new SocialPensioner();
        expect_not($model->save());
    }

    public function testCreateInvalidRecordStatus()
    {
        $data = $this->data(['record_status' => 3]);

        $model = new SocialPensioner($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testUpdateSuccess()
    {
        $model = $this->tester->grabRecord('app\models\SocialPensioner', [
            'record_status' => SocialPensioner::RECORD_ACTIVE
        ]);
        $model->record_status = 1;
        expect_that($model->save());
    }

    public function testDeleteSuccess()
    {
        $model = $this->tester->grabRecord('app\models\SocialPensioner', [
            'record_status' => SocialPensioner::RECORD_ACTIVE
        ]);
        expect_that($model->delete());
    }

    public function testActivateData()
    {
        $model = $this->tester->grabRecord('app\models\SocialPensioner', [
            'record_status' => SocialPensioner::RECORD_INACTIVE
        ]);
        expect_that($model);

        $model->activate();
        expect_that($model->save());
    }

    public function testGuestDeactivateData()
    {
        $model = $this->tester->grabRecord('app\models\SocialPensioner', [
            'record_status' => SocialPensioner::RECORD_ACTIVE
        ]);
        expect_that($model);

        $model->inactivate();
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testSexInvalid()
    {
        $model = new SocialPensioner($this->data());
        $model->sex = 'invalid';

        expect_not($model->save());
        expect($model->errors)->hasKey('sex');
    }

    public function testSexNotExisting()
    {
        $model = new SocialPensioner($this->data());
        $model->sex = 99999999;
        expect_not($model->save());
        expect($model->errors)->hasKey('sex');
    }

    public function testCivilStatusInvalid()
    {
        $model = new SocialPensioner($this->data());
        $model->civil_status = 'invalid';

        expect_not($model->save());
        expect($model->errors)->hasKey('civil_status');
    }

    public function testCivilStatusNotExisting()
    {
        $model = new SocialPensioner($this->data());
        $model->civil_status = 99999999;
        expect_not($model->save());
        expect($model->errors)->hasKey('civil_status');
    }

    public function testExistingPensioner()
    {
        $model = new SocialPensioner($this->data());
        $model->first_name = 'First Name';
        $model->last_name = 'Last Name';
        $model->middle_name = 'Middle Name';
        $model->birth_date = '1979-06-16';

        expect_not($model->save());
        expect($model->errors)->hasKey('first_name');
        expect($model->errors)->hasKey('last_name');
        expect($model->errors)->hasKey('middle_name');
        expect($model->errors)->hasKey('birth_date');
    }

    public function testExistingPensionerQrId()
    {
        $model = new SocialPensioner($this->data());
        $model->qr_id = 'qr-code-1';

        expect_not($model->save());
        expect($model->errors)->hasKey('qr_id');
    }
}