<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\TransactionLogSearch;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionLog */

$this->title = 'Transaction Log: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Transaction Logs', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new TransactionLogSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="transaction-log-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>