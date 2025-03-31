<?php

use app\helpers\App;
use app\helpers\ArrayHelper;
use app\widgets\BootstrapSelect;
?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h3 class="card-label">Others</h3>
			<hr/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= BootstrapSelect::widget([
				'form' => $form,
				'model' => $model,
				'attribute' => 'accomplished_by',
				'data' => ArrayHelper::valueToKey(App::params('pwd_form')['accomplished_by'])
			]) ?>
		</div>
	</div>


	<hr>
	<div class="row">
		<div class="col-md-12">
			<div class="mb-2 font-weight-bold">Certifying Physician</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'license_no')->textInput([
				'maxlength' => true
			])->label('Physician License No.') ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'certifying_physician_lastname')->textInput([
				'maxlength' => true,
			])->label('Last Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'certifying_physician_firstname')->textInput([
				'maxlength' => true,
			])->label('First Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'certifying_physician_middlename')->textInput([
				'maxlength' => true,
			])->label('Middle Name') ?>
		</div>
	</div>
		
	<hr>
	<div class="row">
		<div class="col-md-12">
			<div class="mb-2 font-weight-bold">Processing Officer</div>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'processing_officer_lastname')->textInput([
				'maxlength' => true,
			])->label('Last Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'processing_officer_firstname')->textInput([
				'maxlength' => true,
			])->label('First Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'processing_officer_middlename')->textInput([
				'maxlength' => true,
			])->label('Middle Name') ?>
		</div>
	</div>

	<hr>
	<div class="row">
		<div class="col-md-12">
			<div class="mb-2 font-weight-bold">Approving Officer</div>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'approving_officer_lastname')->textInput([
				'maxlength' => true,
			])->label('Last Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'approving_officer_firstname')->textInput([
				'maxlength' => true,
			])->label('First Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'approving_officer_middlename')->textInput([
				'maxlength' => true,
			])->label('Middle Name') ?>
		</div>
	</div>

	<hr>
	<div class="row">
		<div class="col-md-12">
			<div class="mb-2 font-weight-bold">Encoder</div>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'encoder_lastname')->textInput([
				'maxlength' => true,
			])->label('Last Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'encoder_firstname')->textInput([
				'maxlength' => true,
			])->label('First Name') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'encoder_middlename')->textInput([
				'maxlength' => true,
			])->label('Middle Name') ?>
		</div>
	</div>
	<hr>


	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'reporting_unit')->textInput([
				'maxlength' => true
			]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'control_no')->textInput([
				'maxlength' => true
			]) ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'remarks')->textarea([
				'rows' => 8
			]) ?>
		</div>
	</div>
</section>