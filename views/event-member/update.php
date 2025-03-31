<?php

use app\models\search\EventMemberSearch;

/* @var $this yii\web\View */
/* @var $model app\models\EventMember */

$this->title = 'Update Event Member: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Event Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new EventMemberSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="event-member-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>