<?php

use app\models\search\EventCategorySearch;

/* @var $this yii\web\View */
/* @var $model app\models\EventCategory */

$this->title = 'Duplicate EventCategory: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Event Categories', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new EventCategorySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="event-category-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>