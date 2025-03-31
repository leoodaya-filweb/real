<?php

use app\models\search\CountrySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Country */

$this->title = 'Create Country';
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Create';
$this->params['searchModel'] = new CountrySearch();
?>
<div class="country-create-page">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>