<?php

use app\models\search\ProvinceSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Province */

$this->title = 'Create Province';
$this->params['breadcrumbs'][] = ['label' => 'Provinces', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new ProvinceSearch();
?>
<div class="province-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>