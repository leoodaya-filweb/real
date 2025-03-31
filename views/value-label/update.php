<?php

use app\models\search\ValueLabelSearch;

/* @var $this yii\web\View */
/* @var $model app\models\ValueLabel */

$this->title = 'Update Value Label: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Value Labels', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new ValueLabelSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="value-label-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>