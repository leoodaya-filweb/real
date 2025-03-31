<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Transaction;
use app\widgets\BulkAction;
use app\widgets\ExportButton;
use app\widgets\FilterColumn;
use app\widgets\Grid;
use app\widgets\SearchQrCode;
use app\widgets\Value;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['showCreateButton'] = true; 
$this->params['showExportButton'] = true;
$this->params['wrapCard'] = false;

$this->registerJs(<<< JS
    $('.btn-summary').click(function() {
        let date_range = $(this).data('date_range');
        KTApp.block('body', {
            overlayColor: '#000000',
            state: 'warning',
            message: 'Generating Summary Report...'
        });
        
        $.ajax({
            url: app.baseUrl + 'transaction/summary-report',
            data: {date_range: date_range},
            method: 'get',
            dataType: 'json',
            success: function(s) {
                if(s.status == 'success') {
                    $('#modal-summary .modal-body').html(s.summary);
                    $('#modal-summary').modal('show');
                }
                else {
                    Swal.fire('Error', s.error, 'error');
                }
                KTApp.unblock('body');
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('body');
            }
        });
    });

    $('.btn-create').click(function(e) {
        e.preventDefault();
        $('.add-transaction').slideToggle();
    });
JS);

$this->registerCss(<<< CSS
    .bmr-1 {
        white-space: nowrap;
        /*border: 1px solid #ccc;*/
        padding: 10px 10px;
        border-radius: 5px;
        margin: 0 3px;
        text-align: center;
    }
    .bmr-1 label {
        margin-top: 5px;
        margin-bottom: 0;
        cursor: pointer;
    }
    .bmr-1:hover {
        background: #e7e7e7;
    }
    .bmr-1 .badge-count {
        font-size: 12px;
    }
    .btn-group .active {
        background: #e7e7e7;
    }
CSS);

$getStatus = App::get('status');
$getStatus = is_array($getStatus)? $getStatus: [$getStatus];
?>
<div class="transaction-index-page">
    <?php if (App::identity()->can('create')): ?>
        <div class="add-transaction" style="display: none">
            
          
          <div class="card card-custom mb-8">
			 <div class="card-body pt-5"> 
			 
              <div class="row">
                  
                   <div class="col-md-4 pt-8">
                    
                       <?php //$this->beginContent('@app/views/layouts/_card_wrapper.php', [ 'title' => 'Procedure' ]); ?>
                       <strong>Procedure</strong>
                <ul>
                    <li>Seach Name or Scan QR Code</li>
                    <li>Select Transaction Type</li>
                    <li>Update Profile</li>
                    <li>Fill up transaction form</li>
                    <li>Complete</li>
                </ul>
                <?php //$this->endContent(); ?>
                    
                </div>
                  
               <div class="col-md-8">

                 
                <?= SearchQrCode::widget([
                    'title' => 'Search household member to create Transaction'
                ]) ?>
                 

                 
               </div>
               
               
              </div>
          
            </div>
          </div>
          
          
          
           
        </div>
    <?php endif ?>
    
    <?php if (($total = Transaction::find()->count()) > 0): ?>
        <div style="overflow:auto;">
            <div class="btn-group pb-3">
                <a href="<?= $searchModel->indexUrl ?>" class="bmr-1 <?= (App::get('status')) ? '': 'active' ?>">
                    <div class="text-center">
                        <span class="badge badge-pill badge-dark badge-count">
                            <?= Html::number($total) ?>
                        </span>
                    </div>
                    <label class="badge badge-white">
                        <span class="font-weight-bold">
                            All
                        </span>
                    </label>
                </a>
                <?= Html::foreach(Transaction::getFilterData(), function($status) use($getStatus) {
                    $active = in_array($status['status'], $getStatus)? 'active': '';

                    return <<< HTML
                        <a href="{$status['url']}" class="bmr-1 {$active}">
                            <div class="text-center">
                                <span class="badge badge-pill badge-{$status['class']} badge-count">
                                    {$status['total']}
                                </span>
                            </div>
                            <label class="badge badge-white">
                                <span class="font-weight-bold">
                                    {$status['statusName']}
                                </span>
                            </label>
                        </a>
                    HTML;
                }) ?>
                
                
                <?php
    $transactionfound = Transaction::find()->select([" count(*) as total_count"])
    ->from("(SELECT * FROM {{%transactions}} where status=10 and name_of_deceased is NOT null GROUP by name_of_deceased HAVING count(*)>1) sc")
    ->asArray()
    ->one();
    
                
                echo Html::a('
                <div div class="text-center"><span class="badge badge-pill badge-warning badge-count">'.$transactionfound['total_count'].'</span></div>
                <label class="badge badge-white"><span class="font-weight-bold">Duplicate Death Assistance</span></label>', ['transaction/index', 'duplicate' => 1], ['class' => 'bmr-1']); ?>
            </div>
        </div>
    <?php endif ?>


    <?php $this->beginContent('@app/views/layouts/_card_wrapper.php'); ?>
        <?= FilterColumn::widget(['searchModel' => $searchModel]) ?>
        <?= Html::beginForm(['bulk-action'], 'post'); ?>
            <div class="d-flex">
                <div>
                    <?= BulkAction::widget(['searchModel' => $searchModel]) ?>
                </div>
                <div>
                    <?= Html::if(App::identity()->can('summary-report'), Html::a('Summary Report', '#', [
                        'class' => 'btn btn-light-success btn-sm font-weight-bolder ml-4 btn-summary',
                        'data-date_range' => $searchModel->theDateRange
                    ])) ?>
                </div>
            </div>
           
            <?= Grid::widget([
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'rowOptions' => function ($model, $key, $index, $grid) {
                    if($model->transaction_type==7 && !in_array(App::identity()->role_id, [2, 3, 4, 5])  ) { 
                        return ['style' => 'display: none']; //display: none;
                        }
                        return [];
                  }
            ]); ?>
        <?= Html::endForm(); ?> 
    <?php $this->endContent(); ?>
</div>


<div class="modal fade" id="modal-summary" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-summaryLabel">Summary Report </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close</button>
                <?= Html::if(App::identity()->can('export-summary'), ExportButton::widget([
                    'filename' => "Summary Report ({$searchModel->theDateRange})",
                    'controller' => 'transaction',
                    'anchorOptions' =>  [
                    'class' => 'btn btn-light-primary font-weight-bolder',
                        'data-toggle' => 'dropdown',
                        'aria-haspopup' => true,
                        'aria-expanded' => false
                    ],
                    'printUrl' => [
                        'transaction/export-summary',
                        'date_range' => $searchModel->theDateRange,
                        'type' => 'print'
                    ],
                    'pdfUrl' => [
                        'transaction/export-summary', 
                        'date_range' => $searchModel->theDateRange,
                        'type' => 'pdf'
                    ],
                    'csvUrl' => [
                        'transaction/export-summary', 
                        'date_range' => $searchModel->theDateRange,
                        'type' => 'csv'
                    ],
                    'xlsxUrl' => [
                        'transaction/export-summary', 
                        'date_range' => $searchModel->theDateRange,
                        'type' => 'xlsx'
                    ],
                ])) ?>
            </div>
        </div>
    </div>
</div>