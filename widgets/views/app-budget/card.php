<?php

use app\helpers\Html;
?>

<div class="card card-custom card-stretch gutter-b app-budget" id="<?= $widgetId ?>">
    <div class="card-header border-0 pt-6">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label font-weight-bolder font-size-h4 text-dark-75">
                Budget
            </span>
            <span class="text-muted mt-3 font-weight-bold font-size-lg">
                Budget as of this year
            </span>
        </h3>
        <div class="card-toolbar">
            <?= Html::a('View Budget', ['budget/index'], [
                'class' => 'btn btn-light-primary font-weight-bold'
            ]) ?>
        </div>
    </div>
    <div class="card-body pt-5">

        <?= $content ?>
    </div>
</div>

