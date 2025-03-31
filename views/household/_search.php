<?php

use app\helpers\App;
use app\widgets\Filter;
use app\widgets\Search;
use app\models\Barangay;
use app\widgets\DateRange;
use app\widgets\Pagination;
use yii\widgets\ActiveForm;
use app\widgets\SearchButton;
use app\widgets\RecordStatusFilter;

/* @var $this yii\web\View */
/* @var $model app\models\search\HouseholdSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => $model->searchAction,
    'method' => 'get',
    'id' => 'household-search-form'
]); ?>
    <?= Search::widget(['model' => $model]) ?>
    <?= DateRange::widget(['model' => $model]) ?>
    <?= Filter::widget([
        'form' => $form,
        'model' => $model,
        'title' => 'Barangay',
        'attribute' => 'barangay_id',
        'data' => Barangay::dropdown(),
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