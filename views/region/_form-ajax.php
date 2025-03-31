<?php

use app\helpers\Html;
use app\models\Country;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;

/* @var $this yii\web\View */
/* @var $model app\models\RateType */
/* @var $form app\widgets\ActiveForm */

$this->registerJs(<<< JS
    $('form#region-ajax').on('beforeSubmit', function(e) {
        e.preventDefault();
        let form = $(this);
        KTApp.block('#add-entry-modal .modal-body', {
            state: 'warning', // a bootstrap color
            message: 'Saving...',
        });
 
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            dataType: 'json',
            data: form.serialize(),
            success: function(s) {
                if(s.status == 'success') {
                    $("#household-region_id").append('<option value="'+s.model.id+'" selected="">'+s.model.name+'</option>');

                    $("#household-region_id").selectpicker("refresh");

                    $('.modal').modal('hide');
                }
                else {
                    Swal.fire("Error", s.errorSummary, "error");
                }
                KTApp.unblock('#add-entry-modal .modal-body');
            },
            error: function(e) {
                    Swal.fire("Error", e.responseText, "error");
                KTApp.unblock('#add-entry-modal .modal-body');
            }
        });
        return false;
    });
JS, \yii\web\View::POS_END);
?>


<?php $form = ActiveForm::begin([
    'id' => 'region-ajax',
    'enableAjaxValidation' => true,
    'validationUrl' => ['region/create', 'ajaxValidate' => true]
]); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'no')->textInput() ?>
        </div>
    </div>
    <div class="form-group float-right">
        <?= Html::resetButton('Close', [
            'class' => 'btn btn-secondary', 
            'data-dismiss' => 'modal',
        ]) ?>
        <?= Html::submitButton('Save', [
            'class' => 'btn btn-success',
        ]) ?>
    </div>

<?php ActiveForm::end(); ?>

