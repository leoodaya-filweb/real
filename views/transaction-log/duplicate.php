<?php

use app\models\search\TransactionLogSearch;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionLog */

$this->title = 'Duplicate Transaction Log: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Transaction Logs', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new TransactionLogSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="transaction-log-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>