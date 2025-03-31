<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;

/* @var $this yii\web\View */
/* @var $model app\models\RateType */
/* @var $form app\widgets\ActiveForm */

$this->registerJs(<<< JS
    $('form#event-category-ajax').on('beforeSubmit', function(e) {
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
                    if($("#event-category_id").length > 0) {
                        $("#event-category_id").append('<option value="'+s.model.id+'" selected="">'+s.model.name+'</option>');

                        $("#event-category_id").selectpicker("refresh");
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
    'id' => 'event-category-ajax',
    'enableAjaxValidation' => true,
    'validationUrl' => ['event-category/create', 'ajaxValidate' => true]
]); ?>

    <div class="row">
        <div class="col-md-12">
            <?= BootstrapSelect::widget([
                'model' => $model,
                'form' => $form,
                'attribute' => 'sort_order',
                'data' => App::keyMapParams('event_category_types')
            ]) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
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

