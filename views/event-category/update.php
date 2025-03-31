<?php

use app\models\search\EventCategorySearch;

/* @var $this yii\web\View */
/* @var $model app\models\EventCategory */

$this->title = 'Update EventCategory: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Event Categories', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new EventCategorySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="event-category-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>