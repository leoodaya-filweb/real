<?php

use app\helpers\Html;
use app\models\search\SocialPensionerSearch;
use app\widgets\Anchors;
use app\widgets\Detail;

/* @var $this yii\web\View */
/* @var $model app\models\SocialPensioner */

$this->title = 'Social Pensioner: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Social Pensioners', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new SocialPensionerSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="social-pensioner-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Html::a('Add to Masterlist', ['add-to-masterlist', 'slug' => $model->slug], [
        'class' => 'font-weight-bold btn btn-outline-success',
        'data-confirm' => 'Are you sure?',
        'data-method' => 'post'
    ]) ?>
    <div class="mt-10"></div>
    <?= $model->detailView ?>
</div>