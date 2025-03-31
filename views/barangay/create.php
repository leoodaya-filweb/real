<?php

use app\models\search\BarangaySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Barangay */

$this->title = 'Create Barangay';
$this->params['breadcrumbs'][] = ['label' => 'Barangays', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new BarangaySearch();
?>
<div class="barangay-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>