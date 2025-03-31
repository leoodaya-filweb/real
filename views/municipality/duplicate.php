<?php

use app\models\search\MunicipalitySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Municipality */

$this->title = 'Duplicate Municipality: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Municipalities', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new MunicipalitySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="municipality-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>