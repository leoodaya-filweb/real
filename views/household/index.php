<?php

use app\helpers\Html;
use app\widgets\BulkAction;
use app\widgets\FilterColumn;
use app\widgets\Grid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\HouseholdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Households';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['showCreateButton'] = true; 
$this->params['showExportButton'] = true;
?>
<div class="household-index-page">
    <?= FilterColumn::widget(['searchModel' => $searchModel]) ?>
    <?= Html::beginForm(['bulk-action'], 'post'); ?>
        <?= Html::a('Import Household Data', ['import'], [
            'class' => 'btn btn-outline-primary ml-10 btn-sm btn-import-household',
        ]) ?>
        <?= BulkAction::widget(['searchModel' => $searchModel]) ?>
        
        <?= Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]); ?>
    <?= Html::endForm(); ?> 
</div>