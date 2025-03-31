<?php

use app\models\search\TransactionLogSearch;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionLog */

$this->title = 'Create Transaction Log';
$this->params['breadcrumbs'][] = ['label' => 'Transaction Logs', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new TransactionLogSearch();
?>
<div class="transaction-log-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>