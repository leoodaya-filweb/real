<?php

use app\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Barangay */
/* @var $form app\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'barangay-form']); ?>
    <div class="row">
        <div class="col-md-5">
            <p class="lead font-weight-bolder mb-5">Municipality: <?= $model->municipalityName ?></p>
			<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'no')->textInput([
                'type' => 'number'
            ]) ?>
			<?= $form->field($model, 'priority_score')->textInput([
                'type' => 'number'
            ]) ?>
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