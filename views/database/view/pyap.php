<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\search\DatabaseSearch;
use app\widgets\Anchors;
use app\widgets\DatabaseReport;
use app\widgets\Detail;
use app\widgets\PyapForm;

/* @var $this yii\web\View */
/* @var $model app\models\Database */

$this->title = 'Database: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Database: Priority Sectors', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => $model->prioritySectorLabel, 'url' => $model->prioritySectorIndexUrl];
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
    <ul class="nav nav-tabs nav-bold nav-tabs-line">
        <li class="nav-item" data-tab="main">
            <a class="nav-link active" data-toggle="tab" href="#tab-details">
                <span class="nav-icon"><i class="flaticon2-chat-1"></i></span>
                <span class="nav-text">Details</span>
            </a>
        </li>
        <li class="nav-item" data-tab="main">
            <a class="nav-link" data-toggle="tab" href="#tab-id">
                <span class="nav-icon"><i class="far fa-address-card"></i></span>
                <span class="nav-text">Identification Cards</span>
            </a>
        </li>

        <li class="nav-item" data-tab="main">
            <a class="nav-link" data-toggle="tab" href="#tab-documents">
                <span class="nav-icon"><i class="far fa-file-alt"></i></span>
                <span class="nav-text">Other Documents</span>
            </a>
        </li>
        <li class="nav-item" data-tab="role-access">
            <a class="nav-link" data-toggle="tab" href="#tab-form">
                <span class="nav-icon"><i class="fas fa-print"></i></span>
                <span class="nav-text">
                    Membership Information Sheet
                </span>
            </a>
        </li>
    </ul>

     <div class="tab-content pt-5">
        <div class="tab-pane fade show active" id="tab-details" role="tabpanel" aria-labelledby="tab-details">
            <h3 class="card-label text-warning text-uppercase">Main Information</h3>
            <?= Detail::widget([
                'model' => $model,
                'attributes' => $model->mainInformationColumns
            ]) ?>

            <h3 class="card-label text-warning text-uppercase mt-10">I. Identifying Data (Pagkakakilanlan)</h3>
            <?= Detail::widget([
                'model' => $model,
                'attributes' => $model->primaryInformationColumns
            ]) ?>

            <h3 class="card-label text-warning text-uppercase mt-10">Address</h3>
            <?= Detail::widget([
                'model' => $model,
                'attributes' => $model->addressColumns
            ]) ?>

            <h3 class="card-label text-warning text-uppercase mt-10">II. Siblings (Mga Kapatid)</h3>
            <?= DatabaseReport::widget([
                'model' => $model,
                'template' => 'siblings'
            ]) ?>

            <h3 class="card-label text-warning text-uppercase mt-10">III. Paaralang Huling Pinasukan</h3>
            <?= Detail::widget([
                'model' => $model,
                'attributes' => $model->educationAndSourceOfIncomeColumns
            ]) ?>

            <h3 class="card-label text-warning text-uppercase mt-10">IV. Mga Kakayanan o skills</h3>
            <?= $model->skills ? App::formatter('asUl', $model->skills) : 'None' ?>

            <h3 class="card-label text-warning text-uppercase mt-10">V. Mga interes at libangan</h3>
            <?= $model->interests ? App::formatter('asUl', $model->interests) : 'None' ?>

            <h3 class="card-label text-warning text-uppercase mt-10">VI. Karanasan sa Paggawa o Trabaho</h3>
            <?= DatabaseReport::widget([
                'model' => $model,
                'template' => 'work-experience'
            ]) ?>

            <h3 class="card-label text-warning text-uppercase mt-10">VII. Kasapian sa mga Organisasyon, Samahan, etc.</h3>
            <?= DatabaseReport::widget([
                'model' => $model,
                'template' => 'organizations'
            ]) ?> 
        </div>

        <div class="tab-pane fade" id="tab-id" role="tabpanel" aria-labelledby="tab-form">
           <?php $this->beginContent('@app/views/file/_row-header.php'); ?>
                <?= Html::if(($files = $model->identificationCards) != null, function() use($files) {
                    return Html::foreach($files, function($file) {
                        $img = $this->render('/file/_row-filename', [
                            'model' => $file
                        ]);
                        return <<< HTML
                            <tr>
                                <td> {$img} </td>
                                <td class="text-center" width="100">
                                    <a href="{$file->viewerUrl}" target="_blank" class="btn btn-light-primary btn-sm font-weight-bolder">
                                        <i class="fa fa-eye"></i> View
                                    </a>

                                     <a href="{$file->downloadUrl}" class="btn btn-light-success btn-sm font-weight-bolder">
                                        <i class="fa fa-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                        HTML;
                    });
                }) ?>
            <?php $this->endContent(); ?>
        </div> 

        <div class="tab-pane fade" id="tab-documents" role="tabpanel" aria-labelledby="tab-form">
           <?php $this->beginContent('@app/views/file/_row-header.php', [
                'tableId' => 'tbl-documents'
           ]); ?>
                <?= Html::if(($files = $model->documentFiles) != null, function() use($files) {
                    return Html::foreach($files, function($file) {
                        $img = $this->render('/file/_row-filename', [
                            'model' => $file
                        ]);
                        return <<< HTML
                            <tr>
                                <td> {$img} </td>
                                <td class="text-center" width="100">
                                    <a href="{$file->viewerUrl}" target="_blank" class="btn btn-light-primary btn-sm font-weight-bolder">
                                        <i class="fa fa-eye"></i> View
                                    </a>

                                     <a href="{$file->downloadUrl}" class="btn btn-light-success btn-sm font-weight-bolder">
                                        <i class="fa fa-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                        HTML;
                    });
                }) ?>
            <?php $this->endContent(); ?>
        </div> 

        <div class="tab-pane fade" id="tab-form" role="tabpanel" aria-labelledby="tab-form">
            <?= PyapForm::widget([
                'model' => $model
            ]) ?>
        </div> 
    </div>
</div>