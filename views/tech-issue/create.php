<?php

use app\models\search\TechIssueSearch;

/* @var $this yii\web\View */
/* @var $model app\models\TechIssue */

$this->title = 'Create Technical Issue';
$this->params['breadcrumbs'][] = ['label' => 'Technical Issues', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new TechIssueSearch();
?>
<div class="tech-issue-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>