<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\RegionSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Region */

$this->title = 'Region: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Regions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new RegionSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="region-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>