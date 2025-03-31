<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\ValueLabelSearch;

/* @var $this yii\web\View */
/* @var $model app\models\ValueLabel */

$this->title = 'Value Label: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Value Labels', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new ValueLabelSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="value-label-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>