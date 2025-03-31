<?php

use app\models\Country;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;

/* @var $this yii\web\View */
/* @var $model app\models\Region */
/* @var $form app\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'region-form']); ?>
    <div class="row">
        <div class="col-md-5">
			<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= BootstrapSelect::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'country_id',
                'data' => Country::dropdown()
            ]) ?>
			<?= $form->field($model, 'no')->textInput() ?>
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