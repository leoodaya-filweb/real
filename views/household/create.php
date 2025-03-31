<?php

use app\models\Household;
use app\models\search\HouseholdSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Household */

$this->title = 'Create Household | ' . $step_forms[$step]['title'];
$this->params['breadcrumbs'][] = ['label' => 'Households', 'url' => $household->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['breadcrumbs'][] = $step_forms[$step]['title'];
$this->params['searchModel'] = new HouseholdSearch();
?>
<div class="household-create-page">
	<?= $this->render('_form', [
		'model' => $model,
		'step' => $step,
		'household' => $household,
		'step_forms' => $step_forms,
		'action' => $action ?? 'create',
	]) ?>
</div>