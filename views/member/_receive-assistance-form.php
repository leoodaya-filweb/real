<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\File;
use app\widgets\Dropzone;
use app\widgets\Reminder;
use app\widgets\Value;
use app\widgets\Webcam;

$callback =  <<< JS
    $('#modal-receive-assistance').modal('hide');
    KTApp.blockPage({
        overlayColor: '#000000',
        state: 'warning',
        message: 'Claiming...',
        size: 'lg'
    });
    $.ajax({
        url: app.baseUrl + 'event-member/receive',
        data: {
            member_id: {$model->id},
            event_id: {$event->id},
            photo: s.file.token,
            
        },
        method: 'post',
        dataType: 'json',
        success: function(s) {
            if(s.status == 'success') {
                Swal.fire({
                    icon: "success",
                    title: "Process Completed",
                    showConfirmButton: false,
                    timer: 1500
                });
                window.location.reload();
                // $('.beneficiaries-grid-container').html(s.beneficiaries);
                // $('.claimed-grid-container').html(s.claimed);
            }
            else {
                Swal.fire('Warning', s.error, 'warning');
            }
            KTApp.unblockPage();
        },
        error: function(e) {
            Swal.fire('Error', e.responseText, 'error');
            KTApp.unblockPage();
        }
    });
JS;
?>

<?= Html::if($eventMember->isClaimOrAttended, Reminder::widget([
    'type' => 'info',
    'head' => 'Participated',
    'message' => 'This member was already participated.'
])) ?>
<div class="row receive-assistance-form">
    <div class="col-md-6">
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
    <div class="col-md-6">
        <?php if (! $eventMember->isClaimOrAttended): ?>
            <?= Webcam::widget([
                'tag' => 'Member',
                'model' => $eventMember,
                'buttonLabel' => 'Capture and Save',
                'attribute' => 'photo',
                'inputValue' => $model->fullname . ' received',
                'ajaxSuccess' => $callback
            ]) ?>
            <p class="lead my-10 font-weight-bolder text-center">OR</p>
            <?= Dropzone::widget([
                'tag' => 'Member',
                'model' => $model,
                'attribute' => 'photo',
                'acceptedFiles' => array_map(
                    function($val) { 
                        return ".{$val}"; 
                    }, File::EXTENSIONS['image']
                ),
                'success' => $callback
            ]) ?>
        <?php endif ?>

    </div>
</div>