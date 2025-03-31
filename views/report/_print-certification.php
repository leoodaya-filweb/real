<?php

use app\helpers\App;
use app\widgets\CertificationSummary;
use app\widgets\ColumnChart;

$data = $searchModel->certification_data();
$scenario = $scenario ?? 'default';
?>

<?= CertificationSummary::widget([
	'data' => $data,
	'searchModel' =>$searchModel,
	'title' => 'Certification Summary'
]) ?>

<?php if ($searchModel->showCertificationGraph($data)): ?>
	<div class="p-3 mt-10">
		<p class="report-subtitle text-center">
			gender statistics (<?= App::formatter()->asDateToTimezone('', 'Y') ?>)
		</p>
		<?= $this->render('_pie-certification', ['data' => $data]) ?>
	</div>

	<div class="p-3 mt-10">
		<p class="report-subtitle text-center">
			transaction statistics (<?= App::formatter()->asDaterange($searchModel->date_range) ?>)
		</p>
		
		<div class="text-center" style="<?= ($scenario == 'print')? 'max-width: 10in;margin: 0 auto;': '' ?>">
			<?= ColumnChart::widget([
				'data' => $searchModel->certification_transaction_data(),
				'template' => 'certification'
			]) ?>
		</div>
	</div>
<?php endif ?>