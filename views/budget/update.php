<?php

use app\models\search\BudgetSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Budget */

$this->title = 'Update Budget: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Budgets', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new BudgetSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="budget-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>