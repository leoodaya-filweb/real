<?php

use app\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionLog */
/* @var $form app\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'transaction-log-form']); ?>
    <div class="row">
        <div class="col-md-5">
			<?= $form->field($model, 'transaction_id')->textInput() ?>
			<?= $form->field($model, 'remarks')->textarea(['rows' => 6]) ?>
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