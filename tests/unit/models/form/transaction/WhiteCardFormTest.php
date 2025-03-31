<?php

namespace tests\unit\models\form\transaction;

use app\models\Transaction;
use app\models\form\transaction\WhiteCardForm;

class WhiteCardFormTest extends \Codeception\Test\Unit
{
    public function testValid()
    {
        $model = new WhiteCardForm(['transaction_id' => 1]);
        $model->white_card = 'white_card';
        expect_that($model->save());

        $this->tester->seeRecord('app\models\Transaction', [
            'id' => 1,
            'white_card' => 'white_card',
            'white_card_status' => Transaction::DOCUMENT_CLERK_CREATED
        ]);
    }

    public function testTransactionIdInvalid()
    {
        $model = new WhiteCardForm(['transaction_id' => 'invalid']);
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');
    }

    public function testTransactionIdNotExisting()
    {
        $model = new WhiteCardForm(['transaction_id' => 99999]);
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');
    }

    public function testWhiteCardRequired()
    {
        $model = new WhiteCardForm(['transaction_id' => 1]);
        $model->white_card = '';
        expect_not($model->save());
        expect($model->errors)->hasKey('white_card');
    }
}