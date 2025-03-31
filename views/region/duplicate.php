<?php

use app\models\search\RegionSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Region */

$this->title = 'Duplicate Region: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Regions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new RegionSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="region-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>