<?php

use app\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ValueLabel */
/* @var $form app\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'value-label-form']); ?>
    <div class="row">
        <div class="col-md-5">
			<?= $form->field($model, 'var')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'value')->textInput() ?>
			<?= $form->field($model, 'elementID')->textInput() ?>
			<?= $form->field($model, 'label')->textarea(['rows' => 6]) ?>
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