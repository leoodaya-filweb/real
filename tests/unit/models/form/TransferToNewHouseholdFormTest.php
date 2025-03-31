<?php

namespace tests\unit\models\form;

use app\helpers\App;
use app\models\Barangay;
use app\models\HouseholdMember;
use app\models\Member;
use app\models\form\TransferToNewHouseholdForm;

class TransferToNewHouseholdFormTest extends \Codeception\Test\Unit
{
    private function data($replace=[])
    {
        return array_replace([
            'head' => Member::FAMILY_HEAD_YES,
            'member_id' => 1,
            'barangay_id' => Barangay::POBLACION_1,
            'street' => 'None',
            'zone_no' => 1,
            'blk_no' => '9b',
            'lot_no' => 1,
            'household_no' => 9999,
            'transfer_date' => '05/21/2015 8:56 AM',
            'purok_no' => '1',
            'longitude' => '1',
            'latitude' => '1',
            'altitude' => '1',
            'sitio' => 'sitio',
            'landmark' => 'landmark',
            'files' => json_encode(['file1', 'file2'])
        ], $replace);
    }

    public function testValid()
    {
        $model = new TransferToNewHouseholdForm($this->data());
        $model->household_no = 9999;

        $data = $model->save();

        expect_that($data);

        $this->tester->seeRecord('app\models\Household', [
            'barangay_id' => Barangay::POBLACION_1,
            'street' => 'None',
            'zone_no' => 1,
            'blk_no' => '9b',
            'lot_no' => 1,
            'no' => 9999,
            'purok_no' => 1,
            'longitude' => '1',
            'latitude' => '1',
            'altitude' => '1',
            'sitio' => 'sitio',
            'landmark' => 'landmark',
            'files' => json_encode(['file1', 'file2'])
        ]);

        $this->tester->seeRecord('app\models\Member', [
            'household_id' => $data['household']->id,
            'head' => Member::FAMILY_HEAD_YES
        ]);

        $this->tester->seeRecord('app\models\HouseholdMember', [
            'household_id' => 1,
            'member_id' => $data['member']->id,
            'status' => HouseholdMember::REMOVED
        ]);
    }

    public function testMemberIdInvalid()
    {
        $model = new TransferToNewHouseholdForm($this->data(['member_id' => 'invalid']));
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testMemberIdNotExisting()
    {
        $model = new TransferToNewHouseholdForm($this->data(['member_id' => 9999]));
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testBarangayIdInvalid()
    {
        $model = new TransferToNewHouseholdForm($this->data(['barangay_id' => 'invalid']));
        expect_not($model->save());
        expect($model->errors)->hasKey('barangay_id');
    }

    public function testBarangayIdNotExisting()
    {
        $model = new TransferToNewHouseholdForm($this->data(['barangay_id' => 9999]));
        expect_not($model->save());
        expect($model->errors)->hasKey('barangay_id');
    }

    public function testHeadInvalid()
    {
        $model = new TransferToNewHouseholdForm($this->data());
        $model->head = 'invalid';
        expect_not($model->save());
        expect($model->errors)->hasKey('head');
    }

    public function testHeadNotExisting()
    {
        $model = new TransferToNewHouseholdForm($this->data());
        $model->head = 9999;
        expect_not($model->save());
        expect($model->errors)->hasKey('head');
    }

    public function testHouseholdNoExisting()
    {
        $model = new TransferToNewHouseholdForm($this->data());
        $model->household_no = 111111;
        expect_not($model->save());
        expect($model->errors)->hasKey('household_no');
    }

    public function testTransferDateGreaterThanToday()
    {
        $model = new TransferToNewHouseholdForm($this->data());
        $model->transfer_date = '05/21/3025 8:56 AM';
        expect_not($model->save());
        expect($model->errors)->hasKey('transfer_date');
    }
}