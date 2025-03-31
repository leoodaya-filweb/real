<?php

use app\models\search\BudgetSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Budget */

$this->title = 'Duplicate Budget: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Budgets', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new BudgetSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="budget-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>