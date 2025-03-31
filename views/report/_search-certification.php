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
        'attribute' => 'transaction_type',
        'title' => 'Transaction Type',
        'data' => [
            Transaction::SENIOR_CITIZEN_ID_APPLICATION => 'Senior Citizen ID Application',
            Transaction::CERTIFICATE_OF_INDIGENCY => 'Certificate of Indigency',
            Transaction::FINANCIAL_CERTIFICATION => 'Financial Certification',
            Transaction::SOCIAL_CASE_STUDY_REPORT => 'Social Case Study Report',
            Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING => 'Certificate of Marriage Counseling',
            Transaction::CERTIFICATE_OF_COMPLIANCE => 'Certificate of Counseling',
            Transaction::CERTIFICATE_OF_APPARENT_DISABILITY => 'Certificate of Apparent Disability',
        ],
    ]) ?>

    <?= Filter::widget([
        'model' => $model,
        'form' => $form,
        'attribute' => 'status',
        'title' => 'Status',
        'data' => Transaction::filterStatus(),
    ]) ?>


    <?= Pagination::widget([
        'model' => $model,
        'form' => $form,
    ]) ?>
    <?= SearchButton::widget() ?>
<?php ActiveForm::end(); ?>