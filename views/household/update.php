<?php

use app\models\search\HouseholdSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Household */

$this->title = 'Update Household | ' . $step_forms[$step]['title'];
$this->params['breadcrumbs'][] = ['label' => 'Households', 'url' => $household->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $household->mainAttribute, 'url' => $household->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['breadcrumbs'][] = $step_forms[$step]['title'];
$this->params['searchModel'] = new HouseholdSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="household-update-page">
	<?= $this->render('_form', [
        'model' => $model,
		'step' => $step,
		'household' => $household,
		'step_forms' => $step_forms,
		'action' => 'update',
    ]) ?>
</div>