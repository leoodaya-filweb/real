<?php

use app\helpers\Html;
?>

<p class="lead text-success font-weight-bold">To Complete</p>
<ul>
    <li>Create <?= Html::a('General Intake Sheet', "{$transaction->viewUrlSeniorCitizenIntakeSheet}&create_document=intake-sheet") ?> <?= Html::ifElse($transaction->general_intake_sheet, $checked, $xmark) ?></li>
    <li>Upload or Scan Senior Citizen ID</li>
    <li>Click the <b>"Complete"</b> button</li>
    <li>Add remarks</li>
    <li>Save</li>
</ul>

<hr>
<p class="lead text-danger mt-5 font-weight-bold">To Decline</p>
<ul>
    <li>Click the <b>"Decline"</b> button</li>
    <li>Add remarks</li>
    <li>Save</li>
</ul>