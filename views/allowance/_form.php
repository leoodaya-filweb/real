<?php

use app\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Allowance */
/* @var $form app\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'allowance-form']); ?>
    <div class="row">
        <div class="col-md-5">
			<?= $form->field($model, 'scholarship_id')->textInput() ?>
			<?= $form->field($model, 'semester')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'documents')->textarea(['rows' => 6]) ?>
			<?= $form->field($model, 'remarks')->textarea(['rows' => 6]) ?>
			<?= $form->field($model, 'date')->textInput() ?>
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