<?php

use app\helpers\Html;
use app\widgets\BulkAction;
use app\widgets\FilterColumn;
use app\widgets\Grid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Members';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['showCreateButton'] = true; 
$this->params['showExportButton'] = true;
?>



<div class="member-index-page">
    

    <?= FilterColumn::widget(['searchModel' => $searchModel]) ?>
    <?= Html::beginForm(['bulk-action'], 'post'); ?>
        <?= Html::a('Import Members Data', ['import'], [
            'class' => 'btn btn-outline-primary ml-10 btn-sm btn-import-member',
        ]) ?>
        <?= BulkAction::widget(['searchModel' => $searchModel]) ?>
        
        <?= Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'template' => ['view', 'update', 'duplicate', 'delete', 'download-qr-code'],
            'rowOptions' => function ($model, $index, $widget, $grid){
			           if($model->head==1){
					   return ['style' => 'background-color:#f1f1f1;']; 
			           }else{
			           return [];  
			           }
                   }
            
        ]); ?>
    <?= Html::endForm(); ?> 
</div>