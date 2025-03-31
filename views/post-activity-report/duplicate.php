<?php

use app\models\search\PostActivityReportSearch;

/* @var $this yii\web\View */
/* @var $model app\models\PostActivityReport */

$this->title = 'Duplicate Post Activity Report: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Post Activity Reports', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new PostActivityReportSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="post-activity-report-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>