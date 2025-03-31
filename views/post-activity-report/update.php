<?php

use app\models\search\PostActivityReportSearch;

/* @var $this yii\web\View */
/* @var $model app\models\PostActivityReport */

$this->title = 'Update Post Activity Report: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Post Activity Reports', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new PostActivityReportSearch();
$this->params['showCreateButton'] = true; 
?>
 <div class="post-activity-report-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
 </div>
