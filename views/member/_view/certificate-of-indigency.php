<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\CertificateOfIndigency;

?>

<div class="card card-custom card-stretch gutter-b">
    <div class="card-header border-0 pt-6">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label font-weight-bolder font-size-h4 text-dark-75">
                <?= $tabData['title'] ?>
            </span>
            <span class="text-muted mt-3 font-weight-bold font-size-lg">
                <?= $tabData['description'] ?>
            </span>
        </h3>
    </div>
    <div class="card-body pt-7">
        <div class="border border-2">
            <?= CertificateOfIndigency::widget([
                'model' => $model
            ]) ?>
        </div>
    </div>
</div>
