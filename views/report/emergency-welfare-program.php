<?php
use yii\helpers\Html;
use app\helpers\App;
use app\helpers\Url;
use app\models\Transaction;
use app\widgets\EmergencyWelfareProgramSummary;
use app\widgets\ExportButton;
use app\widgets\FilterColumn;
use app\widgets\Grid;
use app\widgets\PieChart;
use app\widgets\ReportTemplate;
use app\widgets\TinyMce;
use yii\widgets\ActiveForm;

$this->title = 'Report: Emergency Welfare Program';
$this->params['searchModel'] = $searchModel; 
$this->params['breadcrumbs'][] = $this->title;
$this->params['wrapCard'] = false; 
$this->params['searchKeywordUrl'] = Url::to(['report/ewp-find-by-keywords']); 
$this->params['activeMenuLink'] = '/report/emergency-welfare-program';

$data = $searchModel->emergency_welfare_program_data();

//print_r($data);


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
    <?= Html::submitButton('Go', [ 'class' => 'btn btn-primary btn-sm ml-1 mb-6']) ?>
   </div>
<?php ActiveForm::end(); ?>

<div class="report-page">
	
	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
		'title' => 'SUMMARY REPORT',
		'toolbar' => <<< HTML
			<div class="card-toolbar">
				{$searchModel->ewpExportSummaryBtn}
			</div>
		HTML
	]); ?>
		<?= $this->render('_print-emergency-welfare-program', [
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

	<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
		'title' => 'Transaction Items',
		'toolbar' => <<< HTML
			<div class="card-toolbar">
				{$searchModel->ewpExportTransactionBtn}
			</div>
		HTML
	]); ?>
		<?= Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'columns' => $searchModel->ewpColumns,
            'withActionColumn' => false
        ]); ?>
	<?php $this->endContent(); ?>
</div>