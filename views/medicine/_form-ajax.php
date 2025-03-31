<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RateType */
/* @var $form app\widgets\ActiveForm */

$this->registerJs(<<< JS
    $('form#medicine-ajax').on('beforeSubmit', function(e) {
        e.preventDefault();
        let form = $(this);
        KTApp.block('#modal-medicine .modal-body', {
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
                    $.ajax({
                        url: app.baseUrl + 'transaction/load-medicines',
                        data: {token: '{$model->transaction->token}'},
                        dataType: 'json',
                        success: function(s) {
                            KTApp.unblock('.medicines-container');
                            $('.medicines-container table').replaceWith(s.medicines);
                            KTApp.unblock('#modal-medicine .modal-body');
                            Swal.fire({
                                icon: "success",
                                title: "Saved!",
                                showConfirmButton: false,
                                timer: 1000
                            })
                            $('#modal-medicine').modal('hide');
                            
                            $('.medicines-container span.total-price').html(s.totalMedicinePrice);
                        },
                        error: function(e) {
                            Swal.fire('Error', e.responseText, 'error');
                            KTApp.unblock('#modal-medicine .modal-body');
                        }
                    });
                }
                else {
                    Swal.fire("Error", s.errorSummary, "error");
                    KTApp.unblock('#modal-medicine .modal-body');
                }
            },
            error: function(e) {
                Swal.fire("Error", e.responseText, "error");
                KTApp.unblock('#modal-medicine .modal-body');
            }
        });
        return false;
    });
JS, \yii\web\View::POS_END);
?>


<?php $form = ActiveForm::begin([
    'id' => 'medicine-ajax',
    'enableAjaxValidation' => true,
    'action' => ['medicine/' . App::actionID(), 'id' => $model->id],
    'validationUrl' => [
        'medicine/' . App::actionID(), 
        'id' => $model->id, 
        'ajaxValidate' => true
    ]
]); ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'transaction_id')->textInput([
                'class' => 'app-hidden'
            ])->label(false) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'quantity')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'unit')->textInput([
                'maxlength' => true,
                'list' => 'units'
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

