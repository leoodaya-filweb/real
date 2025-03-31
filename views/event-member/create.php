<?php

use app\models\search\EventMemberSearch;

/* @var $this yii\web\View */
/* @var $model app\models\EventMember */

$this->title = 'Create Event Member';
$this->params['breadcrumbs'][] = ['label' => 'Event Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new EventMemberSearch();
?>
<div class="event-member-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>