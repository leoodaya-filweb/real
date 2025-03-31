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
        'attribute' => 'status',
        'title' => 'Status',
        'data' => Transaction::filterStatus(),
    ]) ?>

    <?= Filter::widget([
        'model' => $model,
        'form' => $form,
        'attribute' => 'emergency_welfare_program',
        'title' => 'Emergency Welfare Program',
        'data' => [
            Transaction::AICS_MEDICAL => 'Medical (AICS)',
            Transaction::AICS_LABORATORY_REQUEST => 'Laboratory Request (AICS)',
            Transaction::BALIK_PROBINSYA_PROGRAM => 'Balik Probinsya',
        ],
    ]) ?>

    <?= Filter::widget([
        'model' => $model,
        'form' => $form,
        'attribute' => 'transaction_type',
        'title' => 'Transaction Type',
        'data' => [
            Transaction::DEATH_ASSISTANCE => 'Death Assistance',
            Transaction::SOCIAL_PENSION => 'Social Pension',
        ],
    ]) ?>

    <?= Pagination::widget([
        'model' => $model,
        'form' => $form,
    ]) ?>
    <?= SearchButton::widget() ?>
<?php ActiveForm::end(); ?>