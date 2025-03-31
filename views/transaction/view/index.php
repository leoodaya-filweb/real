<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\widgets\Anchors;
use app\widgets\AppIcon;
use app\widgets\Timeline;
use app\widgets\TransactionInstructions;
use app\widgets\Value;
use app\models\Transaction;

$this->registerCss(<<< CSS
    .eligibility-notice {
        background: #fff;
        padding: 10px;
    }
    .eligibility-notice .mb-9 {
        margin-bottom: 0px !important;
    }
    .budget-notice {
        background: white;
        padding: 18px 10px;
        border-radius: 2px;
        border-left: 3px solid #3699ff;
    }
    .app-iconbox {
        box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px 0 !important;
    }

    .app-iconbox .card-body {
        padding: 0rem 0.25rem !important;
    }
CSS);

$this->registerJs(<<< JS
    $('.toggle-tooltip').tooltip();
JS);


$budget = App::setting('budget');

$this->params['headerButtons'] = implode(" ", [
    TransactionInstructions::widget(['transaction' => $model]),
   $model->actionButton,
   $model->statusAction,
    Anchors::widget([
        'names' => ['update', 'delete', 'log'], 
        'model' => $model
    ])
]);



if($model->transaction_type==4){
    $transactionfound = Transaction::find()
    ->andWhere("name_of_deceased is not null")
    ->andWhere(['<>', 'id', $model->id])
    ->andWhere(['transaction_type'=>4,'status'=>10])
    ->andFilterWhere(['like', 'name_of_deceased', $model->name_of_deceased])
    ->one();
    
    if($transactionfound){
      $infor ='
      <div class="alert alert-danger d-flex align-items-center p-5 mb-10" style="background-color: #ffffff!important; color: #222 !important;">
      <div>
            <div class="head-alert">
              <i class="fas fa-info"></i> Duplicate assistance
            </div>
          <p class="content-alert">
            Other claimant <strong>'.$transactionfound->claimantName.'</strong>  already claimed the burial assistance for <strong>'.$transactionfound->name_of_deceased.'</strong> on '.App::formatter()->asDateToTimezone($transactionfound->created_at, 'F d, Y h:iA').'. 
            '.Html::a('See details', ['transaction/view', 'token' => $transactionfound->token], ['class' => 'link']).'
         </p>
       </div>
    
   </div>';
     echo $infor;
    }
    
}


if($model->patient_name && $model->transaction_type && in_array($model->transaction_type,[1,2,3])){
    $transactionfound = Transaction::find()
    ->andWhere("patient_name is not null and patient_name<>'' ")
    ->andWhere(['<>', 'id', $model->id])
    ->andWhere(['transaction_type'=>[1,2,3],'status'=>10])
    ->andFilterWhere(['like', 'patient_name', $model->patient_name])
    ->one();
    
    if($transactionfound){
      $infor ='
      <div class="alert alert-danger d-flex align-items-center p-5 mb-10" style="background-color: #ffffff!important; color: #222 !important;">
      <div>
            <div class="head-alert">
              <i class="fas fa-info"></i> Duplicate assistance
            </div>
          <p class="content-alert">
            Other client/claimant <strong>'.$transactionfound->claimantName.'</strong>  already claimed the medical assistance for <strong>'.$transactionfound->patient_name.'</strong> on '.App::formatter()->asDateToTimezone($transactionfound->created_at, 'F d, Y h:iA').'. 
            '.Html::a('See details', ['transaction/view', 'token' => $transactionfound->token], ['class' => 'link']).'
         </p>
       </div>
    
   </div>';
     echo $infor;
    }
    
}


if($model->relation_type==1 && $model->transaction_type && in_array($model->transaction_type,[1,2,3])){
    $transactionfound = Transaction::find()
    ->andWhere("relation_type=1")
    ->andWhere(['<>', 'id', $model->id])
    ->andWhere(['transaction_type'=>[1,2,3],'status'=>10, 'member_id'=>$model->member_id])
    //->andFilterWhere(['like', 'patient_name', $model->patient_name])
    ->one();
    
    if($transactionfound){
      $infor ='
      <div class="alert alert-danger d-flex align-items-center p-5 mb-10" style="background-color: #ffffff!important; color: #222 !important;">
      <div>
            <div class="head-alert">
              <i class="fas fa-info"></i> Duplicate assistance
            </div>
          <p class="content-alert">
           Client patient <strong>'.$transactionfound->claimantName.'</strong>  already claimed the medical assistance on '.App::formatter()->asDateToTimezone($transactionfound->created_at, 'F d, Y h:iA').'. 
            '.Html::a('See details', ['transaction/view', 'token' => $transactionfound->token], ['class' => 'link']).'
         </p>
       </div>
    
   </div>';
   echo $infor;
   //echo '..';
    }
    
}


?>




<div class="row">
    <div class="col-md-4">
        
        
        
        <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
            'title' => "Transaction Process",
            'toolbar' => <<< HTML
                <div class="card-toolbar">
                    <div class="text-left">
                        {$model->statusBadge}
                        <br>{$model->secondaryLabel}
                    </div>
                </div>
            HTML
        ]); ?>
            <ul class="navi navi-hover navi-active">
                <?= Html::foreach($model->viewTabs, function($viewTab, $keyTab) use($tab) {
                    $class = $keyTab == $tab ? 'active': '';
                    $url = Url::current(['tab' => $keyTab]);

                    return <<< HTML
                        <li class="navi-item" data-key="{$keyTab}">
                            <a class="navi-link {$class}" href="{$url}">
                                <span class="symbol symbol-50 mr-3">
                                    <span class="symbol-label">
                                        {$viewTab['icon']}
                                    </span>
                                </span>
                                <div class="navi-text">
                                    <span class="d-block font-weight-bold">
                                        {$viewTab['label']}
                                    </span>
                                    <span class="text-muted">
                                        {$viewTab['description']}
                                    </span>
                                </div>
                                {$viewTab['status']}
                            </a>
                        </li>
                    HTML;
                }) ?>
            </ul>
        <?php $this->endContent(); ?>

    </div>
    <div class="col-md-8">
       
        
        <div class="<?= !$model->isSeniorCitizenIdApplication ? '': '' ?>">
            <?= $content ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-transaction-logs" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-transaction-logsLabel">Transaction Logs</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?= Timeline::widget(['model' => $model]) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>