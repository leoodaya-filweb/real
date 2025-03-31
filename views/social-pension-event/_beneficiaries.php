<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\EventMember;
use app\widgets\ActiveForm;
use app\widgets\Anchor;
use app\widgets\ExportButton;
use app\widgets\Filter;
use app\widgets\Grid;
?>
<div class="modal fade" id="modal-receive-assistance" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-receive-assistanceLabel">
                    Receive Assistance Form
                </h5>
                <button type="button" class="close btn-close" aria-label="Close" data-dismiss="modal">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="beneficiaries-grid-container">
    <?= ExportButton::widget([
        'title' => 'Export Masterlist',
        'filename' => "{$model->name} Masterlist",
        'controller' => 'event-member',
        'anchorOptions' =>  [
        'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
            'data-toggle' => 'dropdown',
            'aria-haspopup' => true,
            'aria-expanded' => false
        ],
        'printUrl' => [
            'event-member/print', 
            'event_id' => $model->id,
        ],
        'pdfUrl' => [
            'event-member/export-pdf', 
            'event_id' => $model->id,
        ],
        'csvUrl' => [
            'event-member/export-csv', 
            'event_id' => $model->id,
        ],
        'xlsUrl' => [
            'event-member/export-xls', 
            'event_id' => $model->id,
        ],
        'xlsxUrl' => [
            'event-member/export-xlsx', 
            'event_id' => $model->id,
        ],
    ]) ?>
    
    <?= $this->render('_beneficiaries-grid', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'model' => $model,
        'eventMemberData' => $eventMemberData,
    ]) ?>
</div>