<?php

use app\helpers\Html;
use app\models\Budget;
use app\models\Event;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;

$this->registerJs(<<< JS
    $('#form-budget').on('beforeSubmit', function(e) {
        e.preventDefault();
        let form = $(this);
        KTApp.block('#modal-entry .modal-body', {
            overlayColor: '#000',
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
                    Swal.fire({
                        icon: "success",
                        title: "Disbursed Budget Recorded",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.reload();
                }
                else {
                    Swal.fire("Error", s.errorSummary, "error");
                }
                KTApp.unblock('#modal-entry .modal-body');
            },
            error: function(e) {
                Swal.fire("Error", e.responseText, "error");
                KTApp.unblock('#modal-entry .modal-body');
            }
        });
        return false;
    });

    $('#disbursedbudgetform-model_id').change(function() {
        KTApp.block('#modal-entry .modal-content', {
            overlayColor: '#000000',
            state: 'warning',
            message: 'Please wait...'
        });

        let eventId = $(this).val();

        if(eventId) {
            $.ajax({
                url: app.baseUrl + 'event/view',
                data: {
                    token: 'ajax',
                    id: eventId
                },
                dataType: 'json',
                method: 'get',
                success: function(s) {
                    if(s.status == 'success') {
                        $('.event-details').html(s.detail);
                        $('.event-details').prepend(s.link);
                    }
                    else {
                        Swal.fire('Error', s.error, 'error');
                    }
                    KTApp.unblock('#modal-entry .modal-content');
                },
                error: function(e) {
                    Swal.fire('Error', e.responseText, 'error');
                    KTApp.unblock('#modal-entry .modal-content');
                }
            });
        }
        else {
            $('.event-details').html('');
            KTApp.unblock('#modal-entry .modal-content');
        }
    });
JS, \yii\web\View::POS_END);

?>

<?php $form = ActiveForm::begin([
    'id' => 'form-budget',
    'enableAjaxValidation' => true,
    'validationUrl' => $validationUrl ?? ['budget/create', 'action' => Budget::SUBTRACT, 'ajaxValidate' => true]
]); ?>
    <div class="row">
        <div class="col">
            <?= $form->field($model, 'year')->textInput(['readonly' => true]) ?>
            <?= $form->field($model, 'budget')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'remarks')->textarea(['rows' => 8]) ?>
            
            <div class="mt-10">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                <?= Html::button('Cancel', ['class' => 'btn btn-light-danger', 'data-dismiss' => 'modal']) ?>
            </div>
        </div>
        <div class="col">
            <?= BootstrapSelect::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'model_id',
                'data' => Event::selfDropdown(),
                'label' => 'Select Event'
            ]) ?>

            <div class="event-details">
                <?= $model->eventDetailView ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>