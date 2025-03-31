<?php

use app\models\search\BarangaySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Barangay */

$this->title = 'Duplicate Barangay: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Barangays', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new BarangaySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="barangay-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>