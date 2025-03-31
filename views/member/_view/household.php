<?php

use app\widgets\Mapbox;
use app\helpers\Html;
use app\widgets\Value;

$household = $model->household;
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
        <span>
            <?= Html::a('View Household', $household->viewUrl, [
                'target' => '_blank',
                'class' => 'btn btn-info font-weight-bolder font-size-sm'
            ]) ?>
        </span>
    </div>
    <div class="card-body pt-7">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <?= Value::widget([
                            'model' => $household,
                            'attribute' => 'no'
                        ]) ?>
                    </div>
                    <div class="col">
                        <?= Value::widget([
                            'model' => $household,
                            'attribute' => 'transfer_date'
                        ]) ?>
                    </div>
                </div>
                
                <div class="my-3"></div>
                <?= Value::widget([
                    'model' => $household,
                    'attribute' => 'regionName'
                ]) ?>
                
                <div class="my-3"></div>
                <div class="row">
                    <div class="col">
                        <?= Value::widget([
                            'model' => $household,
                            'attribute' => 'provinceName'
                        ]) ?>
                    </div>
                    <div class="col">
                        <?= Value::widget([
                            'model' => $household,
                            'attribute' => 'municipalityName'
                        ]) ?>
                    </div>
                </div>

                <div class="my-3"></div>
                <div class="row">
                    <div class="col">
                        <?= Value::widget([
                            'model' => $household,
                            'attribute' => 'purokNo'
                        ]) ?>
                    </div>
                    <div class="col">
                        <?= Value::widget([
                            'model' => $household,
                            'attribute' => 'barangayName'
                        ]) ?>
                    </div>
                </div>

                <div class="my-3"></div>
                <div class="row">
                    <div class="col">
                        <?= Value::widget([
                            'model' => $household,
                            'attribute' => 'blkNo'
                        ]) ?>
                    </div>
                    <div class="col">
                        <?= Value::widget([
                            'model' => $household,
                            'attribute' => 'lotNo'
                        ]) ?>
                    </div>
                </div>
                
                <div class="my-3"></div>
                <div class="row">
                    <div class="col">
                        <?= Value::widget([
                            'model' => $household,
                            'attribute' => 'streetName'
                        ]) ?>
                    </div>
                    <div class="col">
                        <?= Value::widget([
                            'model' => $household,
                            'attribute' => 'zoneNo'
                        ]) ?>
                    </div>
                </div>

                <div class="my-3"></div>
                <div class="row">
                    <div class="col">
                        <?= Value::widget([
                            'model' => $household,
                            'attribute' => 'sitio'
                        ]) ?>
                    </div>
                    <div class="col">
                        <?= Value::widget([
                            'model' => $household,
                            'attribute' => 'landmark'
                        ]) ?>
                    </div>
                </div>

            </div>
            <div class="col">
                <?= Mapbox::widget([
                    'draggableMarker' => false,
                    'enableClick' => false,
                    'lnglat' => [$household->longitude, $household->latitude]
                ]) ?>
            </div>
        </div>

        <?= Html::if(($imageFiles = $household->imageFiles) != null, function() use($imageFiles) {
            $images =  Html::foreach($imageFiles, function($file) {
                $image = Html::image($file, ['w' => 200], [
                    'class' => 'img-thumbnail'
                ]);
                return <<< HTML
                    <div class="col-md-3">
                        <a href="{$file->viewerUrl}" target="_blank">
                            {$image}
                        </a>
                    </div>
                HTML;
            });

            return <<< HTML
                <p class="lead font-weight-bold mt-10">Photos</p>
                <div class="row">{$images}</div>
            HTML;
        }) ?>
        
    </div>
</div>
