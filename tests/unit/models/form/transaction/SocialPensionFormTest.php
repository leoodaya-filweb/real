<?php

namespace tests\unit\models\form\transaction;

use app\models\Transaction;
use app\models\form\transaction\SocialPensionForm;

class SocialPensionFormTest extends \Codeception\Test\Unit
{
    public function data()
    {
        return [
            'member_id' => 1,
            'files' => json_encode(['test'])
        ];
    }

    public function testValid()
    {
        $model = new SocialPensionForm($this->data());
        expect_that($model->save());

        $this->tester->seeRecord('app\models\Transaction', [
            'member_id' => 1,
            'transaction_type' => Transaction::SOCIAL_PENSION,
        ]);
    }

    public function testMemberIdInvalid()
    {
        $model = new SocialPensionForm(['member_id' => 'invalid']);
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testMemberIdNotExisting()
    {
        $model = new SocialPensionForm(['member_id' => 99999999]);
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }

    public function testMemberIdRequired()
    {
        $model = new SocialPensionForm(['member_id' => '']);
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }
    
    public function testMemberAlreadySocialPensioner()
    {
        $model = new SocialPensionForm(['member_id' => 8]);
        expect_not($model->save());
        expect($model->errors)->hasKey('member_id');
    }
}