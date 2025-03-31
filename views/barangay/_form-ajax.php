<?php

use app\helpers\Html;
use app\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RateType */
/* @var $form app\widgets\ActiveForm */

$this->registerJs(<<< JS
    $('form#brgy-ajax').on('beforeSubmit', function(e) {
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
                    if($("#household-barangay_id").length > 0) {
                        $("#household-barangay_id").append('<option value="'+s.model.id+'" selected="">'+s.model.name+'</option>');

                        $("#household-barangay_id").selectpicker("refresh");
                    }

                    if($("#transfertonewhouseholdform-barangay_id").length > 0) {
                        $("#transfertonewhouseholdform-barangay_id").append('<option value="'+s.model.id+'" selected="">'+s.model.name+'</option>');

                        $("#transfertonewhouseholdform-barangay_id").selectpicker("refresh");
                    }

                    $('#add-entry-modal').modal('hide');
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
    'id' => 'brgy-ajax',
    'enableAjaxValidation' => true,
    'validationUrl' => ['barangay/create', 'ajaxValidate' => true]
]); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'no')->textInput([
                'type' => 'number'
            ]) ?>
            <?= $form->field($model, 'priority_score')->textInput([
                'type' => 'number'
            ]) ?>
        </div>
    </div>
    <div class="form-group float-right">
        <?= Html::submitButton('Save', [
            'class' => 'btn btn-success',
        ]) ?>

        <?= Html::resetButton('Cancel', [
            'class' => 'btn btn-light-danger', 
            'data-dismiss' => 'modal',
        ]) ?>
        
    </div>

<?php ActiveForm::end(); ?>

