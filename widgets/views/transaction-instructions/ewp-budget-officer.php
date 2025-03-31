<?php

use app\helpers\Html;
?>

<p class="lead text-success font-weight-bold">To Certify</p>
<ul>
    <li>Click the <b>certify</b> button</li>
    <li>Add remarks</li>
    <li>Save</li>
</ul>
<hr>
<em>Make sure to double check the required (<?= Html::ifElse($transaction->isMedical, 4, 3) ?>) documents and the budget.</em>
<ol>
    <?= Html::if($transaction->isMedical, Html::tag('li', Html::a('Whitecard', $transaction->viewUrlWhiteCard))) ?>
    <li><?= Html::a('General Intake Sheet', $transaction->viewUrlGeneralIntakeSheet) ?></li>
    <li><?= Html::a('Obligation Request Form', $transaction->viewUrlObligationRequest) ?></li>
    <li><?= Html::a('Petty Cash Voucher', $transaction->viewUrlPettyCashVoucher) ?></li>
</ol>