<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\MunicipalitySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Municipality */

$this->title = 'Municipality: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Municipalities', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new MunicipalitySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="municipality-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>