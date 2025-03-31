<?php

use app\helpers\Html;
use app\widgets\ActiveForm;
use app\widgets\DatePicker;
use app\widgets\Dropzone;
?>


<?php $form = ActiveForm::begin([
    'id' => 'allowance-ajax',
    'enableAjaxValidation' => true,
    'validationUrl' => ['allowance/create', 'ajaxValidate' => true, 'scholarship_id' => $model->scholarship_id]
]); ?>

    <div class="row">
        <div class="col-md-12">
            <?= DatePicker::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'date'
            ]) ?>
            <?= $form->field($model, 'semester')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'remarks')->textarea(['rows' => 6]) ?>

            <?= Dropzone::widget([
                'tag' => 'Allowance',
                'model' => $model,
                'attribute' => 'documents',
            ]) ?>
        </div>
    </div>
    <div class="form-group float-right mt-10">
        <?= Html::submitButton('Save', [
            'class' => 'btn btn-success',
        ]) ?>

        <?= Html::resetButton('Cancel', [
            'class' => 'btn btn-light-danger', 
            'data-dismiss' => 'modal',
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>