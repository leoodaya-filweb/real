<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Event;
use app\models\EventCategory;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form app\widgets\ActiveForm */
$this->registerCss(<<< CSS
    .image-gallery-modal {
        text-align: left;
    }
    .border-class {
        border:  2px solid #1BC5BD;
        background:  #deffe7;
    }
    .hide {
        display: none;
    }
CSS);

$this->registerJs(<<< JS
    // Demo 7
    $('#date_from').datetimepicker({
        format: 'L'
    });
    $('#date_to').datetimepicker({
        format: 'L',
        useCurrent: false
    });

    let isOneDay = function() {
        if($('input[name="SocialPensionEvent[oneday]"]').is(':checked')) {
            $('input[name="SocialPensionEvent[date_to]"]').val($('input[name="SocialPensionEvent[date_from]"]').val());
            $('input[name="SocialPensionEvent[date_to]"]').attr('readonly', true);
        }
        else {
            $('input[name="SocialPensionEvent[date_to]"]').attr('readonly', false);
        }
    }
    isOneDay();

    $('input[name="SocialPensionEvent[oneday]"]').on('change', function() {
        isOneDay()
    });

    $('#date_from').on('change.datetimepicker', function (e) {
        isOneDay();

        if($('input[name="SocialPensionEvent[oneday]"]').is(':checked') == false) {
            $('#date_to').datetimepicker('minDate', e.date);
        }
    });
    $('#date_to').on('change.datetimepicker', function (e) {
        isOneDay();

        if($('input[name="SocialPensionEvent[oneday]"]').is(':checked') == false) {
            $('#date_from').datetimepicker('maxDate', e.date);
        }
    });

    $('#event-category_id').change(function() {
        let category_id = $(this).val();

        KTApp.block('#event-form', {
            overlayColor: '#000000',
            state: 'primary',
            message: 'Please wait...'
        });

        $.ajax({
            url: app.baseUrl + 'event-category/view',
            data: {slug: category_id},
            method: 'get',
            dataType: 'json',
            success: function(s) {
                if(s.status == 'success') {
                    $('.event-photo').attr('src', s.src);
                    $('input[name="SocialPensionEvent[photo]"]').val(s.model.value);
                }
                KTApp.unblock('#event-form');
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('#event-form');
            }
        });
    });

    $('#event-type').change(function() {
        let assistance = {$model->assistanceValue},
            defaultType = {$model->defaultTypeValue},
            cashValue = {$model->cashValue},
            inkindValue = {$model->inkindValue},
            value = $(this).val();

        if(assistance != value) {
            $("#event-assistance_type").html('<option value="'+defaultType+'" selected="">Default</option>');
            $("#event-assistance_type").val(defaultType);
        }
        else {
            $("#event-assistance_type").html('<option value="'+cashValue+'" selected="">Cash</option><option value="'+inkindValue+'" selected="">In-Kind</option>');

            // $("#event-assistance_type").val(defaultType);
        }

        $("#event-assistance_type").selectpicker("refresh");
        $('.field-event-assistance_type .bootstrap-select').removeClass('is-invalid');
    });
JS);
?>
<h4 class="mb-10 font-weight-bold text-dark">
    <?= $tabData['title'] ?>
</h4>

<?php $form = ActiveForm::begin([
    'id' => 'event-form',
    // 'enableAjaxValidation' => true,
    // 'validationUrl' => Url::current(['ajaxValidate' => true])
]); ?>
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <div class="checkbox-list mb-5"> 
                <label class="checkbox font-weight-bold">
                    <input type="checkbox" value="1" name="SocialPensionEvent[oneday]" <?= $model->oneday ? 'checked': '' ?>> 
                    <span></span>  One day (1) Only
                </label>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'date_from', ['template' => <<< HTML
                        {label}
                        <div class="input-group date" id="date_from" data-target-input="nearest">
                            {input}
                            <div class="input-group-append" data-target="#date_from" data-toggle="datetimepicker">
                                <span class="input-group-text">
                                    <i class="ki ki-calendar"></i>
                                </span>
                            </div>
                        </div>
                        {error}
                    HTML])->textInput([
                        'class' => 'form-control datetimepicker-input',
                        'placeholder' => 'Date From',
                        'data-target' => '#date_from',
                        'data-toggle' => 'datetimepicker',
                        'autocomplete' => 'off'
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'date_to', ['template' => <<< HTML
                        {label}
                        <div class="input-group date" id="date_to" data-target-input="nearest">
                            {input}

                            <div class="input-group-append" data-target="#date_to" data-toggle="datetimepicker">
                                <span class="input-group-text">
                                    <i class="ki ki-calendar"></i>
                                </span>
                            </div>
                        </div>
                        {error}
                    HTML])->textInput([
                        'class' => 'form-control datetimepicker-input',
                        'placeholder' => 'Date From',
                        'data-target' => '#date_to',
                        'data-toggle' => 'datetimepicker',
                        'autocomplete' => 'off'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <?= BootstrapSelect::widget([
                        'form' => $form,
                        'model' => $model,
                        'attribute' => 'social_pension_fund',
                        'data' => Event::fundsDropdown()
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'no_of_pensioner')->textInput(['type' => 'number'])->label('No. of Pensioner') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'amount')->textInput(['type' => 'number']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mt-10">
        <?= Html::submitButton('Next', [
            'class' => 'btn btn-success btn-lg',
            'name' => 'populate',
            'value' => 'pensioner'
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>


<div class="modal fade" id="add-entry-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" data-scroll="true">
                
            </div>
        </div>
    </div>
</div>
