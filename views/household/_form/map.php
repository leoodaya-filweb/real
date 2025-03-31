<?php

use app\helpers\Url;
use app\widgets\Mapbox;
use app\helpers\Html;
use app\widgets\ActiveForm;
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

<h4 class="mb-10 font-weight-bold text-dark">Map Plotting</h4>

<?php $form = ActiveForm::begin(['id' => 'household-form']); ?>
    <section class="mt-5">
	    <div class="row">
	    	<div class="col">
				<?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>
	    	</div>
	    	<div class="col">
	    		<?= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>
	    	</div>
	    	<div class="col">
				<?= $form->field($model, 'altitude')->textInput(['maxlength' => true]) ?>
	    	</div> 
	    </div>
		<?= Mapbox::widget([
			'lnglat' => [$model->longitude, $model->latitude],
			'onClickScript' => <<< JS
				$('#household-latitude').val(coordinate.lat);
				$('#household-longitude').val(coordinate.lng);
			JS,
			'markerDragEndScript' => <<< JS
				$('#household-latitude').val(coordinate.lat);
				$('#household-longitude').val(coordinate.lng);
			JS,
		]) ?>
	</section>

    <div class="form-group mt-5">
		<?= Html::a('Back', Url::current(['step' => 'general-information']), [
            'class' => 'btn btn-secondary btn-lg'
        ]) ?>
        <?= Html::submitButton('Next', [
            'class' => 'btn btn-success btn-lg'
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>