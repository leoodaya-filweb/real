<?php

use app\models\search\EventSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = 'Duplicate Event: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new EventSearch();
$this->params['showCreateButton'] = true; 
$this->params['wrapCard'] = false;
?>
<div class="event-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>