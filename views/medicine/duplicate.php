<?php

use app\models\search\MedicineSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Medicine */

$this->title = 'Duplicate Medicine: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Medicines', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new MedicineSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="medicine-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>