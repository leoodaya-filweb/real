<?php

use app\helpers\Html;
use app\widgets\HouseholdDetail;
use app\widgets\Value;
?>
<div class="general-information">
    <h4 class="mb-10 font-weight-bold text-dark">
        General Information
        <?= Html::a('<i class="fa fa-edit text-warning"></i>', ['update', 'no' => $model->no, 'step' => 'general-information']) ?>
    </h4>

    <?= HouseholdDetail::widget(['model' => $model]) ?>
</div>
