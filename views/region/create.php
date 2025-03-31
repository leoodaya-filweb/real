<?php

use app\models\search\RegionSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Region */

$this->title = 'Create Region';
$this->params['breadcrumbs'][] = ['label' => 'Regions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new RegionSearch();
?>
<div class="region-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>