<?php

use app\helpers\Html;
use app\models\search\PostActivityReportSearch;
use app\widgets\Anchors;
use app\widgets\Detail;

/* @var $this yii\web\View */
/* @var $model app\models\PostActivityReport */

$this->title = 'Post Activity Report: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Post Activity Reports', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new PostActivityReportSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="post-activity-report-view-page">
    <?= Anchors::widget([
    	'names' => ['update', 'duplicate', 'delete', 'log'], 
    	'model' => $model
    ]) ?> 

    <?= Html::popupCenter('Print', $model->printableUrl, [
        'class' => 'btn btn-secondary font-weight-bold',
    ]) ?>

    <div class="mb-10"></div>
    <div class="d-flex justify-content-center">
        <div></div>
        <div style="border: 1px solid #ccc; padding: 2rem;overflow: auto;width: 100%;max-width: 9in;">
            <?= $this->render('printable', [
                'model' => $model, 
                'style' => 'max-width: 8.5in;'
            ]) ?>
        </div>
        <div></div>
    </div>
</div>