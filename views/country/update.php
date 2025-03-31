<?php

use app\models\search\CountrySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Country */

$this->title = 'Update Country: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->mainAttribute, 'url' => $model->viewUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new CountrySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="country-update-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>