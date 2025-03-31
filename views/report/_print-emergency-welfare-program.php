<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ColumnChart;
use app\widgets\EmergencyWelfareProgramSummary;

$data = $searchModel->emergency_welfare_program_data();
$scenario = $scenario ?? 'default';

if (Yii::$app->user->identity->id==17){
   // echo $searchModel->date_range;
    
  // print_r($searchModel->emergency_welfare_program_transaction_data()) ;
}
?>

<?php 

echo EmergencyWelfareProgramSummary::widget([
	'data' => $data,
	'searchModel' =>$searchModel,
	'title' => 'Emergency Welfare Program Summary'
]);

?>


<?php if ($searchModel->showEwpGraph($data)): ?>
	<div class="p-3 mt-10">
		<p class="report-subtitle text-center">
			gender statistics (<?= App::formatter()->asDateToTimezone('', 'Y') ?>)
		</p>
		<?= $this->render('_pie-emergency-welfare-program', ['data' => $data]) ?>
	</div>

	<div class="p-3 mt-10">
		<p class="report-subtitle text-center">
			transaction statistics (<?= App::formatter()->asDaterange($searchModel->date_range) ?>)
		</p>
		
		<div class="text-center" style="<?= ($scenario == 'print')? 'max-width: 10in;margin: 0 auto;': '' ?>">
			<?= ColumnChart::widget([
				'data' => $searchModel->emergency_welfare_program_transaction_data(),
				'template' => 'emergency-welfare-program'
			]) ?>
		</div>
	</div>
<?php endif ?>