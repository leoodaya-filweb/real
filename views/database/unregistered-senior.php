<?php

use app\helpers\Html;
use app\widgets\BulkAction;
use app\widgets\FilterColumn;
use app\widgets\Grid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DatabaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unregistered Senior';
$this->params['breadcrumbs'][] = ['label' => 'Database', 'url' => ['database/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['showCreateButton'] = false; 
$this->params['showExportButton'] = true;
$this->params['activeMenuLink'] = '/database/unregistered-senior';
$this->params['createTitle'] = 'Create Database Entry';
$this->params['xlsxUrl'] = ['database/export-xlsx-unregistered-senior'];
$this->params['csvUrl'] = ['database/csv-unregistered-senior'];
?>

<div class="unregistered-senior-index-page">
    <?= FilterColumn::widget(['searchModel' => $searchModel]) ?>
    <?= Html::beginForm(['bulk-action'], 'post'); ?>
        <?= BulkAction::widget(['searchModel' => $searchModel]) ?>
        
        <?= Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $searchModel->unregisteredSeniorColumns,
            'withActionColumn' => false,
        ]); ?>
    <?= Html::endForm(); ?> 
</div>