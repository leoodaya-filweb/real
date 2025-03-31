<?php

use app\helpers\Html;
use app\models\Transaction;
?>

<p class="lead text-success font-weight-bold">To Complete</p>
<ul>
    <li>Create <?= Html::a('General Intake Sheet', "{$transaction->viewUrlGeneralIntakeSheet}&create_document=gis") ?> <?= Html::ifElse($transaction->general_intake_sheet_status == Transaction::DOCUMENT_CLERK_CREATED, $checked, $xmark) ?></li>
    <li>Create <?= Html::a('Obligation Request Form', "{$transaction->viewUrlObligationRequest}&create_document=orf") ?> <?= Html::ifElse($transaction->obligation_request_status == Transaction::DOCUMENT_CLERK_CREATED, $checked, $xmark) ?></li>
    <li>Create <?= Html::a('Petty Cash Voucher', "{$transaction->viewUrlPettyCashVoucher}&create_document=pcv") ?> <?= Html::ifElse($transaction->petty_cash_voucher_status == Transaction::DOCUMENT_CLERK_CREATED, $checked, $xmark) ?></li>
    <li>Click the <b>"Complete"</b> button</li>
    <li>Confirm action</li>
</ul>
<!-- <em>The <b>approved</b> button will only visible after creating the the three (3) documents.</em> -->

<hr>
<p class="lead text-danger mt-5 font-weight-bold">To Decline</p>
<ul>
    <li>Click the <b>"Decline"</b> button</li>
    <li>Add remarks</li>
    <li>Save</li>
</ul>