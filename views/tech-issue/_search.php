<?php

use app\helpers\App;
use app\widgets\DateRange;
use app\widgets\Filter;
use app\widgets\Pagination;
use app\widgets\RecordStatusFilter;
use app\widgets\Search;
use app\widgets\SearchButton;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\TechIssueSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => $model->searchAction,
    'method' => 'get',
    'id' => 'tech-issue-search-form'
]); ?>
    <?= Search::widget(['model' => $model]) ?>
    <?= DateRange::widget(['model' => $model]) ?>

    <?= Filter::widget([
        'data' => App::keyMapParams('tech_issue_types'),
        'attribute' => 'type',
        'model' => $model,
        'form' => $form,
    ]) ?>

    <?= Filter::widget([
        'data' => App::keyMapParams('tech_issue_status'),
        'attribute' => 'status',
        'model' => $model,
        'form' => $form,
    ]) ?>

    <?= Pagination::widget([
        'model' => $model,
        'form' => $form,
    ]) ?>
    <?= SearchButton::widget() ?>
<?php ActiveForm::end(); ?>