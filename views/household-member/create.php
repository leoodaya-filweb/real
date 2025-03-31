<?php

use app\models\search\HouseholdMemberSearch;

/* @var $this yii\web\View */
/* @var $model app\models\HouseholdMember */

$this->title = 'Create Household Member';
$this->params['breadcrumbs'][] = ['label' => 'Household Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new HouseholdMemberSearch();
?>
<div class="household-member-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>