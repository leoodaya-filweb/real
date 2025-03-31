<?php

use app\models\search\BarangaySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Barangay */

$this->title = 'Update Barangay: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Barangays', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new BarangaySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="barangay-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>