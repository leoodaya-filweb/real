<?php

use app\widgets\Mapbox;
use app\helpers\Html;
use app\widgets\Value;
?>
<div class="">
    <h3 class="card-title align-items-start flex-column">
        <span class="card-label font-weight-bolder font-size-h4 text-dark-75">
            Household Information
        </span>

        <?= Html::a('Edit Household', $model->updateUrl, [
            'target' => '_blank',
            'class' => 'btn btn-outline-info font-weight-bolder btn-sm'
        ]) ?>
    </h3>
    <div class="row">
        <div class="col">

            <?= Value::widget([
               // 'model' => $model,
                //'attribute' => 'headerName',
                 'label'=> 'Family Head',
                 'content'=> $model->headerName,
               
            ]) ?>
            <div class="my-3"></div>
            
            <div class="row">
                <div class="col">
                    <?= Value::widget([
                        'model' => $model,
                        'attribute' => 'no'
                    ]) ?>
                </div>
                <div class="col">
                    <?= Value::widget([
                        'model' => $model,
                        'attribute' => 'transfer_date'
                    ]) ?>
                </div>
            </div>
            
            <div class="my-3"></div>
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'regionName'
            ]) ?>
            
            <div class="my-3"></div>
            <div class="row">
                <div class="col">
                    <?= Value::widget([
                        'model' => $model,
                        'attribute' => 'provinceName'
                    ]) ?>
                </div>
                <div class="col">
                    <?= Value::widget([
                        'model' => $model,
                        'attribute' => 'municipalityName'
                    ]) ?>
                </div>
            </div>

            <div class="my-3"></div>
            <div class="row">
                <div class="col">
                    <?= Value::widget([
                        'model' => $model,
                        'attribute' => 'purokNo'
                    ]) ?>
                </div>
                <div class="col">
                    <?= Value::widget([
                        'model' => $model,
                        'attribute' => 'barangayName'
                    ]) ?>
                </div>
            </div>

            <div class="my-3"></div>
            <div class="row">
                <div class="col">
                    <?= Value::widget([
                        'model' => $model,
                        'attribute' => 'blkNo'
                    ]) ?>
                </div>
                <div class="col">
                    <?= Value::widget([
                        'model' => $model,
                        'attribute' => 'lotNo'
                    ]) ?>
                </div>
            </div>
            
            <div class="my-3"></div>
            <div class="row">
                <div class="col">
                    <?= Value::widget([
                        'model' => $model,
                        'attribute' => 'streetName'
                    ]) ?>
                </div>
                <div class="col">
                    <?= Value::widget([
                        'model' => $model,
                        'attribute' => 'zoneNo'
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col">
            <?= Mapbox::widget([
                'lnglat' => [$model->longitude, $model->latitude],
                'draggableMarker' => false,
                'enableClick' => false,
            ]) ?>
        </div>
    </div>

    <div class="row mt-10">
        <div class="col">
            <p class="lead font-weight-bold">
                Household Members (<?= $model->totalMembers ?>)
                <?= Html::a('Edit Family Composition', $model->updateUrlFamilyCompositionTab, [
                    'class' => 'btn btn-outline-info btn-sm font-weight-bolder',
                    'target' => '_blank'
                ]) ?>
            </p>
            <?= Html::foreach($model->members, function($member) {
                $img = Html::image($member->photo, ['w' => 40]);
            
                return Value::widget([
                    'content' => <<< HTML
                        <div class="d-flex">
                            <div class="symbol symbol-40 symbol-sm flex-shrink-0">
                                {$img}
                            </div>
                            <div class="ml-4">
                                <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">
                                    {$member->name}
                                </div>
                                <p class="font-weight-bold mb-0">
                                    {$member->tags}
                                </p>
                            </div>
                            <div style="position: absolute;right: 30px;">
                                {$member->profileBtn}
                            </div>
                        </div>
                    HTML
                ]) . '<div class="my-2"></div>';
            }) ?>
        </div>
    </div>
</div>
