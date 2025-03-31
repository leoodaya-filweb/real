<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Event;
use app\widgets\ActiveForm;
use app\widgets\AppIcon;
use app\widgets\Iconbox;

$this->registerCss(<<< CSS
    .app-iconbox {
        box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px 0
    }
    
    #table-file_filter {
        text-align: right;
    }
    #table-file_filter label,
    #table-file_length label {
        display: inline-flex;
    }

    #table-file_filter input {
        margin-top: -5px;
        margin-left: 5px;
    }
    #table-file_length select{
        margin-top: -5px;
        margin-left: 5px;
        margin-right: 5px;
    }
    #table-file_paginate {
        float: right;
    }
CSS);
$this->registerJs(<<< JS
    $('#table-file').DataTable({
        pageLength: 5,
        order: [[0, 'desc']]
    });
JS);

?>

<h4 class="mb-10 font-weight-bolder text-dark mt-3">
    <?= $tabData['title'] ?>
</h4>



<h6 class="font-weight-bolder mb-3">
    General Information:
    <?= Html::a('<i class="fa fa-edit text-warning" title="Edit" data-toggle="tooltip"></i>', [
        App::actionID(), 
        'token' => App::get('token'), 
        'tab' => 'general-information'
    ]) ?>
</h6>

<div class="row">
    <div class="col-md-6">
        <div class="text-dark-50 line-height-lg">
            <div> <b>Name:</b> <?= $model->name ?> </div>
            <div> <b>Date:</b>  
                <ul style="margin-bottom: 0rem;">
                    <li><b>From:</b> <?= date('F d, Y h:i:s A', strtotime($model->date_from)) ?></li>
                    <li><b>To:</b> <?= date('F d, Y h:i:s A', strtotime($model->date_to)) ?></li>
                </ul>
            </div>
            <div> <b>Fund:</b> <?= $model->fundLabel ?> </div>
            <div> <b>No of Pensioner:</b> <?= App::formatter('asNumber', $model->no_of_pensioner) ?> </div>
            <div> <b>Amount:</b> <?= App::formatter('asNumber', $model->amount) ?> </div>
            <div> <b>Description:</b> <?= $model->description ?: 'None' ?> </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <?= Iconbox::widget([
            'title' =>'Total Beneficiaries (' . Html::tag('span', App::formatter('asNumber', $model->totalEventMembers), [
                'id' => 'total-beneficiaries-found',
                'class' => 'font-weight-bolder'
            ]) . ')',
            'url' => Url::to([App::actionID(), 'token' => App::get('token'), 'tab' => 'create-list']),
            'anchorOptions' => [
                'class' => 'text-dark text-hover-success font-weight-bold font-size-h4 mb-3'
            ],
            'iconContent' => Html::tag(
                'div', AppIcon::widget(['icon' => 'add-user']), [
                'class' => 'svg-icon svg-icon-success svg-icon-4x',
            ]),
            'content' => 'Total numbers of beneficiaries created.',
            'wrapperClass' => 'wave wave-animate-slower'
        ]) ?>
    </div>
</div>
<div class="separator separator-dashed my-10"></div>

<h6 class="font-weight-bolder mb-3">
    Documents:
    <?= Html::a('<i class="fa fa-edit text-warning" title="Edit" data-toggle="tooltip"></i>', [
        App::actionID(), 
        'token' => App::get('token'), 
        'tab' => 'documents'
    ]) ?>
</h6>

<table class="table table-bordered table-head-solid" id="table-file">
    <thead>
        <th>file</th>
        <th>date</th>
        <th width="100" class="text-center">action</th>
    </thead>
    <tbody>
        <?= Html::if(($files = $model->allFiles) != null, function() use($files) {
            return Html::foreach($files, function($file) {
                $img = $file->show([
                    'class' => 'img-fluid',
                    'loading' => 'lazy',
                    'width' => 50,
                    'style' => 'border-radius: 4px;width: 50px'
                ], 50);
                return <<< HTML
                    <tr>
                        <td>
                            <div class="d-flex">
                                <div>{$img} </div>
                                <div>
                                    <div class="ml-4">
                                        {$file->upperCaseName}
                                        <br>{$file->fileSize} | {$file->upperCaseExtension}
                                    </div>
                                </div>
                            </div>
                            
                        </td>
                        <td>{$file->createdAt}</td>
                        <td class="text-center">
                            <a href="{$file->viewerUrl}" target="_blank" class="btn btn-light-primary btn-sm btn-icon btn-view-file font-weight-bolder">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                HTML;
            });
        }) ?>
    </tbody>
</table>




<?php $form = ActiveForm::begin(['id' => 'event-form']); ?>
    <?= $form->field($model, 'record_status')->hiddenInput([
        'value' => Event::RECORD_ACTIVE
    ])->label(false) ?>
    <div class="form-group mt-10">
        <?= Html::a('Back', Url::current(['tab' => 'create-list']), [
            'class' => 'btn btn-light-info btn-lg'
        ]) ?>
        <?= Html::submitButton('Confirm & Save', [
            'class' => 'btn btn-success btn-lg'
        ]) ?>
    </div>
<?php ActiveForm::end(); ?>


