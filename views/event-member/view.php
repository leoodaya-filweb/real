<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\EventMemberSearch;

/* @var $this yii\web\View */
/* @var $model app\models\EventMember */

$this->title = 'Event Member: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Event Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new EventMemberSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="event-member-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>