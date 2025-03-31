<?php

use app\helpers\Html;
use app\widgets\Dafac;

?>

<div class="member-fields-container">
    <h4 class="mb-10 font-weight-bold text-dark">
        <?= $step_form['description'] ?>
    </h4>

    <?= Dafac::widget([
        'household' => $model
    ]) ?>
</div>
