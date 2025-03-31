<?php

use app\models\search\EventCategorySearch;

/* @var $this yii\web\View */
/* @var $model app\models\EventCategory */

$this->title = 'Create EventCategory';
$this->params['breadcrumbs'][] = ['label' => 'Event Categories', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new EventCategorySearch();
?>
<div class="event-category-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>