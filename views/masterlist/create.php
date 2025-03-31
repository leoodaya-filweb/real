<?php

use app\models\search\MasterlistSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Masterlist */

$this->title = 'Create Masterlist';
$this->params['breadcrumbs'][] = ['label' => 'Masterlist', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new MasterlistSearch();
$this->params['wrapCard'] = false;
?>
<div class="masterlist-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>