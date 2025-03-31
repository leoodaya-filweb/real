<?php

use app\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Budget */
/* @var $form app\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'budget-form']); ?>
    <div class="row">
        <div class="col-md-5">
			<?= $form->field($model, 'year')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'type')->textInput() ?>
			<?= $form->field($model, 'budget')->textInput(['maxlength' => true]) ?>
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