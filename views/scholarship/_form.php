<?php

use app\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Scholarship */
/* @var $form app\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'scholarship-form']); ?>
    <div class="row">
        <div class="col-md-5">
			<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'name_suffix')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'birth_date')->textInput() ?>
			<?= $form->field($model, 'age')->textInput() ?>
			<?= $form->field($model, 'course')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'barangay_id')->textInput() ?>
			<?= $form->field($model, 'street_address')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'alternate_email')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'alternate_contact_no')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'house_no')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'guardian')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'first_enrollment')->textInput() ?>
			<?= $form->field($model, 'expected_graduation')->textInput() ?>
			<?= $form->field($model, 'current_year_level')->textInput() ?>
			<?= $form->field($model, 'school_name')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'subjects')->textarea(['rows' => 6]) ?>
			<?= $form->field($model, 'units')->textarea(['rows' => 6]) ?>
			<?= $form->field($model, 'documents')->textarea(['rows' => 6]) ?>
			<?= $form->field($model, 'photo')->textInput(['maxlength' => true]) ?>
			<?= ActiveForm::recordStatus([
                'model' => $model,
                'form' => $form,
            ]) ?>
        </div>
    </div>
    <div class="form-group">
		<?= ActiveForm::buttons() ?>
    </div>
<?php ActiveForm::end(); ?>