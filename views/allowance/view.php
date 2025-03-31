<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\AllowanceSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Allowance */

$this->title = 'Allowance: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Allowances', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new AllowanceSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="allowance-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>