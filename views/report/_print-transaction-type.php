<?php

use app\helpers\App;
use app\widgets\ColumnChart;
use app\widgets\TransactionTypeSummary;

$data = $searchModel->transaction_type_data();
$scenario = $scenario ?? 'default';
?>

<?= TransactionTypeSummary::widget([
	'data' => $data,
	'searchModel' =>$searchModel,
	'title' => 'Transaction Type Summary'
]) ?>


<?php if ($searchModel->showTransactionTypeGraph($data)): ?>
	<div class="p-3 mt-10">
		<p class="report-subtitle text-center">
			gender statistics (<?= App::formatter()->asDateToTimezone('', 'Y') ?>)
		</p>
		<?= $this->render('_pie-transaction-type', ['data' => $data]) ?>
	</div>

	<div class="p-3 mt-10">
		<p class="report-subtitle text-center">
			transaction statistics (<?= App::formatter()->asDaterange($searchModel->date_range) ?>)
		</p>
		
		<div class="text-center" style="<?= ($scenario == 'print')? 'max-width: 10in;margin: 0 auto;': '' ?>">
			<?= ColumnChart::widget([
				'data' => $searchModel->transaction_type_transaction_data(),
				'template' => 'transaction-type'
			]) ?>
		</div>
	</div>
<?php endif ?>