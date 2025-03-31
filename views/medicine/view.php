<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\MedicineSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Medicine */

$this->title = 'Medicine: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Medicines', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new MedicineSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="medicine-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>