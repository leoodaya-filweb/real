<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\ProvinceSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Province */

$this->title = 'Province: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Provinces', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new ProvinceSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="province-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>