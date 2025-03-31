<?php

use app\models\search\EventMemberSearch;

/* @var $this yii\web\View */
/* @var $model app\models\EventMember */

$this->title = 'Duplicate Event Member: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Event Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new EventMemberSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="event-member-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>