<?php

use app\models\search\ScholarshipSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Scholarship */

$this->title = 'Update Scholarship: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Scholarships', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new ScholarshipSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="scholarship-update-page">
    <?= $this->render('_form-step', [
        'model' => $model,
        'tab' => $tab,
    ]) ?>
</div>