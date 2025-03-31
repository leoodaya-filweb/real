<?php

use app\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EventMember */
/* @var $form app\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'event-member-form']); ?>
    <div class="row">
        <div class="col-md-5">
			<?= $form->field($model, 'event_id')->textInput() ?>
			<?= $form->field($model, 'member_id')->textInput() ?>
			<?= $form->field($model, 'photo')->textarea(['rows' => 6]) ?>
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