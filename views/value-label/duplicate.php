<?php

use app\models\search\ValueLabelSearch;

/* @var $this yii\web\View */
/* @var $model app\models\ValueLabel */

$this->title = 'Duplicate Value Label: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Value Labels', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new ValueLabelSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="value-label-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>