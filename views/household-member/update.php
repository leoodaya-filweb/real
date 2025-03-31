<?php

use app\models\search\HouseholdMemberSearch;

/* @var $this yii\web\View */
/* @var $model app\models\HouseholdMember */

$this->title = 'Update Household Member: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Household Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new HouseholdMemberSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="household-member-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>