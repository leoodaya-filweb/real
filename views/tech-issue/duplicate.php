<?php

use app\models\search\TechIssueSearch;

/* @var $this yii\web\View */
/* @var $model app\models\TechIssue */

$this->title = 'Duplicate Technical Issue: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Technical Issues', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new TechIssueSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="tech-issue-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>