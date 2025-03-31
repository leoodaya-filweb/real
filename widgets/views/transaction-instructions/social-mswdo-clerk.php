<?php

use app\helpers\Html;
?>

<p class="lead text-success font-weight-bold">To Complete</p>
<ul>
    <li>Create <?= Html::a('Social Pension Application', "{$transaction->viewUrlSocialPensionApplicationForm}&create_document=spaf") ?> <?= Html::ifElse($transaction->social_pension_application_form, $checked, $xmark) ?></li>
    <li>Click the <b>"Complete"</b> button</li>
    <li>Confirm action</li>
</ul>

<hr>
<p class="lead text-danger mt-5 font-weight-bold">To Decline</p>
<ul>
    <li>Click the <b>"Decline"</b> button</li>
    <li>Add remarks</li>
    <li>Save</li>
</ul>