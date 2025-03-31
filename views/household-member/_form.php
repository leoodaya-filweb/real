<?php

use app\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HouseholdMember */
/* @var $form app\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'household-member-form']); ?>
    <div class="row">
        <div class="col-md-5">
			<?= $form->field($model, 'household_id')->textInput() ?>
			<?= $form->field($model, 'member_id')->textInput() ?>
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