<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ActiveForm;
use app\widgets\Value;

$this->registerJs(<<< JS
    $('.change-status-link').click(function() {

        let status = $(this).data('status'),
            label = $(this).data('label');

        $('#modal-change-status-{$widgetId} #changetransactionstatusform-remarks').val(label);
        $('#modal-change-status-{$widgetId} #changetransactionstatusform-status').val(status);
        $('#modal-change-status-{$widgetId} .notice p').html('Transaction status will set to ' + label);
        $('#modal-change-status-{$widgetId}').modal('show');
    });

    $('#change-status-form-{$widgetId}').on('beforeSubmit', function(e) {
        e.preventDefault();

        KTApp.block('#modal-change-status-{$widgetId} .modal-body', {
            overlayColor: '#000000',
            state: 'warning', // a bootstrap color
            message: 'Please wait...'
        });


        let form = $(this);

        $.ajax({
            url: app.baseUrl + 'transaction/change-status',
            data: form.serialize(),
            method: 'post',
            dataType: 'json',
            success: function(s) {
                if (s.status == 'success') {
                    Swal.fire({
                        icon: "success",
                        title: "Status Changed!",
                        showConfirmButton: false,
                        timer: 1000
                    });
                    window.location.reload();
                }
                else {
                    Swal.fire('Error', s.error, 'error');
                    KTApp.block('#modal-change-status-{$widgetId} .modal-body');
                }
            },  
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.block('#modal-change-status-{$widgetId} .modal-body');
            }
        });

        return false;
    });
JS);
?>

<div class="btn-group">
    <button type="button" class="btn btn-<?= $model->transactionStatusClass ?>">
        <?= $model->transactionStatusLabel ?>
    </button>
    <button type="button" class="btn btn-<?= $model->transactionStatusClass ?> dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu" x-placement="bottom-start">
        <?= Html::foreach($status, function($id) {
            $param = App::params('transaction_status')[$id];
            return Html::a($param['label'], '#', [
                'class' => 'dropdown-item change-status-link',
                'data-status' => $id,
                'data-label' => $param['label']
            ]);
        }) ?>
    </div>
</div>

<div class="modal fade" id="modal-change-status-<?= $widgetId ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-change-statusLabel-<?= $widgetId ?>">Change Transaction Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['id' => 'change-status-form-' . $widgetId]); ?>
                    <div class="notice mb-5">
                        <?= Value::widget([
                            'label' => 'Notice',
                            'content' => ''
                        ]) ?>
                    </div>
                    <?= $form->field($formModel, 'remarks')->textarea([
                        'rows' => 15
                    ]) ?>
                    <?= $form->field($formModel, 'transaction_id')->hiddenInput()->label(false) ?>
                    <?= $form->field($formModel, 'status')->hiddenInput()->label(false) ?>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success font-weight-bold">Save</button>
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>