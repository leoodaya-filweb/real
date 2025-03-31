<?php

use app\models\search\TransactionLogSearch;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionLog */

$this->title = 'Update Transaction Log: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Transaction Logs', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new TransactionLogSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="transaction-log-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>