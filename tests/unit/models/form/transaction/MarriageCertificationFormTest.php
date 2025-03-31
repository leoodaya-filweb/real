<?php

namespace tests\unit\models\form\transaction;

use app\models\Transaction;
use app\models\form\transaction\MarriageCertificationForm;


class MarriageCertificationFormTest extends \Codeception\Test\Unit
{
    public function testValid()
    {
        $model = new MarriageCertificationForm(['member_id' => 1]);

        expect_that($model->save());

        $this->tester->seeRecord('app\models\Transaction', [
            'member_id' => 1,
            'status' => Transaction::COMPLETED
        ]);
    }

    public function testMemberIdInvalid()
    {
        $model = new MarriageCertificationForm(['member_id' => 'invalid']);
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testMemberIdNotExisting()
    {
        $model = new MarriageCertificationForm(['member_id' => 99999999]);
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testContentRequired()
    {
        $model = new MarriageCertificationForm(['content' => '']);
        expect_not($model->save());
        expect($model->errors)->hasKey('content');
    }

    public function testTransactionTypeInvalid()
    {
        $model = new MarriageCertificationForm(['transaction_type' => 'invalid']);
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_type');

        $model = new MarriageCertificationForm(['transaction_type' => Transaction::EMERGENCY_WELFARE_PROGRAM]);
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_type');
    }
}