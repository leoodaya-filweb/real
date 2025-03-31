<?php

use app\models\search\MedicineSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Medicine */

$this->title = 'Update Medicine: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Medicines', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new MedicineSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="medicine-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>