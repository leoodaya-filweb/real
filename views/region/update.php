<?php

use app\models\search\RegionSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Region */

$this->title = 'Update Region: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Regions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new RegionSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="region-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>