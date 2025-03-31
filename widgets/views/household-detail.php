<?php

use app\widgets\Value;
?>

<div class="row">
    <div class="col-md-4">
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'no',
        ]) ?>
    </div>
    <div class="col-md-4">
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'transfer_date',
        ]) ?>
    </div>
</div>

<div class="separator separator-dashed my-5"></div>
<section class="mt-5">
    <p class="lead font-weight-bold">Address:</p>

    <div class="row">
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'regionName',
            ]) ?>
        </div>
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'provinceName',
            ]) ?>
        </div>
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'municipalityName',
            ]) ?>
        </div>
    </div>
    <div class="my-10"></div>
    <div class="row">
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'purokNo',
            ]) ?>
        </div>
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'barangayName',
            ]) ?>
        </div>
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'blkNo',
            ]) ?>
        </div>
    </div>
    <div class="my-10"></div>
    <div class="row">
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'lotNo',
            ]) ?>
        </div>
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'streetName',
            ]) ?>
        </div>
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'zoneNo',
            ]) ?>
        </div>
    </div>
</section>

<div class="separator separator-dashed my-5"></div>
<section class="mt-5">
    <p class="lead font-weight-bold">Geo-Location:</p>

    <div class="row">
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'latitude',
            ]) ?>
        </div>
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'longitude',
            ]) ?>
        </div>
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'altitude',
            ]) ?>
        </div>
    </div>
</section>