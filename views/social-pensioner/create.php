<?php

use app\models\search\SocialPensionerSearch;

/* @var $this yii\web\View */
/* @var $model app\models\SocialPensioner */

$this->title = 'Create Social Pensioner';
$this->params['breadcrumbs'][] = ['label' => 'Social Pensioners', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new SocialPensionerSearch();
$this->params['wrapCard'] = false;
?>
<div class="social-pensioner-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>