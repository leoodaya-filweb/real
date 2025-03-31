<?php

use app\models\search\ProvinceSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Province */

$this->title = 'Duplicate Province: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Provinces', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new ProvinceSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="province-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>