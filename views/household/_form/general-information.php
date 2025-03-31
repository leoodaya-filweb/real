<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Barangay;
use app\models\Country;
use app\models\File;
use app\models\Household;
use app\models\Municipality;
use app\models\Province;
use app\models\Region;
use app\models\form\UserAgentForm;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;
use app\widgets\Dropzone;
use app\widgets\Map;
use app\widgets\Value;

$this->registerCss(<<< CSS
	.content .bootstrap-select .dropdown-menu {
		z-index: 99;
	}
CSS);
?>

<h4 class="mb-10 font-weight-bold text-dark">General Information</h4>

<?php $form = ActiveForm::begin(['id' => 'household-form']); ?>
    <div class="row">
        <div class="col-md-4">
			<?= $form->field($model, 'no')->textInput([
				'maxlength' => 15
			])->label('Household Number') ?>
        </div>
        <div class="col-md-4">
        	<label class="control-label" for="household-transfer_date">Transfer Date</label>
			<?= $form->field($model, 'transfer_date', [
				'options' => [
					'class' => 'input-group date', 
					'id' => 'kt_datetimepicker',
					'data-target-input' => 'nearest'
				],
				'template' => <<< HTML
					{input}
					<div class="input-group-append" data-target="#kt_datetimepicker" data-toggle="datetimepicker">
							<span class="input-group-text">
								<i class="ki ki-calendar"></i>
							</span>
						</div>
					{error}
				HTML
			])->textInput([
				'class' => 'form-control datetimepicker-input',
				'placeholder' => 'Select date & time',
				'data-target' => '#kt_datetimepicker'
			]) ?>
        </div>
    </div>

    <section class="mt-5">
	   <p class="lead font-weight-bold">Address:</p>

	    <div class="row">
	    	<div class="col">
				<?= Value::widget([
					'model' => $model,
					'attribute' => 'regionName'
				]) ?>
	    	</div>
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
	    <div class="row mt-10">
	    	<div class="col">
				<?= BootstrapSelect::widget([
	    			'form' => $form,
	    			'model' => $model,
	    			'prompt' => false,
	    			'attribute' => 'barangay_id',
	    			'data' => Barangay::dropdown('no', 'name', [
						'municipality_id' => App::setting('address')->municipality_id
					])
	    		]) ?>
				<?= $this->render('_add-new-btn', [
					'url' => ['barangay/create'],
					'title' => 'Barangay'
				]) ?>
	    	</div>
	    	<div class="col">
				<?= $form->field($model, 'purok_no')->textInput() ?>
	    	</div>
	    	<div class="col">
				<?= $form->field($model, 'blk_no')->textInput(['maxlength' => true]) ?>
	    	</div>
	    </div>
	    <div class="row">
	    	<div class="col">
				<?= $form->field($model, 'lot_no')->textInput(['maxlength' => true]) ?>
	    	</div>
	    	<div class="col">
				<?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>
	    	</div>
	    	<div class="col">
				<?= $form->field($model, 'zone_no')->textInput() ?>
	    	</div>
	    </div>

	    <div class="row">
	    	<div class="col-md-4">
				<?= $form->field($model, 'sitio')->textInput(['maxlength' => true]) ?>
	    	</div>
	    	<div class="col">
				<?= $form->field($model, 'landmark')->textInput(['maxlength' => true]) ?>
	    	</div>
	    </div>

	    <div>
	    	<p class="lead">Upload Images</p>
		    <?= Dropzone::widget([
		    	'tag' => 'Household',
		        'files' => $model->imageFiles,
		        'model' => $model,
		        'attribute' => 'files',
		        'acceptedFiles' => array_map(
		            function($val) { 
		                return ".{$val}"; 
		            }, File::EXTENSIONS['image']
		        )
		    ]) ?>
	    </div>
	</section>

    <div class="form-group mt-5">
        <?= Html::submitButton('Next', [
            'class' => 'btn btn-success btn-lg'
        ]) ?>
        <?= Html::a('Cancel', (new Household())->indexUrl, [
            'class' => 'btn btn-danger btn-lg'
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>