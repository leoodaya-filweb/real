<?php

use app\helpers\Html;
?>

<p class="lead text-success font-weight-bold">To Approved</p>
<ul>
    <?= Html::if($transaction->isMedical, Html::tag('li', 'Approve ' . Html::a('Whitecard ', $transaction->viewUrlWhiteCard) . Html::ifElse($transaction->isWhitecardApproved, $checked, $xmark))) ?>
    <li>Approve <?= Html::a('General Intake Sheet', $transaction->viewUrlGeneralIntakeSheet) ?> <?= Html::ifElse($transaction->isGisApproved, $checked, $xmark) ?></li>
    <li>Approve <?= Html::a('Obligation Request Form', $transaction->viewUrlObligationRequest) ?> <?= Html::ifElse($transaction->isOrfApproved, $checked, $xmark) ?></li>
    <li>Approve <?= Html::a('Petty Cash Voucher', $transaction->viewUrlPettyCashVoucher) ?> <?= Html::ifElse($transaction->isPcvApproved, $checked, $xmark) ?></li>
    <li>Click the <b>approve</b> button</li>
    <li>Add remarks</li>
    <li>Save</li>
</ul>
<!-- <em>The <b>approved</b> button will only visible after approving the (<?= Html::ifElse($transaction->isMedical, 4, 3) ?>) documents.</em> -->

<hr>
<p class="lead text-danger mt-5 font-weight-bold">To Decline</p>
<ul>
    <li>Click the <b>decline</b> button</li>
    <li>Add remarks</li>
    <li>Save</li>
</ul>