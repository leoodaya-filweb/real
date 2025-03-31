<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\BarangaySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Barangay */

$this->title = 'Barangay: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Barangays', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new BarangaySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="barangay-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>