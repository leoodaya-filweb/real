<?php

use app\models\search\HouseholdMemberSearch;

/* @var $this yii\web\View */
/* @var $model app\models\HouseholdMember */

$this->title = 'Duplicate Household Member: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Household Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new HouseholdMemberSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="household-member-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>