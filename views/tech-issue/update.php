<?php

use app\models\search\TechIssueSearch;

/* @var $this yii\web\View */
/* @var $model app\models\TechIssue */

$this->title = 'Update Technical Issue: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Technical Issues', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new TechIssueSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="tech-issue-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>