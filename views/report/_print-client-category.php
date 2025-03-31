<?php

use app\helpers\App;
use app\widgets\ClientcategorySummary;
use app\widgets\ColumnChart;

$data = $searchModel->client_category_data();
$scenario = $scenario ?? 'default';

$transaction_type = Yii::$app->request->get('transaction_type');
 
 if($transaction_type==1){
      $label = 'AICS ';
 }elseif($transaction_type==6){
      $label = 'CERTIFICATES ';
 }

?>

<?= ClientcategorySummary::widget([
	'data' => $data,
	'searchModel' =>$searchModel,
	'title' => $label.'Transactions Per Client Category'.($staff_name?' By '.$staff_name:null)
]) ?>
<?php if ($searchModel->showAicsGraph($data)): ?>
	<div class="p-3 mt-10">
		<p class="report-subtitle text-center">
			gender statistics (<?= App::formatter()->asDateToTimezone('', 'Y') ?>)
		</p>
		<?= $this->render('_pie-client-category', ['data' => $data]) ?>
	</div>

	<div class="p-3 mt-10">
		<p class="report-subtitle text-center">
			transaction statistics per client categoty (<?= App::formatter()->asDaterange($searchModel->date_range) ?>)
		</p>
		
		<div class="text-center" style="<?= ($scenario == 'print')? 'max-width: 10in;margin: 0 auto;': '' ?>">
			<?= ColumnChart::widget([
				'data' => $searchModel->client_category_transaction_data(),
				'template' => 'aics'
			]) ?>
		</div>
	</div>
<?php endif ?>