<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\EventCategory;
use app\widgets\ActiveForm;
use app\widgets\BootstrapSelect;
use app\widgets\ImageGallery;

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
CSS);
$this->registerJs(<<< JS
    // Demo 7
    $('#date_from').datetimepicker();
    $('#date_to').datetimepicker({
        useCurrent: false
    });

    $('#date_from').on('change.datetimepicker', function (e) {
        $('#date_to').datetimepicker('minDate', e.date);
    });
    $('#date_to').on('change.datetimepicker', function (e) {
        $('#date_from').datetimepicker('maxDate', e.date);
    });

    let brgyCheckbox = 'input[name="Event[barangay_ids][]"]';

    let numberWithCommas = function (x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    let computeBeneficiaries = function() {
        let _members = $(brgyCheckbox + ':checked').map(function () {return $(this).data('members')}).get(),
            _households = $(brgyCheckbox + ':checked').map(function () {return $(this).data('households')}).get(),
            totalMembers = _members.reduce((partialSum, a) => partialSum + a, 0),
            totalMembersHtml = (totalMembers)? "Members: <b class='text-success'>" + numberWithCommas(totalMembers) + '</b>': '',
            totalHouseholds = _households.reduce((partialSum, a) => partialSum + a, 0),
            totalHouseholdsHtml = (totalHouseholds)? "Households: <b class='text-success'>"+ numberWithCommas(totalHouseholds) + '</b>': '',

        html = [totalHouseholdsHtml, totalMembersHtml].filter(e => e).join(' | ');

        $('#total-beneficiaries').html(html);
    }

    let colorTd = function(isChecked, tr) {
        if (isChecked) {
            tr.addClass('border-class');
        }
        else {
            tr.removeClass('border-class');
        }
    }

    $('.checkbox-brgy').change(function() {
        let isChecked = $(this).is(':checked');

        if (isChecked) {
            $(brgyCheckbox).prop('checked', true);
        }
        else {
            $(brgyCheckbox).prop('checked', false);
        }
        computeBeneficiaries();
        colorTd(isChecked, $('#table-barangay tbody tr'));
    });

    $(brgyCheckbox).change(function() {
        computeBeneficiaries();
        colorTd($(this).is(':checked'), $(this).closest('tr'));
    });
JS);
?>
<?php $form = ActiveForm::begin(['id' => 'event-form']); ?>
    <div class="row">
        <div class="col-md-8">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Primary Details'
            ]); ?>
                <div class="row">
                    <div class="col">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        <?= BootstrapSelect::widget([
                            'form' => $form,
                            'model' => $model,
                            'attribute' => 'category',
                            'data' => EventCategory::dropdown(),
                        ]) ?>
                        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                        <?= BootstrapSelect::widget([
                            'form' => $form,
                            'model' => $model,
                            'attribute' => 'benificiary_type',
                            'data' => App::keyMapParams('benificiary_types'),
                        ]) ?>
                        <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
                        
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col">
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
                                    'data-target' => '#date_from'
                                ]) ?>
                            </div>
                            <div class="col">
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
                                    'data-target' => '#date_to'
                                ]) ?>
                            </div>
                        </div>
                        
                        <?= BootstrapSelect::widget([
                            'form' => $form,
                            'model' => $model,
                            'attribute' => 'status',
                            'prompt' => false,
                            'data' => App::keyMapParams('event_status'),
                        ]) ?>

                        <div class="text-center">
                            <?= Html::image($model->photo, ['w' => 200], [
                                'class' => 'img-thumbnail event-photo mt-7 mw-200'
                            ]) ?>
                            <div class="mt-7">
                                <?= ImageGallery::widget([
                                    'tag' => 'Event',
                                    'buttonTitle' => 'Choose Photo',
                                    'model' => $model,
                                    'attribute' => 'photo',
                                    'ajaxSuccess' => "
                                        if(s.status == 'success') {
                                            $('.event-photo').attr('src', s.src);
                                        }
                                    ",
                                ]) ?> 
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="form-group mt-10">
                    <hr>
                    <?= ActiveForm::buttons('lg') ?>
                </div>
            <?php $this->endContent(); ?>
        </div>

        <div class="col">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => <<< HTML
                    Beneficiaries 
                    <br>{$model->totalTags}
                HTML
            ]); ?>
            <table class="table table-head-solid table-bordered" id="table-barangay">
                <thead>
                    <tr>
                        <th>
                            <div class="checkbox-list">
                                <label class="checkbox">
                                    <input type="checkbox" class="checkbox-brgy">
                                    <span></span> Barangay Name
                                </label>
                            </div>
                            
                        </th>
                        <th>Households</th>
                        <th>Members</th>
                    </tr>
                </thead>
                <tbody>
                    <?= Html::foreach(App::setting('address')->barangays, function($b) use($model) {
                        $inp = Html::input('checkbox', 'Event[barangay_ids][]', $b->id, [
                            'data-members' => $b->totalMembers,
                            'data-households' => $b->totalHouseholds,
                            'checked' => in_array($b->id, $model->barangay_ids ?: [])
                        ]);
                        $tdClass = in_array($b->id, $model->barangay_ids ?: [])? 'border-class': '';
                        return <<< HTML
                            <tr class="{$tdClass}">
                                <td>
                                    <div class="checkbox-list">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-success">
                                            {$inp}
                                            <span></span> {$b->name}
                                        </label>
                                    </div>
                                </td>
                                <td>{$b->getTotalHouseholds(true)} </td>
                                <td>{$b->getTotalMembers(true)} </td>
                            </tr>
                        HTML;
                    }) ?>
                </tbody>
            </table>
                    
            <?php $this->endContent(); ?>
        </div>
    </div>
 
<?php ActiveForm::end(); ?>