<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\EmailSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Email */

$this->title = 'Email: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Emails', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new EmailSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="email-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>