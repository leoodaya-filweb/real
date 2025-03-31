<?php

use app\models\search\MunicipalitySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Municipality */

$this->title = 'Update Municipality: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Municipalities', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new MunicipalitySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="municipality-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>