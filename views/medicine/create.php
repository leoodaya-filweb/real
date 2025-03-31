<?php

use app\models\search\MedicineSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Medicine */

$this->title = 'Create Medicine';
$this->params['breadcrumbs'][] = ['label' => 'Medicines', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new MedicineSearch();
?>
<div class="medicine-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>