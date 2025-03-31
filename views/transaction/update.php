<?php

use app\models\search\TransactionSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Update Transaction: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new TransactionSearch();
$this->params['showCreateButton'] = true; 
$this->params['wrapCard'] = false;
?>
<div class="transaction-update-page container">
	<?= $this->render('_form', [
        'model' => $model,
        'member' => $member,
        'withSlug' => $withSlug,
    ]) ?>
</div>