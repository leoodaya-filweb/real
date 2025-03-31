<?php

use app\models\search\TransactionSearch;
use app\widgets\Anchors;
use app\widgets\MemberDetail;
use app\widgets\TinyMce;
use yii\widgets\DetailView;
use yii\helpers\Html;


$this->title = 'View Certificate: ' . $model->transactionTypeName;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new TransactionSearch();
$this->params['showCreateButton'] = false; 
$this->params['wrapCard'] = false; 
$this->params['headerButtons'] = implode(' ', [
	Anchors::widget([
    	'names' => ['update'], 
    	'model' => $model

	]),
	$model->transaction_type==7?
	 Html::a('Duplicate', ['transaction/create','qr_id'=>$model->member->qr_id, 'type'=>str_replace(' ', '-',strtolower($model->transactionTypeName)), 'dup'=>$model->token], ['class'=>'font-weight-bold btn btn-primary font-weight-bolder font-size-sm'])
	:''
	
]);
?>
<div class="container">
    
<div class="row">
	<div class="col-md-12">
		<?php $this->beginContent('@app/views/layouts/_card_wrapper.php'); ?>
			<ul class="nav nav-tabs nav-bold nav-tabs-line">
			    <li class="nav-item">
			        <a class="nav-link active" data-toggle="tab" href="#tab-form">
			            <span class="nav-icon">
			                <i class="flaticon2-chat-1"></i>
			            </span>
			            <span class="nav-text"><?= $model->transactionTypeName ?></span>
			        </a>
			    </li>
			    <li class="nav-item">
			        <a class="nav-link" data-toggle="tab" href="#tab-member-profile">
			            <span class="nav-icon">
			                <i class="flaticon2-drop"></i>
			            </span>
			            <span class="nav-text">Member Profile</span>
			        </a>
			    </li>
			</ul>

			<div class="tab-content">
			    <div class="tab-pane fade show active pt-5" id="tab-form" role="tabpanel" aria-labelledby="tab-form">
					<?= TinyMce::widget([
						'size' => 'A4',
					    'content' => ($model->transaction_type==7?$model->contentWithFooterQrCode:$model->contentWithQrCode),
					    'margin'=>($model->transaction_type==7?'0':'0.3in 0.5in'),
					    'bodymargin'=>($model->transaction_type==7?'0':'auto'),
					    'menubar' => false,
					    'toolbar' => 'print',
					    'height' => '400mm',
					    'plugins' =>  'print pagebreak',
					    'readonly' => true,
						'setup' => <<< JS
							editor.on('SkinLoaded', function() {
								$(".tox-toolbar-overlord").removeClass('tox-tbtn--disabled');
								$(".tox-toolbar-overlord").attr( 'aria-disabled', 'false' );
								// And activate ALL BUTTONS styles
								$(".tox-toolbar__group button").removeClass('tox-tbtn--disabled');
								$(".tox-toolbar__group button").attr( 'aria-disabled', 'false' );
							});
						JS
					]) ?>
					
					
					
					
					<?php // echo $model->contentWithQrCode; ?>
				</div>

				<div class="tab-pane fade pt-5" id="tab-member-profile" role="tabpanel" aria-labelledby="tab-member-profile">
			        <?= MemberDetail::widget([
			            'model' => $model->member,
			            'withViewBtn' => true
			        ]) ?>
			    </div>
			</div>
		<?php $this->endContent(); ?>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
			'title' => 'Primary Information'
		]); ?> 
			<?= DetailView::widget([
	            'model' => $model,
	            'attributes' => [
		            'memberFullname:raw',
		            'transactionTypeName:raw',
		            'remarks:raw',
		            'created_at' => [
		                'attribute' => 'created_at',
		                'format' => 'fulldate'
		            ],
		            'updated_at' => [
		                'attribute' => 'updated_at',
		                'format' => 'fulldate'
		            ],
		            'CreatedByName' => [
		                'attribute' => 'CreatedByName',
		                'format' => 'raw',
		            ],
		            'UpdatedByName' => [
		                'attribute' => 'UpdatedByName',
		                'format' => 'raw'
		            ],
		        ],
	        ]) ?>
		<?php $this->endContent(); ?>
	</div>
 </div>
</div>
