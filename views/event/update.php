<?php

use app\models\search\EventSearch;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = 'Update Event: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new EventSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="event-update-page">
	<?= $this->render('_form', [
        'model' => $model,
        'tab' => $tab,
    ]) ?>
</div>