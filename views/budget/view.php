<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\BudgetSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Budget */

$this->title = 'Budget: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Budgets', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new BudgetSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="budget-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>