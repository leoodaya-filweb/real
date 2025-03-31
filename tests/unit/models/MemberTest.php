<?php

namespace tests\unit\models;

use app\models\CivilStatus;
use app\models\EducationalAttainment;
use app\models\Member;
use app\models\Relation;
use app\models\Sex;

class MemberTest extends \Codeception\Test\Unit
{
    protected function data($replace=[])
    {
        return array_replace([
            'qr_id' => 'qr-code-123',
            'household_id' => 1,
            'last_name' => 'Last Name',
            'middle_name' => 'Middle Name',
            'first_name' => 'First Name',
            'sex' => Sex::male(),
            'birth_date' => '1979-06-16',
            'birth_place' => 'GMA Cavite',
            'civil_status' => CivilStatus::single(),
            'email' => 'testemail@gmail.com',
            'contact_no' => '09384076956',
            'telephone_no' => '1234-123-1234',
            'relation' => Relation::head(),
            'educational_attainment' => (EducationalAttainment::find()->one())->value,
            'head' => Member::FAMILY_HEAD_YES,
            'scenario' => 'family-head'
        ], $replace);
    }

    public function testCreateSuccess()
    {
        $model = new Member($this->data());
        $model->save();
    }

    public function testNoInactiveDataAccessRoleUserCreateInactiveData()
    {
        \Yii::$app->user->login($this->tester->grabRecord('app\models\User', [
            'username' => 'no_inactive_data_access_role_user'
        ]));

        $data = $this->data(['record_status' => Member::RECORD_INACTIVE]);

        $model = new Member($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');

        \Yii::$app->user->logout();
    }

    public function testCreateNoData()
    {
        $model = new Member();
        expect_not($model->save());
    }

    public function testCreateInvalidRecordStatus()
    {
        $data = $this->data(['record_status' => 3]);

        $model = new Member($data);
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testUpdateSuccess()
    {
        $model = $this->tester->grabRecord('app\models\Member', [
            'record_status' => Member::RECORD_ACTIVE
        ]);
        $model->record_status = 1;
        expect_that($model->save());
    }

    public function testDeleteSuccess()
    {
        $model = $this->tester->grabRecord('app\models\Member', [
            'qr_id' => 'test-4'
        ]);
        expect_that($model->delete());
    }

    public function testActivateData()
    {
        $model = $this->tester->grabRecord('app\models\Member', [
            'record_status' => Member::RECORD_INACTIVE
        ]);
        expect_that($model);

        $model->activate();
        expect_that($model->save());
    }

    public function testGuestDeactivateData()
    {
        $model = $this->tester->grabRecord('app\models\Member', [
            'record_status' => Member::RECORD_ACTIVE
        ]);
        expect_that($model);

        $model->inactivate();
        expect_not($model->save());
        expect($model->errors)->hasKey('record_status');
    }

    public function testSexInvalid()
    {
        $model = new Member($this->data());
        $model->sex = 'invalid';

        expect_not($model->save());
        expect($model->errors)->hasKey('sex');
    }

    public function testSexNotExisting()
    {
        $model = new Member($this->data());
        $model->sex = 99999999;
        expect_not($model->save());
        expect($model->errors)->hasKey('sex');
    }

    public function testHouseholdNoInvalid()
    {
        $model = new Member($this->data());
        $model->household_id = 'invalid';
        expect_not($model->save());
        expect($model->errors)->hasKey('household_id');
    }

    public function testHouseholdNoNotExisting()
    {
        $model = new Member($this->data());
        $model->household_id = 9999999;
        expect_not($model->save());
        expect($model->errors)->hasKey('household_id');
    }

    public function testEmailInvalid()
    {
        $model = new Member($this->data());
        $model->email = '9999999';
        expect_not($model->save());
        expect($model->errors)->hasKey('email');
    }

    public function testContactNoInvalid()
    {
        $model = new Member($this->data());
        $model->contact_no = '9999999';
        expect_not($model->save());
        expect($model->errors)->hasKey('contact_no');
    }

    public function testBirthPlaceRequiredInPersonalInformationScene()
    {
        $model = new Member($this->data());
        $model->scenario = 'family-composition';
        $model->birth_place = null;
        expect_not($model->save());
        expect($model->errors)->hasKey('birth_place');
    }

    public function testCivilStatusInvalid()
    {
        $model = new Member($this->data());
        $model->civil_status = 'invalid';

        expect_not($model->save());
        expect($model->errors)->hasKey('civil_status');
    }

    public function testCivilStatusNotExisting()
    {
        $model = new Member($this->data());
        $model->civil_status = 99999999;
        expect_not($model->save());
        expect($model->errors)->hasKey('civil_status');
    }

    public function testHeadInvalid()
    {
        $model = new Member();
        $model->head = 'invalid';

        expect_not($model->validate('head'));
        expect($model->errors)->hasKey('head');
    }

    public function testHeadNotExisting()
    {
        $model = new Member($this->data());
        $model->head = 99999999;
        expect_not($model->validate('head'));
        expect($model->errors)->hasKey('head');
    }

    public function testRequiredRelationOnFamilyComposition()
    {
        $model = new Member($this->data(['scenario' => 'family-composition']));
        $model->relation = '';
        expect_not($model->validate('relation'));
        expect($model->errors)->hasKey('relation');
    }

    public function testBirthDateIsGreaterThanDateToday()
    {
        $model = new Member($this->data(['birth_date' => '2044-06-16']));
        expect_not($model->validate('birth_date'));
        expect($model->errors)->hasKey('birth_date');
    }

    public function testLivingStatusInvalid()
    {
        $model = new Member($this->data());
        $model->living_status = 'invalid';

        expect_not($model->validate('living_status'));
        expect($model->errors)->hasKey('living_status');


        $model = new Member($this->data());
        $model->living_status = 456789;

        expect_not($model->validate('living_status'));
        expect($model->errors)->hasKey('living_status');
    }


    public function testPwdInvalid()
    {
        $model = new Member($this->data());
        $model->pwd = 'invalid';

        expect_not($model->validate('pwd'));
        expect($model->errors)->hasKey('pwd');


        $model = new Member($this->data());
        $model->pwd = 456789;

        expect_not($model->validate('pwd'));
        expect($model->errors)->hasKey('pwd');
    }

    public function testPwdTypeInvalid()
    {
        $model = new Member($this->data());
        $model->pwd_type = 'invalid';

        expect_not($model->validate('pwd_type'));
        expect($model->errors)->hasKey('pwd_type');


        $model = new Member($this->data());
        $model->pwd_type = 456789;

        expect_not($model->validate('pwd_type'));
        expect($model->errors)->hasKey('pwd_type');
    }

    public function testSoloParentInvalid()
    {
        $model = new Member($this->data());
        $model->solo_parent = 'invalid';

        expect_not($model->validate('solo_parent'));
        expect($model->errors)->hasKey('solo_parent');


        $model = new Member($this->data());
        $model->solo_parent = 456789;

        expect_not($model->validate('solo_parent'));
        expect($model->errors)->hasKey('solo_parent');
    }


    public function testSoloMemberInvalid()
    {
        $model = new Member($this->data());
        $model->solo_member = 'invalid';

        expect_not($model->validate('solo_member'));
        expect($model->errors)->hasKey('solo_member');


        $model = new Member($this->data());
        $model->solo_member = 456789;

        expect_not($model->validate('solo_member'));
        expect($model->errors)->hasKey('solo_member');
    }

    public function testSocialPensionStatusInvalid()
    {
        $model = new Member($this->data());
        $model->social_pension_status = 'invalid';

        expect_not($model->validate('social_pension_status'));
        expect($model->errors)->hasKey('social_pension_status');


        $model = new Member($this->data());
        $model->social_pension_status = 456789;

        expect_not($model->validate('social_pension_status'));
        expect($model->errors)->hasKey('social_pension_status');
    }
}