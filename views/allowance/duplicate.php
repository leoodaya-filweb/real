<?php

use app\models\search\AllowanceSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Allowance */

$this->title = 'Duplicate Allowance: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Allowances', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new AllowanceSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="allowance-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>