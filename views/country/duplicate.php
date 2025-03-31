<?php

use app\models\search\CountrySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Country */

$this->title = 'Duplicate Country: ' . $originalModel->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $originalModel->mainAttribute, 'url' => $originalModel->viewUrl];
$this->params['breadcrumbs'][] = 'Duplicate';
$this->params['searchModel'] = new CountrySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="country-duplicate-page">
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>