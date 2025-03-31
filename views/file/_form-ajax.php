<?php

use app\helpers\Html;
use app\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RateType */
/* @var $form app\widgets\ActiveForm */

$this->registerJs(<<< JS
    $('form#file-ajax').on('beforeSubmit', function(e) {
        e.preventDefault();
        let form = $(this);
        KTApp.block('#modal-edit-document .modal-body', {
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
                    $('#file-' + s.file.id).html(s.file.name);

                    let tableId = $('#file-' + s.file.id).closest('table').attr('id');

                    $('#' + tableId).DataTable({
                        destroy: true,
                        pageLength: 5,
                        order: [[0, 'desc']]
                    });

                    $('#modal-edit-document').modal('hide');
                }
                else {
                    Swal.fire("Error", s.errorSummary, "error");
                }
                KTApp.unblock('#modal-edit-document .modal-body');
            },
            error: function(e) {
                Swal.fire("Error", e.responseText, "error");
                KTApp.unblock('#modal-edit-document .modal-body');
            }
        });
        return false;
    });

    $('.btn-close-modal-edit-document').on('click', function() {
        $(this).closest('.modal').modal('hide');
    });
JS, \yii\web\View::POS_END);
?>


<?php $form = ActiveForm::begin([
    'id' => 'file-ajax',
    'enableAjaxValidation' => true,
    'action' => [
        'file/update', 
        'token' => $model->token,
    ],
    'validationUrl' => [
        'file/update', 
        'token' => $model->token,
        'ajaxValidate' => true
    ]
]); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-group float-right">
        <?= Html::submitButton('Save', [
            'class' => 'btn btn-success',
        ]) ?>

        <?= Html::resetButton('Cancel', [
            'class' => 'btn btn-light-danger btn-close-modal-edit-document', 
            // 'data-dismiss' => 'modal',
        ]) ?>
        
    </div>

<?php ActiveForm::end(); ?>

