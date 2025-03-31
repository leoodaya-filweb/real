<?php
use app\helpers\App;
use app\helpers\Url;
use app\helpers\Html;
use app\widgets\Grid;
use app\widgets\ReportTemplate;
use app\widgets\TinyMce;
use app\models\User;
use yii\widgets\ActiveForm;


$this->title = 'Report summary transactions per client category';
$this->params['searchModel'] = $searchModel; 
$this->params['breadcrumbs'][] = $this->title;
$this->params['wrapCard'] = false; 
$this->params['searchKeywordUrl'] = Url::to(['report/transaction-type-find-by-keywords']); 
$this->params['activeMenuLink'] = '/report/client-category';


$transaction_type = Yii::$app->request->get('transaction_type');
$staff_id = Yii::$app->request->get('staff');
 
 if($transaction_type==1){
      $label = 'AICS ';
 }elseif($transaction_type==6){
      $label = 'CERTIFICATES ';
 }


$users = User::find()->alias('t')->andWhere(" t.id in (select created_by from {{%transactions}} where Year(created_at)=Year(Now()) ) ")
        //->asArray()
        ->all();
  
$users_staff=null;
$staff=[];
if($users) {
 foreach($users as $key=>$row) {
        $staff[$row->id]=$row->fullname;
        $users_staff .= Html::a($row->fullname, ['report/client-category', 'staff'=>$row->id, 'current_date'=>$searchModel->current_date], [
                                         'title' => Yii::t('yii', 'view'),
                                        'class' => "dropdown-item"
                                        ]);
    
       } 
}
        


$aics = '
<div class="dropdown">
  <button class="btn btn-sm mr-10 btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
     '.($staff[$staff_id]?:"All Staff").'
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
   '.Html::a('All Staff', ['report/client-category','current_date'=>$searchModel->current_date], [
                                        'title' => Yii::t('yii', 'view'),
                                        'class' => "dropdown-item"
                                        ])
                                        .$users_staff
  .'</div>
</div>';


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
    echo $form->field($searchModel, 'staff')->hiddenInput(['name'=>'staff'])->label(false);
    ?>
    
    <?= Html::submitButton('Go', [ 'class' => 'btn btn-primary btn-sm ml-1 mb-6']) ?>
   </div>
<?php ActiveForm::end(); ?>





<div class="report-page">
	
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
		'title' => 'SUMMARY REPORT PER CLIENT CATEGORY',
		'toolbar' => <<< HTML
			<div class="card-toolbar">{$aics}
				{$searchModel->clientCategoryExportSummaryBtn}
			</div>
		HTML
	]); ?>
		<?= $this->render('_print-client-category', [
			'searchModel' => $searchModel,
			'staff_name'=>$staff[$staff_id]?:null
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