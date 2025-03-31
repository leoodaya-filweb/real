<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\DatabaseSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Database */

$this->title = 'Database: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Databases', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new DatabaseSearch();
$this->params['showCreateButton'] = false; 
$this->params['activeMenuLink'] = '/database';
$this->params['headerButtons'] = implode(' ', [
    Anchors::widget([
        'names' => ['update', 'duplicate', 'delete', 'log'], 
        'model' => $model
    ]),
    $model->headerCreateButton
]);
?>
<div class="database-view-page">
    <?php /* Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) */ ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>