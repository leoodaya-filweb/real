<?php

use app\helpers\Html;
use app\models\search\TransactionSearch;
use app\widgets\Anchors;
use app\widgets\Detail;
use app\widgets\Timeline;
use app\widgets\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Transaction: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new TransactionSearch();
$this->params['showCreateButton'] = true; 
$this->params['wrapCard'] = false; 
?>
<div class="transaction-view-page">
    <div class="mb-5">
        <?= Anchors::widget([
            'names' => ['update', 'duplicate', 'delete', 'log'], 
            'model' => $model
        ]) ?> 
    </div>
    <div class="row">
        <div class="col-md-4">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Primary Details',
            ]); ?>
                <?= Detail::widget(['model' => $model]) ?>
            <?php $this->endContent(); ?>
        </div>
        <div class="col-md-4">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Documents',
            ]); ?>
                <?= Html::foreach($model->imageFiles, function($file) {
                    return <<< HTML
                        <div class="image-input image-input-outline file-container">
                            <div class="image-input-wrapper" style="background-image: url({$file->getUrlImage(['w' => 200])}); width: 200px"></div>
                            <a title="View: {$file->name}" target="_blank" href="{$file->urlImage}">
                                    <p class="badge badge-secondary mb-1">{$file->truncatedName}</p>
                                </a>
                        </div>
                    HTML;
                })?>
            <?php $this->endContent(); ?>
        </div>

        <div class="col-md-4">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Transaction Logs'
            ]); ?>
                <?= Timeline::widget(['model' => $model]) ?>
            <?php $this->endContent(); ?>
        </div>
    </div>
    
    <div class="card card-custom">
        <div class="card-header card-header-tabs-line">
            <div class="card-title">
                <h3 class="card-label">Reports</h3>
            </div>
            <div class="card-toolbar">
                <ul class="nav nav-tabs nav-bold nav-tabs-line">
                    <?= Html::if($model->isMedical, <<< HTML
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#white-card">
                                White Card
                            </a>
                        </li>
                    HTML) ?>

                    <li class="nav-item">
                        <a class="nav-link <?= ($model->isMedical)? '': 'active' ?>" data-toggle="tab" href="#general-intake-sheet">
                            General Intake Sheet
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#obligation-request-form">
                            Obligation Request Form
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#petty-cah-voucher">
                            Petty Cash Voucher
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">

                <?= Html::if($model->isMedical, function() use($model) {
                    return <<< HTML
                        <div class="tab-pane fade active show" id="white-card" role="tabpanel" aria-labelledby="white-card">
                            {$model->whiteCardEditor}
                        </div>
                    HTML;
                }) ?>

                <div class="tab-pane fade <?= ($model->isMedical)? '': 'active show' ?>" id="general-intake-sheet" role="tabpanel" aria-labelledby="general-intake-sheet">
                    General Intake Sheet
                </div>
                <div class="tab-pane fade" id="obligation-request-form" role="tabpanel" aria-labelledby="obligation-request-form">
                    Obligation Request Form
                </div>
                <div class="tab-pane fade" id="petty-cah-voucher" role="tabpanel" aria-labelledby="petty-cah-voucher">
                    Petty Cash Voucher
                </div>
            </div>
        </div>
    </div>
</div>

