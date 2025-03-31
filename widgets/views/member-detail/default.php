<?php

use app\helpers\App;
use app\helpers\Url;
use app\helpers\Html;
use app\widgets\Value;
?>

<p>
    <?= $model->subcategoriesTag ?>
    <?= $model->recordStatusBadge ?>
</p>
<a href="<?= Url::to(['file/download', 'token' => $model->photo ?: App::setting('image')->image_holder]) ?>">
    <?= Html::image($model->photo, ['w' => 100], [
        'class' => 'user-photo img-thumbnail',
        'title' => 'Member\'s Photo',
        'data-content' => 'Click to download',
        'data-toggle' => 'popover',
        'data-placement' => 'top'
    ]) ?>
</a>
<a href="<?= $model->downloadQrCodeUrl ?>">
    <?= Html::img($model->qrCode, [
        'width' => 130,
        'title' => 'Member\'s QR Code',
        'data-content' => 'Click to download',
        'data-toggle' => 'popover',
        'data-placement' => 'top',
    ]) ?>
</a>


<span class="float-right">
    <?= Html::a('Update Household', $household->updateUrl, [
        'class' => 'btn btn-outline-success font-weight-bolder'
    ]) ?>
    <?= Html::a('Update Profile', $model->updateUrl, [
        'class' => 'btn btn-outline-info font-weight-bolder'
    ]) ?>
    <?= Html::if($withTransactionBtn, Html::a(<<< HTML
        <span class="svg-icon svg-icon-md svg-icon-white">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24"></rect>
                    <rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13" rx="1.5"></rect>
                    <rect fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8" rx="1.5"></rect>
                    <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"></path>
                    <rect fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6" rx="1.5"></rect>
                </g>
            </svg>
            </span>New Transaction
        HTML, $model->createTransactionLink, [
            'class' => 'btn btn-primary font-weight-bolder font-size-sm'
    ])) ?>
  

    <?= Html::if($withViewBtn, Html::a('View Profile', $model->viewUrl, [
        'class' => 'btn btn-light-info font-weight-bolder',
        'target' => '_blank'
    ])) ?>
</span>


<div class="my-7"></div>
<div class="row">
    <div class="col-md-4">
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'householdNo',
        ]) ?>
    </div>
    <div class="col-md-4">
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'qr_id',
        ]) ?>
    </div>
    
    <div class="col-md-4">
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'arc_no',
        ]) ?>
    </div>
   
</div> 

<div class="my-7"></div>
<div class="row">
    <div class="col">
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'last_name',
        ]) ?>
    </div>
    <div class="col"> 
        <?= Value::widget([
            'label' => 'Middle Name',
            'content' => $model->middleName,
        ]) ?>
    </div>
    <div class="col"> 
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'first_name',
        ]) ?>
    </div>
</div>
<div class="my-7"></div>
<div class="row">
    <div class="col">
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'sexLabel',
        ]) ?>
    </div>
    <div class="col">
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'civilStatusName',
        ]) ?>
    </div>
    <div class="col">
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'educationalAttainmentLabel',
        ]) ?>
    </div>
</div>
<div class="my-7"></div>
<div class="row">
    <div class="col">
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'birth_date',
        ]) ?>
    </div>
    <div class="col">
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'currentAge',
        ]) ?>
    </div>
    <div class="col">
        <?= Value::widget([
            'model' => $model,
            'attribute' => 'birth_place',
        ]) ?>
    </div>
    
</div>

<div class="separator separator-dashed my-7"></div>
<section class="mt-5">
    <p class="lead font-weight-bold">Address</p>
    <div class="row">
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $household,
                'attribute' => 'barangayName',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $household,
                'attribute' => 'purok_no',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $household,
                'attribute' => 'blk_no',
            ]) ?>
        </div>
    </div>
    <div class="my-7"></div>
    <div class="row">
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $household,
                'attribute' => 'lot_no',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $household,
                'attribute' => 'street',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $household,
                'attribute' => 'zone_no',
            ]) ?>
        </div>
    </div>
</section>


<div class="separator separator-dashed my-7"></div>
<section class="mt-5">
    <p class="lead font-weight-bold">Contact</p>
    <div class="row">
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'email',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'contact_no',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'telephone_no',
            ]) ?>
        </div>
    </div>
</section>

<div class="separator separator-dashed my-7"></div>
<section class="mt-5">
    <p class="lead font-weight-bold">Occupation</p>
    <div class="row">
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'occupation',
            ]) ?>
        </div>
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'monthlyIncome',
            ]) ?>
        </div>
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'source_of_income',
            ]) ?>
        </div>
    </div>
</section>

<div class="separator separator-dashed my-7"></div>
<section class="mt-5">
    <p class="lead font-weight-bold">Pension</p>
    <div class="row">
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'pensionerTag',
            ]) ?>
        </div>
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'pensioner_from',
            ]) ?>
        </div>
        <div class="col">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'monthlyPensionAmount',
            ]) ?>
        </div>
    </div>
</section>


<div class="separator separator-dashed my-7"></div>
<section class="mt-5">
    <p class="lead font-weight-bold">PWD</p>
    <div class="row">
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'pwdLabel',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'pwdTypeName',
            ]) ?>
        </div>
    </div>
</section>

<?= $model->skillsList ?>


<div class="separator separator-dashed my-7"></div>
<section class="mt-5">
    <p class="lead font-weight-bold">Others</p>
    <div class="row">
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'soloParentLabel',
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'livingStatusLabel',
            ]) ?>
        </div>

        <div class="col-md-4">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'fourPsLabel',
            ]) ?>
        </div>
    </div>
    <div class="my-7"></div>
    <div class="row">
        <div class="col-md-4">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'voterLabel',
            ]) ?>
        </div>

        <div class="col-md-4">
            <?= Value::widget([
                'model' => $model,
                'attribute' => 'soloMemberLabel',
            ]) ?>
        </div>
    </div>
</section>
