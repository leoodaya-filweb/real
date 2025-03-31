<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\CountrySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Country */

$this->title = 'Country: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new CountrySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="country-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>