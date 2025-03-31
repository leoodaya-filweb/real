<?php

use app\models\search\ProvinceSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Province */

$this->title = 'Update Province: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Provinces', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new ProvinceSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="province-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>