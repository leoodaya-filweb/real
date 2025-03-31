<?php

use app\models\search\HouseholdSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Household */

$this->title = 'Duplicate Household: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Households', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new HouseholdSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="household-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>