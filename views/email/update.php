<?php

use app\models\search\EmailSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Email */

$this->title = 'Update Email: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Emails', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new EmailSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="email-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>