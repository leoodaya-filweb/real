<?php

use app\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(['id' => 'setting-personnel-form']); ?>
    <h4 class="mb-10 font-weight-bold text-dark">Personnels</h4>
	<div class="row">
		<div class="col">
			<?= $form->field($model, 'mayor')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col">
			<?= $form->field($model, 'mswdo')->textInput(['maxlength' => true]) ?>
		</div>

		<div class="col">
			<?= $form->field($model, 'budget_officer')->textInput(['maxlength' => true]) ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'disbursing_officer')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'senior_citizen_president')->textInput(['maxlength' => true]) ?>
		</div>
	</div>
	<div class="form-group"> <br>
		<?= ActiveForm::buttons() ?>
	</div>
<?php ActiveForm::end(); ?>