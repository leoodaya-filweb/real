<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\CivilStatus;
use app\models\Database;
use app\models\PwdType;
use app\widgets\BootstrapSelect;
use app\widgets\DataList;
use app\widgets\DatePicker;
use app\widgets\ImageGallery;

$this->registerJs(<<< JS
	function getAge(dateString) {
		var ageInMilliseconds = new Date() - new Date(dateString);
		return Math.floor(ageInMilliseconds/1000/60/60/24/365); // convert to years
	}

	$(document).on('change', '#database-date_of_birth', function() {
		let birthDate = $(this).val();

		$('#database-age').val(getAge(birthDate));
	});
JS);
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Primary Information</h3>
			 <hr/>
		</div>
	</div>

	<div class="row mb-5">
		<div class="col-md-4">
			<div class="text-center" style="width: fit-content;">
				<?= Html::image($model->photo, ['w'=>100], [
	                'class' => 'img-thumbnail user-photo mw-100',
	                'loading' => 'lazy',
	            ] ) ?>
	            <div class="my-2"></div>
	            <?= ImageGallery::widget([
	            	'tag' => 'Database',
	            	'buttonTitle' => 'Profile Photo',
	                'model' => $model,
	                'attribute' => 'photo',
	                'ajaxSuccess' => "
	                    if(s.status == 'success') {
	                        $('.user-photo').attr('src', s.sr);
	                    }
	                ",
	            ]) ?> 
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'first_name')->textInput([
				'maxlength' => true
			]) ?>
		</div>			
		<div class="col-md-4">
			<?= $form->field($model, 'middle_name')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'last_name')->textInput([
				'maxlength' => true
			]) ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'name_suffix')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'date_of_birth', [
                                            'template' => "
                                                {label} <span class='text-muted'>(MM/DD/YYYY)</span>
                                                {input}
                                                {error}
                                            "    
                                        ])->textInput([
                                            //'datepicker' => 'true',
                                            'class'=>'form-control date-form ',
                                            'data-inputmask'=>"'alias': 'datetime', 'inputFormat': 'mm/dd/yyyy'",
                                            'autocomplete' => 'off']);
                   ?>
		</div>
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'gender',
				'data' => array_combine(App::params('pwd_form')['sex'], App::params('pwd_form')['sex']),
			]) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'civil_status',
				'data' => array_combine(App::params('pwd_form')['civil_status'], App::params('pwd_form')['civil_status']),
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'arc_no')->textInput([
			//	'maxlength' => true
			]) ?>
		</div>
	</div>

</section>		