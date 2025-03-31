<?php

use app\models\search\EmailSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Email */

$this->title = 'Duplicate Email: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Emails', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new EmailSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="email-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>