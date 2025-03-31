<?php

use app\helpers\Html;
use app\models\search\MasterlistSearch;
use app\widgets\Anchors;
use app\widgets\Detail;

/* @var $this yii\web\View */
/* @var $model app\models\Masterlist */

$this->title = 'Masterlist: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Masterlist', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new MasterlistSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="masterlist-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Html::a('Remove from Masterlist', ['remove-from-masterlist', 'slug' => $model->slug], [
        'class' => 'font-weight-bold btn btn-outline-danger',
        'data-confirm' => 'Are you sure?',
        'data-method' => 'post'
    ]) ?>
    <div class="mt-10"></div>
    <?= $model->detailView ?>
</div>