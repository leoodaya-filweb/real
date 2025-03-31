<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\File;
use app\widgets\Dropzone;
use app\widgets\Value;
use app\widgets\Webcam;

$callback =  <<< JS
    $('.model-name-input').val('{$model->fullname} claimed pension');
    $('#modal-receive-assistance').modal('hide');
    KTApp.block('#modal-claimed .modal-content', {
        overlayColor: '#000000',
        state: 'warning',
        message: 'Claiming...',
        size: 'lg'
    });
    $.ajax({
        url: app.baseUrl + 'social-pension/claim',
        data: {
            transaction_id: {$transaction->id},
            member_id: {$model->id},
            photo: s.file.token,
        },
        method: 'post',
        dataType: 'json',
        success: function(s) {
            if(s.status == 'success') {
                Swal.fire({
                    icon: "success",
                    title: "Social Pension Claimed",
                    showConfirmButton: false,
                    timer: 1500
                });
                window.location.reload();
            }
            else {
                Swal.fire('Warning', s.error, 'warning');
            }
            KTApp.unblock('#modal-claimed .modal-content');
        },
        error: function(e) {
            Swal.fire('Error', e.responseText, 'error');
            KTApp.unblock('#modal-claimed .modal-content');
        }
    });
JS;
?>

<div class="row receive-assistance-form">
    <div class="col">
        <p class="lead font-weight-bold">
            Personal Information | 
            <?= Html::a('View Profile', $model->viewUrl, [
                'target' => '_blank',
                'class' => 'badge'
            ]) ?>
        </p>
       
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'fullname'
        ]) ?>
        <div class="mt-5"></div>
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'householdNo'
        ]) ?>
        <div class="row mt-5">
            <div class="col">
                <?= Value::widget([
                    'model' => $model,
                    'attribute' => 'sexLabel'
                ]) ?>
            </div>
            <div class="col">
                <?= Value::widget([
                    'model' => $model,
                    'attribute' => 'civilStatusName'
                ]) ?>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col">
                <?= Value::widget([
                    'model' => $model,
                    'attribute' => 'birth_date'
                ]) ?>
            </div>
            <div class="col">
                <?= Value::widget([
                    'model' => $model,
                    'attribute' => 'currentAge'
                ]) ?>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col">
                <?= Value::widget([
                    'model' => $model,
                    'attribute' => 'birth_place'
                ]) ?>
            </div>
            <div class="col">
                <?= Value::widget([
                    'model' => $model,
                    'attribute' => 'email'
                ]) ?>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col">
                <?= Value::widget([
                    'model' => $model,
                    'attribute' => 'contact_no'
                ]) ?>
            </div>
            <div class="col">
                <?= Value::widget([
                    'model' => $model,
                    'attribute' => 'telephone_no'
                ]) ?>
            </div>
        </div>

            <div class="row mt-5">
                <div class="col">
                    <?= Value::widget([
                        'label' => 'Profile Photo',
                        'content' => Html::a(Html::image($model->photo, ['w' => 150], [
                            'title' => 'Member\'s Photo',
                            'data-content' => 'Click to download',
                            'data-toggle' => 'popover',
                            'data-placement' => 'top',
                            'class' => 'img-thumbnail'
                        ]), ['file/download', 'token' => $model->photo ?: App::setting('image')->image_holder])
                    ]) ?>
                     
                </div>
                <div class="col">
                    <?= Value::widget([
                        'label' => "QR Code [{$model->qr_id}]",
                        'content' => Html::a(Html::img($model->qrCode, [
                            'width' => 150,
                            'title' => 'Member\'s QR Code',
                            'data-content' => 'Click to download',
                            'data-toggle' => 'popover',
                            'data-placement' => 'top',
                            'class' => 'img-thumbnail'
                        ]), $model->downloadQrCodeUrl)
                    ]) ?>
                </div>
            </div>
    </div>
    <div class="col">
        <?= Webcam::widget([
            'tag' => 'Member',
            'model' => $transaction,
            'buttonLabel' => 'Capture and Save',
            'attribute' => 'social_pension_photo',
            'inputValue' => $model->fullname . ' claimed pension',
            'ajaxSuccess' => $callback,
            'withInput' => false
        ]) ?>
        <p class="lead my-10 font-weight-bolder text-center">OR</p>
        <?= Dropzone::widget([
            'tag' => 'Member',
            'model' => $transaction,
            'attribute' => 'social_pension_photo',
            'acceptedFiles' => array_map(
                function($val) { 
                    return ".{$val}"; 
                }, File::EXTENSIONS['image']
            ),
            'success' => $callback
        ]) ?>
    </div>
</div>