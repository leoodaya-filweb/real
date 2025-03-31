<?php

use app\widgets\Mapbox;
use app\helpers\Html;
use app\widgets\Value;
use app\models\BarangayCoordinates;



if(!$model->latitude || !$model->longitude){

$barangay_c = BarangayCoordinates::find()->andWhere(['or',
    ['like', 'barangay', $model->barangayName],
    ['like', 'barangay_2', $model->barangayName]
]
)->one();
if($barangay_c->coordinates){
  $coordinates=json_decode($barangay_c->coordinates,true);
  $model->latitude = $coordinates[0][1];
  $model->longitude = $coordinates[0][0];
 }
}
        
?>
<h4 class="mb-10 font-weight-bold text-dark">Map View
    <?= Html::a('<i class="fa fa-edit text-warning"></i>', ['update', 'no' => $model->no, 'step' => 'map'], [
        'data-toggle' => 'tooltip',
        'title' => 'Edit'
    ]) ?>

    <span class="float-right">  
        <a target="_blank" href="https://www.google.com/maps?z=17&daddr=<?= $model->latitude ?>,<?= $model->longitude ?>" class="btn btn-primary">
            View on google map
        </a>
    </span>
</h4>
<section class="mt-5">
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
<section class="mt-5">
    <?= Mapbox::widget([
        'enableClick' => false,
        'draggableMarker' => false,
        'lnglat' => [$model->longitude, $model->latitude],
    ]) ?>
</section>
