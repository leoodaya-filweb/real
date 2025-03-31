<?php

use app\models\search\AllowanceSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Allowance */

$this->title = 'Create Allowance';
$this->params['breadcrumbs'][] = ['label' => 'Allowances', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new AllowanceSearch();
?>
<div class="allowance-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>