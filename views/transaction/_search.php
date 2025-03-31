<?php

use app\helpers\App;
use app\models\Transaction;
use app\widgets\DateRange;
use app\widgets\Filter;
use app\widgets\Pagination;
use app\widgets\RecordStatusFilter;
use app\widgets\Search;
use app\widgets\SearchButton;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\TransactionSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => $model->searchAction,
    'method' => 'get',
    'id' => 'transaction-search-form'
]); ?>
    <?= Search::widget(['model' => $model]) ?>
    <?= DateRange::widget(['model' => $model]) ?>

    <?= Filter::widget([
        'model' => $model,
        'form' => $form,
        'attribute' => 'transaction_type',
        'title' => 'Transaction Type',
        'data' => App::keyMapParams('transaction_types'),
    ]) ?>

    <?= Filter::widget([
        'model' => $model,
        'form' => $form,
        'attribute' => 'status',
        'title' => 'Status',
        'data' => Transaction::filterStatus(),
    ]) ?>

    <?= Filter::widget([
        'model' => $model,
        'form' => $form,
        'attribute' => 'emergency_welfare_program',
        'title' => 'Emergency Program',
        'data' => App::keyMapParams('emergency_welfare_programs'),
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