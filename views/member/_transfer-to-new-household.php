<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Barangay;
use app\models\File;
use app\models\Household;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;
use app\widgets\Dropzone;
use app\widgets\Mapbox;
use app\widgets\Reminder;
use app\widgets\Value;

$household = new Household();

$this->registerJs(<<< JS
    $('.kt-selectpicker').selectpicker();
    $('.btn-transfer-to-new-household').on('click', function() {
        KTApp.block('#modal-transfer-to-new-household .modal-content', {
            overlayColor: '#000',
            state: 'primary',
            message: 'Please wait...'
        });
        setTimeout(function() {
            KTApp.unblock('#modal-transfer-to-new-household .modal-content');
        }, 2000);

        return true;
    });
JS);
?>

<?php $form = ActiveForm::begin([
    'id' => 'form-transfer-to-new-household',
    'enableAjaxValidation' => true,
    'action' => ['member/transfer-to-new-household', 'member_id' => $model->member_id,],
    'validationUrl' => [
        'member/transfer-to-new-household', 
        'member_id' => $model->member_id,
        'ajaxValidate' => true
    ]
]); ?>  
    <div class="mb-5">
        <?= Reminder::widget([
            'head' => 'Notice',
            'message' => 'This member will automatically set as the family head.',
            'type' => 'info'
        ]) ?>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'household_no')->textInput([
                'maxlength' => 15
            ])->label('Household Number') ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'transfer_date', [
                'options' => [
                    'id' => 'kt_datetimepicker',
                    'data-target-input' => 'nearest'
                ],
                'template' => <<< HTML
                    {label}
                    <div class="input-group date">
                        {input}
                        <div class="input-group-append" data-target="#kt_datetimepicker" data-toggle="datetimepicker">
                            <span class="input-group-text">
                                <i class="ki ki-calendar"></i>
                            </span>
                        </div>
                    </div>
                    {error}
                HTML
            ])->textInput([
                'class' => 'form-control datetimepicker-input',
                'placeholder' => 'Select date & time',
                'data-target' => '#kt_datetimepicker',
            ]) ?>
        </div>
    </div>

    <section class="mt-5">
       <p class="lead font-weight-bold">Address:</p>

        <div class="row">
            <div class="col">
                <?= Value::widget([
                    'model' => App::setting('address'),
                    'attribute' => 'regionName'
                ]) ?>
            </div>
            <div class="col">
                <?= Value::widget([
                    'model' => App::setting('address'),
                    'attribute' => 'provinceName'
                ]) ?>
            </div>
            <div class="col">
                <?= Value::widget([
                    'model' => App::setting('address'),
                    'attribute' => 'municipalityName'
                ]) ?>
            </div>
        </div>
        <div class="row mt-10">
            <div class="col">
                <?= BootstrapSelect::widget([
                    'form' => $form,
                    'model' => $model,
                    'prompt' => false,
                    'label' => 'Barangay',
                    'attribute' => 'barangay_id',
                    'data' => Barangay::dropdown('no', 'name', [
                        'municipality_id' => App::setting('address')->municipality_id
                    ])
                ]) ?>
                <?= $this->render('@app/views/household/_form/_add-new-btn', [
                    'url' => ['barangay/create'],
                    'title' => 'Barangay'
                ]) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'purok_no')->textInput() ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'blk_no')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= $form->field($model, 'lot_no')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'zone_no')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'sitio')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($model, 'landmark')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </section>

    <section class="mt-5">
        <div class="row">
            <div class="col">
                <?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'altitude')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <?= Mapbox::widget([
            'onClickScript' => <<< JS
                $('#transfertonewhouseholdform-latitude').val(coordinate.lat);
                $('#transfertonewhouseholdform-longitude').val(coordinate.lng);
            JS,
            'markerDragEndScript' => <<< JS
                $('#transfertonewhouseholdform-latitude').val(coordinate.lat);
                $('#transfertonewhouseholdform-longitude').val(coordinate.lng);
            JS,
        ]) ?>
    </section>

    <section class="mt-5">
        <div class="row">
            <div class="col-md-12">
                <p class="lead font-weight-bold mt-5">UPLOAD PHOTOS</p>
                <?= Dropzone::widget([
                    'tag' => 'Household',
                    'model' => $model,
                    'attribute' => 'files',
                    'acceptedFiles' => array_map(
                        function($val) { 
                            return ".{$val}"; 
                        }, File::EXTENSIONS['image']
                    )
                ]) ?>
            </div>
        </div>
    </section>

    <div class="form-group text-right my-10">
        <?= Html::submitButton('Save', [
            'class' => 'btn btn-success btn-lg btn-transfer-to-new-household',
        ]) ?>
        <?= Html::resetButton('Cancel', [
            'class' => 'btn btn-light-danger btn-lg', 
            'data-dismiss' => 'modal',
        ]) ?>
    </div>

<?php ActiveForm::end(); ?>

