<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\HouseholdMemberSearch;

/* @var $this yii\web\View */
/* @var $model app\models\HouseholdMember */

$this->title = 'Household Member: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Household Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new HouseholdMemberSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="household-member-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>