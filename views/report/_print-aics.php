<?php
use app\helpers\App;
use app\widgets\AicsSummary;
use app\widgets\ColumnChart;



$data = $searchModel->aics_data();
$scenario = $scenario ?? 'default';

//$searchModel->date_range='2024-01-19 - 2024-01-19';
?>



<?= AicsSummary::widget([
	'data' => $data,
	'searchModel' =>$searchModel,
	'title' => 'AICS Summary'
]) ?>
<?php if ($searchModel->showAicsGraph($data)): ?>
	<div class="p-3 mt-10">
		<p class="report-subtitle text-center">
			gender statistics (<?= App::formatter()->asDateToTimezone('', 'Y') ?>)
		</p>
		<?= $this->render('_pie-aics', ['data' => $data]) ?>
	</div>

	<div class="p-3 mt-10">
		<p class="report-subtitle text-center">
			transaction statistics (<?= App::formatter()->asDaterange($searchModel->date_range) ?>)
		</p>
		
		<div class="text-center" style="<?= ($scenario == 'print')? 'max-width: 10in;margin: 0 auto;': '' ?>">
			<?= ColumnChart::widget([
				'data' => $searchModel->aics_transaction_data(),
				'template' => 'aics'
			]) ?>
		</div>
	</div>
<?php endif ?>