<?php

use app\helpers\Html;
?>

<p class="lead text-success font-weight-bold">To Approve</p>
<ul>
    <li>Create <?= Html::a('Intake Sheet', "{$transaction->viewUrlSeniorCitizenIntakeSheet}&create_document=intake-sheet") ?> <?= Html::ifElse($transaction->senior_citizen_intake_sheet, $checked, $xmark) ?></li>
    <li>Click the <b>approve</b> button</li>
    <li>Add remarks</li>
    <li>Save</li>
</ul>
<!-- <em>The <b>approved</b> button will only visible after creating the intake sheet.</em> -->