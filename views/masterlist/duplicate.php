<?php

use app\models\search\MasterlistSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Masterlist */

$this->title = 'Duplicate Masterlist: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Masterlist', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new MasterlistSearch();
$this->params['showCreateButton'] = true; 
$this->params['wrapCard'] = false;
?>
<div class="masterlist-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>