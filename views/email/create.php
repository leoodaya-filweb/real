<?php

use app\models\search\EmailSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Email */

$this->title = 'Create Email';
$this->params['breadcrumbs'][] = ['label' => 'Emails', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new EmailSearch();
?>
<div class="email-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>