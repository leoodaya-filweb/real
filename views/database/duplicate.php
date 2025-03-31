<?php

use app\models\search\DatabaseSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Database */

$this->title = 'Duplicate Database: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Databases', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new DatabaseSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="database-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>