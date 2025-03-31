<?php

use app\helpers\App;
use app\helpers\Url;
use app\helpers\Html;
use app\widgets\ImageGallery;
use app\widgets\BootstrapSelect;
?>

<h4 class="mb-10 font-weight-bold text-dark">Enter your Account Details</h4>
<div class="row">
    <div class="col">
        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col">
        <div class="text-center">
            <div class="row">
                <div class="col">
                    <?= Html::image($model->photo, ['w' => 200], [
                        'class' => 'img-thumbnail member-photo mw-200',
                        'loading' => 'lazy',
                    ] ) ?>
                    <div class="mt-3 image-gallery-container">
                        <?= ImageGallery::widget([
                            'tag' => 'Member',
                            'model' => $model,
                            'attribute' => 'photo',
                            'buttonTitle' => 'Choose Photo',
                            'ajaxSuccess' => "
                                if(s.status == 'success') {
                                    $('.member-photo').attr('src', s.src);
                                }
                            ",
                        ]) ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <?= BootstrapSelect::widget([
            'form' => $form,
            'model' => $model,
            'attribute' => 'sex',
            'data' => App::keyMapParams('genders')
        ]) ?>
    </div>
    <div class="col">
        <?= BootstrapSelect::widget([
            'form' => $form,
            'model' => $model,
            'attribute' => 'civil_status',
            'data' => App::keyMapParams('civil_status')
        ]) ?>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="row">
            <div class="col">
                <?= $form->field($model, 'birth_date', [
                    'template' => "
                		{label}
                        <div class='input-group'>
                            {input}
                            <div class='input-group-append'>
                                <span class='input-group-text'>
                                    <i class='la la-calendar-check-o'></i>
                                </span>
                            </div>
                        </div>
                        {error}
                	"])->textInput([
                        'maxlength' => true,
                        'datepicker' => 'true',
                        'autocomplete' => 'off'
                    ]
                ) ?>
            </div>
            <div class="col">
                <div class="form-group field-member-age required">
                    <label class="control-label" for="member-age">Age</label>
                    <input type="text" id="member-age" class="form-control" value="<?= $model->currentAge ?>" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <?= $form->field($model, 'birth_place')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<section class="mt-5">
    <p class="lead font-weight-bold">Contact</p>
    <div class="row">
        <div class="col">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
</section>

<div class="form-group mt-5">
    <?= Html::a('Back', Url::current(['step' => 'map']), [
        'class' => 'btn btn-secondary btn-lg'
    ]) ?>
    <?= Html::submitButton('Next', [
        'class' => 'btn btn-success btn-lg'
    ]) ?>
</div>
