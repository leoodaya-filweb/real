<?php

namespace tests\unit\models\form\transaction;

use app\models\Transaction;
use app\models\form\transaction\ObligationRequestForm;

class ObligationRequestFormTest extends \Codeception\Test\Unit
{
    public function testValid()
    {
        $model = new ObligationRequestForm(['transaction_id' => 1]);

        expect_that($model->save());

        $transaction = $this->tester->grabRecord('app\models\Transaction', [
            'id' => 1,
            'obligation_request_status' => Transaction::DOCUMENT_CLERK_CREATED
        ]);

        expect_that($transaction);
        expect_that($transaction->obligation_request);
    }

    public function testTransactionIdInvalid()
    {
        $model = new ObligationRequestForm(['transaction_id' => 'invalid']);
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');
    }

    public function testTransactionIdNotExisting()
    {
        $model = new ObligationRequestForm(['transaction_id' => 99999999]);
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');
    }
}