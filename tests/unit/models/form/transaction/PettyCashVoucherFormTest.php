<?php

namespace tests\unit\models\form\transaction;

use app\models\Transaction;
use app\models\form\transaction\PettyCashVoucherForm;

class PettyCashVoucherFormTest extends \Codeception\Test\Unit
{
    public function testValid()
    {
        $model = new PettyCashVoucherForm(['transaction_id' => 1]);

        expect_that($model->save());

        $transaction = $this->tester->grabRecord('app\models\Transaction', [
            'id' => 1,
            'petty_cash_voucher_status' => Transaction::DOCUMENT_CLERK_CREATED
        ]);

        expect_that($transaction);
        expect_that($transaction->petty_cash_voucher);
    }

    public function testTransactionIdInvalid()
    {
        $model = new PettyCashVoucherForm(['transaction_id' => 'invalid']);
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');
    }

    public function testTransactionIdNotExisting()
    {
        $model = new PettyCashVoucherForm(['transaction_id' => 99999999]);
        expect_not($model->save());
        expect($model->errors)->hasKey('transaction_id');
    }
}