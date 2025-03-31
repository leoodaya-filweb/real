<?php

use app\models\search\ValueLabelSearch;

/* @var $this yii\web\View */
/* @var $model app\models\ValueLabel */

$this->title = 'Create Value Label';
$this->params['breadcrumbs'][] = ['label' => 'Value Labels', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new ValueLabelSearch();
?>
<div class="value-label-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>