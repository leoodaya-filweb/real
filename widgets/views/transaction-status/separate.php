<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Transaction;
use app\widgets\ActiveForm;
use app\widgets\TransactionInstructions;
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

    $('.change-status-link-completed').click(function() {
        Swal.fire({
            title: "Are you sure?",
            text: "You are going to complete this transaction.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Set as Completed",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then(function(result) {
            if (result.value) {
                KTApp.block('body', {
                    overlayColor: '#000000',
                    state: 'warning', // a bootstrap color
                    message: 'Please wait...'
                });
                $.ajax({
                    url: app.baseUrl + 'transaction/can-completed',
                    data: {token: '{$model->token}'},
                    method: 'post',
                    dataType: 'json',
                    success: function(s) {
                        if (s.status == 'success') {
                            Swal.fire({
                                icon: "success",
                                title: s.message,
                                showConfirmButton: false,
                                timer: 1000
                            });
                            window.location.href = s.viewUrlDetails;
                        }
                        else {
                            Swal.fire('Error', s.error, 'error');
                            KTApp.unblock('body');
                        }
                    },  
                    error: function(e) {
                        Swal.fire('Error', e.responseText, 'error');
                        KTApp.unblock('body');
                    }
                });
            } 
        });
    });
JS);
?>

<?= Html::foreach($status, function($id) use($model) {
    $param = App::params('transaction_status')[$id];

    $show = false;

    switch ($id) {
        case Transaction::MHO_APPROVED: $show = $model->canMhoApproved; break; 
        case Transaction::MHO_DECLINED: $show = $model->canMhoDeclined; break;
        case Transaction::MSWDO_HEAD_APPROVED: $show = $model->canMswdoHeadApproved; break;
        case Transaction::MSWDO_HEAD_DECLINED: $show = $model->canMswdoHeadDeclined; break;
        case Transaction::MAYOR_APPROVED: $show = $model->canMayorApproved; break;
        case Transaction::MAYOR_DECLINED: $show = $model->canMayorDeclined; break;
        case Transaction::BUDGET_OFFICER_CERTIFIED: $show = $model->canBudgetOfficerCertified; break;
        case Transaction::DISBURSED: $show = $model->canDisbursed; break;
        case Transaction::COMPLETED: $show = $model->canCompleted; break;
        case Transaction::WHITE_CARD_CREATED: $show = true; break;
        case Transaction::CERTIFICATE_CREATED: $show = true; break;
        case Transaction::MSWDO_CLERK_APPROVED: $show = $model->canMswdoClerkApproved; break;
        case Transaction::ACCOUNTING_COMPLETED: $show = $model->canAccountingCompleted; break;
        case Transaction::PAYMENT_COMPLETED: $show = $model->canPaymentCompleted; break;
        case Transaction::ID_RELEASED: $show = $model->canIdReleased; break;
        case Transaction::SOCIAL_PENSION_RECEIVED: $show = true; break;
        case Transaction::MSWDO_CLERK_DECLINED: $show = $model->canClerkDeclined; break; 
        default:
            // code...
            break;
    }

    if ($show) {
        if ($id == Transaction::COMPLETED) {
            return Html::a($param['actionLabel'], '#', [
                'class' => "btn btn-{$param['class']} change-status-link-completed font-weight-bolder font-size-sm",
                'data-status' => $id,
                'data-label' => $param['label'],
            ]);
        }
    }


    return $show ? Html::a($param['actionLabel'], '#', [
        'class' => "btn btn-{$param['class']} change-status-link font-weight-bolder font-size-sm",
        'data-status' => $id,
        'data-label' => $param['label'],
    ]): TransactionInstructions::widget([
        'withAlert' => true,
        'transaction' => $model,
        'buttonContent' => $param['actionLabel'],
        'buttonOptions' => [
            'class' => "btn btn-{$param['class']} font-weight-bolder font-size-sm",
            'data-status' => $id,
            'data-label' => $param['label'],
            'data-toggle' => 'modal',
            'data-modal-title' => 'Please Complete the Instructions First!'
        ]
    ]);
}) ?>

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
                        'rows' => 10
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