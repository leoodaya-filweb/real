<?php

use app\helpers\Html;
$trt = $model->totalRecentTransactions;
?>

<ul class="nav nav-tabs nav-bold nav-tabs-line">
	<li class="nav-item">
		<a class="nav-link active" data-toggle="tab" href="#tab-personal-information">
			<span class="nav-icon">
				<i class="flaticon2-chat-1"></i>
			</span>
			<span class="nav-text">
				Personal Infomation
			</span>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#tab-transaction-record">
			<span class="nav-icon">
				<i class="flaticon2-drop"></i>
			</span>
			<span class="nav-text">
				Transactions (<?= $trt ?>)
			</span>
		</a>
	</li>
</ul>
<div class="tab-content pt-3">
	<div class="tab-pane fade show active" id="tab-personal-information" role="tabpanel" aria-labelledby="tab-personal-information">
		<?= $model->getDetailView(false) ?>
	</div>
	<div class="tab-pane fade" id="tab-transaction-record" role="tabpanel" aria-labelledby="tab-transaction-record">
		<h3 class="card-title align-items-start flex-column mt-3">
            <span class="card-label font-weight-bold font-size-h4 text-dark-75">
                Recent Transactions (<?= $trt ?>) 
                <span class="text-warning">
                    Last 6 Months
                </span>
            </span>

            <span class="float-right">
                <?= Html::a('View All Transaction', $model->viewUrl . '?tab=transactions', [
                    'class' => 'btn btn-light-primary font-weight-bolder font-size-sm',
                    'target' => '_blank'
                ]) ?> 
            </span>
       
            <p class="text-muted mt-3 font-weight-bold font-size-sm">
                Showing <?= $model->totalRecentTransactions ?> out of <?= $model->totalTransactions ?> records
            </p>
        </h3>
		<div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>transaction type</th>
                        <th>assistance type</th>
                        <th class="text-right">amount</th>
                        <th class="text-right">date</th>
                        <th>ago</th>
                        <th>status</th>
                        <th class="text-center">action</th>
                    </tr>
                </thead>
                <tbody>
                    <?= Html::ifElse(($transactions = $model->recentTransactions) != null, function()use ($transactions) {

                        return Html::foreach($transactions, function($transaction, $key) {
                            $serial = $key + 1;
                            return <<< HTML
                                <tr>
                                    <td>{$serial}</td>
                                    <td>{$transaction->transactionTypeName}</td>
                                    <td>{$transaction->assistanceTypeName}</td>
                                    <td class="text-right">{$transaction->formattedAmount}</td>
                                    <td class="text-right">{$transaction->date}</td>
                                    <td>{$transaction->ago}</td>
                                    <td>{$transaction->statusBadge}</td>
                                    <td class="text-center">{$transaction->viewBtn}</td>
                                </tr>
                            HTML;
                        });
                    }, <<< HTML
                        <tr>
                            <td colspan="7">
                                <h5 class="text-warning">No transactions found</h5>
                            </td>
                        </tr
                    HTML) ?>
                </tbody>
            </table>
        </div>
	</div>
</div>
