<?php

use app\helpers\App;
use app\models\Province;
use app\models\Region;
use app\widgets\DateRange;
use app\widgets\Filter;
use app\widgets\Pagination;
use app\widgets\RecordStatusFilter;
use app\widgets\Search;
use app\widgets\SearchButton;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\MunicipalitySearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => $model->searchAction,
    'method' => 'get',
    'id' => 'municipality-search-form'
]); ?>
    <?= Search::widget(['model' => $model]) ?>
    <?= DateRange::widget(['model' => $model]) ?>
  
    <?= Filter::widget([
        'data' => Province::dropdown(),
        'title' => 'Province',
        'attribute' => 'province_id',
        'model' => $model,
        'form' => $form,
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