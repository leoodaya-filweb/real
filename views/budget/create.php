<?php

use app\models\search\BudgetSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Budget */

$this->title = 'Create Budget';
$this->params['breadcrumbs'][] = ['label' => 'Budgets', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new BudgetSearch();
?>
<div class="budget-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>