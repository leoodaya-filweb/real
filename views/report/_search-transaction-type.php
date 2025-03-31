<?php

use app\helpers\App;
use app\widgets\DateRange;
use app\widgets\Filter;
use app\widgets\Pagination;
use app\widgets\RecordStatusFilter;
use app\widgets\Search;
use app\widgets\SearchButton;
use yii\widgets\ActiveForm;
use app\models\Transaction;

?>
<?php $form = ActiveForm::begin([
    'action' => $model->searchAction,
    'method' => 'get',
    'id' => 'transaction-search-form'
]); ?>
    <?= Search::widget([
        'model' => $model,
        'searchKeywordUrl' => $this->params['searchKeywordUrl'] ?? ''
    ]) ?>
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

    <?= Pagination::widget([
        'model' => $model,
        'form' => $form,
    ]) ?>
    <?= SearchButton::widget() ?>
<?php ActiveForm::end(); ?>