<?php

use app\models\search\ScholarshipSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Scholarship */

$this->title = 'Duplicate Scholarship: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Scholarships', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new ScholarshipSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="scholarship-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>