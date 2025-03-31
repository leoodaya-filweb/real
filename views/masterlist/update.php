<?php

use app\models\search\MasterlistSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Masterlist */

$this->title = 'Update Masterlist: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Masterlist', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new MasterlistSearch();
$this->params['showCreateButton'] = true; 
$this->params['wrapCard'] = false;
?>
<div class="masterlist-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>