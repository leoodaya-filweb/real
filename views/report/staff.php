<?php
use app\helpers\App;
use app\helpers\Url;
use app\helpers\Html;
use app\widgets\Grid;
use app\widgets\ReportTemplate;
use app\widgets\TinyMce;
use yii\widgets\ActiveForm;

$transaction_type = Yii::$app->request->get('transaction_type');
 
 if($transaction_type==1){
      $label = 'AICS ';
 }elseif($transaction_type==6){
      $label = 'CERTIFICATES ';
 }


$this->title = $label.'Report summary transactions per staff';
$this->params['searchModel'] = $searchModel; 
$this->params['breadcrumbs'][] = $this->title;
$this->params['wrapCard'] = false; 
$this->params['searchKeywordUrl'] = Url::to(['report/transaction-type-find-by-keywords']); 
$this->params['activeMenuLink'] = '/report/staff';




$aics = Html::a('All', ['report/staff', 'current_date'=>$searchModel->current_date], [
                                        'title' => Yii::t('yii', 'view'),
                                        'class' => "btn btn-sm ".(!$transaction_type?'btn-primary btn-text-white-50':'btn-secondary btn-text-dark-50')." btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm mr-3"
                                        ]);

$aics .= Html::a('AICS Per Staff', ['report/staff', 'transaction_type'=>1, 'current_date'=>$searchModel->current_date], [
                                        'title' => Yii::t('yii', 'view'),
                                        'class' => "btn btn-sm ".($transaction_type==1?'btn-primary btn-text-white-50':'btn-secondary btn-text-dark-50')."  btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm mr-3"
                                        ]);
$aics .= Html::a('Certifications Per Staff', ['report/staff', 'transaction_type'=>6, 'current_date'=>$searchModel->current_date], [
                                        'title' => Yii::t('yii', 'view'),
                                        'class' => "btn btn-sm ".($transaction_type==6?'btn-primary btn-text-white-50':'btn-secondary btn-text-dark-50')." btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm mr-15"
                                    ]);
?>


<?php $form = ActiveForm::begin([
    'action' => $searchModel->searchAction,
    'method' => 'get',
    'id' => 'transaction-search-form'
]); ?>
   <div class="d-flex align-items-right" style="justify-content: flex-end; margin-top: -55px">
    <span style="display: inline-block; margin: 10px;">As of:</span> 
    <?php
    !$searchModel->current_date?$searchModel->current_date=App::formatter()->asDateToTimezone($current_date, 'Y-m-d'):null;
    echo $form->field($searchModel, 'current_date')->textInput(['value' => $model->current_date, 'type'=>'date', 'name'=>'current_date', 'min'=>"01/01/1997", 'max'=>"12/31/2030", 'placeholder'=>'As of today', 'id'=>'daterange-filter', 'class'=>'form-control' ])->label(false);
    ?>
    
     <?php
    echo $form->field($searchModel, 'transaction_type')->hiddenInput(['name'=>'transaction_type'])->label(false);
    ?>
    
    <?= Html::submitButton('Go', [ 'class' => 'btn btn-primary btn-sm ml-1 mb-6']) ?>
   </div>
<?php ActiveForm::end(); ?>


<div class="report-page">
	
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
		'title' => 'SUMMARY REPORT PER STAFF',
		'toolbar' => <<< HTML
			<div class="card-toolbar">{$aics}
				{$searchModel->staffExportSummaryBtn}
			</div>
		HTML
	]); ?>
		<?= $this->render('_print-staff', [
			'searchModel' => $searchModel
		]) ?>
	<?php $this->endContent(); ?>


	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
		'title' => 'CUSTOM REPORT',
	]); ?>
		<?= TinyMce::widget([
			'content' => ReportTemplate::widget() . <<< HTML
				<h2 style="text-align: center">CUSTOM REPORT</h2>
				<table style="width: 100%">
					<tbody>
						<tr><td></td><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td><td></td></tr>
					</tbody>
				</table>
			HTML
		]) ?>
	<?php $this->endContent(); ?>

	<?php 
	/*
	$this->beginContent('@app/views/layouts/_card_wrapper.php', [
		'title' => 'Transaction Items',
		'toolbar' => <<< HTML
			<div class="card-toolbar">
				{$searchModel->aicsExportTransactionBtn}
			</div>
		HTML
	]); ?>
		<?= Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $searchModel->aicsColumns,
            'withActionColumn' => false
        ]); ?>
	<?php $this->endContent(); 
	
	*/?>
</div>