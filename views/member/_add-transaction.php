<?php
use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Transaction;
use app\widgets\AppIcon;
use app\widgets\MemberRecentTransaction;
use yii\helpers\StringHelper;

$transactions = $model->recentTransactions;

$this->registerCss(<<< CSS
    .app-border:hover {
        cursor: pointer;
        box-shadow: rgb(0 0 0 / 30%) 0px 1px 20px 0 !important;
    }
    .bg-diagonal-light-success:hover {outline: 3px solid #1BC5BD !important;}
    .bg-diagonal-light-primary:hover {outline: 3px solid #3699FF !important;}
    .bg-diagonal-light-info:hover {outline: 3px solid #3699FF !important;}
    .bg-diagonal-light-secondary:hover {outline: 3px solid #E4E6EF !important;}
    .bg-diagonal-light-danger:hover {outline: 3px solid #F64E60 !important;}
    .bg-diagonal-light-warning:hover {outline: 3px solid #FFA800 !important;}

CSS);

$this->registerJs(<<< JS
    $('.transaction-types .app-border').click(function() {
        window.location.href = $(this).data('url');
    });

    $('[data-toggle="popover"]').popover();
JS);






?>

<ul class="nav nav-tabs nav-tabs-line">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#tab-select-transaction-type">
            Select Transaction Type
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab-recent-transactions">
            Recent Transactions within 6 months (<?= is_countable($transactions)? count($transactions): 0 ?>)
        </a>
    </li>
</ul>

<div class="tab-content mt-5" id="myTabContent">
    <div class="tab-pane fade show active" id="tab-select-transaction-type" role="tabpanel" aria-labelledby="tab-select-transaction-type">
        <div class="row transaction-types">
            <?= Html::foreach(App::params('transaction_types_menu'), function($t, $index) use($model) {
                
   
           if($t['user_access']){
			   $user_access= $t['user_access'];//json_decode($t['user_access']);
			   if(is_array($user_access) && !in_array(Yii::$app->user->identity->username,$user_access)){
			    return false;
			   }
			   
            }
   
   
                $objective = $t['objective'];
                // $objective = StringHelper::truncate($t['objective'], 90);
                $icon = AppIcon::widget(['icon' => $t['slug'], 'iconClass' => $t['class']]);
            
                $url = Url::to([
                    'transaction/update-profile', 
                    'qr_id' => $model->qr_id, 
                    'transaction_type' => $t['slug']
                ]);
                return <<< HTML
                    <div class="col-md-4 mb-10">
                        <div class="card card-custom card-stretch bg-diagonal bg-diagonal-light-{$t['class']} app-border" data-url="{$url}">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                                    <div class="d-flex flex-column mr-5">
                                        <a href="{$url}" class="h4 text-dark text-hover-{$t['class']} mb-5">
                                            {$t['label']}
                                        </a>
                                        <p class="text-dark-50" data-original-title="Objective" title="Objective" data-content="{$t['objective']}" data-toggle="popover">
                                            {$objective}
                                        </p>
                                    </div>
                                    <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                                        {$icon}
                                    </div>
                                </div>
                               
                               
                                  <div class="text-right" style="position: absolute;right: 20px; bottom: 20px;">
                                    <button type="button" class="btn btn-primary btn-md image-gallery-btn">Select</button>
                                  </div>
                               
                               
                            </div>
                            
                        </div>
                    </div>
                HTML;
            }) ?>
        </div>
    </div>
    
    <div class="tab-pane fade" id="tab-recent-transactions" role="tabpanel" aria-labelledby="tab-recent-transactions">

        <?= MemberRecentTransaction::widget(['member' => $model]) ?>
    </div>
</div>

