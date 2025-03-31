<?php

use app\models\search\MunicipalitySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Municipality */

$this->title = 'Create Municipality';
$this->params['breadcrumbs'][] = ['label' => 'Municipalities', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new MunicipalitySearch();
?>
<div class="municipality-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>