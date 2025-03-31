<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\widgets\Value;
use app\widgets\Webcam;

?>

<div class="row receive-assistance-form">
    <div class="col">
        <p class="lead font-weight-bold">Personal Information | 
            <?= Html::a('View Profile', $model->viewUrl, [
                'target' => '_blank',
                'class' => 'badge'
            ]) ?></p>
       
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'fullname'
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
        <p class="lead font-weight-bold">Proof</p>
        <?= Value::widget([
            'label' => 'Date',
            'content' => App::formatter('asFulldate', $eventMember->created_at)
        ]) ?>
        <div class="mt-5"></div>
        <?= Html::image($eventMember->photo, ['w' => 500], [
            'class' => 'img-fluid'
        ]) ?>
        <p class="text-center mt-3">
            <?= Html::a('View', ['file/viewer', 'token' => $eventMember->photo], [
                'class' => 'btn btn-outline-info font-weight-bolder',
                'target' => '_blank'
            ]) ?>
        </p>
    </div>
</div>