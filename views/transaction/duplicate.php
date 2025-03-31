<?php

use app\models\search\TransactionSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Duplicate Transaction: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new TransactionSearch();
$this->params['showCreateButton'] = true; 
$this->params['wrapCard'] = false;
?>
<div class="transaction-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
        'member' => $member,
        'withSlug' => $withSlug,
    ]) ?>
</div>