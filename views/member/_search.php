<?php

use app\helpers\App;
use app\models\CivilStatus;
use app\models\EducationalAttainment;
use app\models\Member;
use app\models\PwdType;
use app\models\Sex;
use app\widgets\DateRange;
use app\widgets\Filter;
use app\widgets\Pagination;
use app\widgets\RecordStatusFilter;
use app\widgets\Search;
use app\widgets\SearchButton;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\MemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => $model->searchAction,
    'method' => 'get',
    'id' => 'member-search-form'
]); ?>
    <?= Search::widget(['model' => $model]) ?>
    <?= DateRange::widget(['model' => $model]) ?>

    <?= Filter::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'head',
        'data' => App::keyMapParams('family_head'),
    ]) ?>


    <?= Filter::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'solo_parent',
        'data' => App::keyMapParams('solo_parent'),
    ]) ?>

    <?= Filter::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'solo_member',
        'title' => 'Solo Member',
        'data' => App::keyMapParams('solo_member'),
    ]) ?>

    <?= Filter::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'sex',
        'data' => Sex::dropdown(),
    ]) ?>

    <?= Filter::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'civil_status',
        'data' => CivilStatus::dropdown(),
    ]) ?>


    <?= Filter::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'pensioner',
        'data' => App::keyMapParams('pensioners'),
    ]) ?>

    <?= Filter::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'pwd',
        'title' => 'PWD',
        'data' => App::keyMapParams('pwd'),
    ]) ?>

    <?= Filter::widget([
        'form' => $form,
        'model' => $model,
        'title' => 'Disability',
        'attribute' => 'pwd_type',
        'data' => PwdType::dropdown(),
    ]) ?>

    <?= Filter::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'educational_attainment',
        'data' => EducationalAttainment::dropdown(),
    ]) ?>

    <?= Filter::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'voter',
        'title' => 'Voter',
        'data' => App::keyMapParams('voters'),
    ]) ?>

    <div class="mt-5"></div>

    <?= $form->field($model, 'age_from')->dropDownList(
        Member::ageDropdown(), [
            'prompt' => 'Select Age',
            'name' => 'age_from'
        ]
    ) ?>
    <?= $form->field($model, 'age_to')->dropDownList(
        Member::ageDropdown(), [
            'prompt' => 'Select Age',
            'name' => 'age_to'
        ]
    ) ?>

    <?= Filter::widget([
        'form' => $form,
        'model' => $model,
        'attribute' => 'living_status',
        'data' => App::keyMapParams('living_status'),
    ]) ?>

    <?= RecordStatusFilter::widget([
        'model' => $model,
        'form' => $form,
    ]) ?>
    <?= Pagination::widget([
        'model' => $model,
        'form' => $form,
    ]) ?>
    <?= SearchButton::widget() ?>
<?php ActiveForm::end(); ?>