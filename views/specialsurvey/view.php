<?php

use app\widgets\Anchors;
use app\widgets\Detail;
use app\models\search\SpecialsurveySearch;

/* @var $this yii\web\View */
/* @var $model app\models\Specialsurvey */

$this->title = 'Survey: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Surveys', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new SpecialsurveySearch();
$this->params['showCreateButton'] = true; 
?>
<div class="specialsurvey-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 
    <?= Detail::widget(['model' => $model]) ?>
</div>