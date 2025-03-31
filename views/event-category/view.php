<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\EventCategorySearch;

/* @var $this yii\web\View */
/* @var $model app\models\EventCategory */

$this->title = 'EventCategory: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Event Categories', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new EventCategorySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="event-category-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>