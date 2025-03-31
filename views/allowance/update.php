<?php

use app\models\search\AllowanceSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Allowance */

$this->title = 'Update Allowance: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Allowances', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new AllowanceSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="allowance-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>