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
   
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            Action
        </a>
        <div class="dropdown-menu">
            <?= Html::a('View Profile', $model->viewUrl, ['class' => 'dropdown-item']) ?>
            <?= Html::a('Add Transaction', $model->createTransactionLink, [
                'class' => 'dropdown-item'
            ]) ?>
        </div>
    </li>
	

</ul>
<div class="tab-content pt-3">
	<div class="tab-pane fade show active" id="tab-personal-information" role="tabpanel" aria-labelledby="tab-personal-information">
		<?= $model->detailView ?>
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
                <?= Html::a(<<< HTML
                    <span class="svg-icon svg-icon-md svg-icon-white">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13" rx="1.5"></rect>
                                <rect fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8" rx="1.5"></rect>
                                <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"></path>
                                <rect fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6" rx="1.5"></rect>
                            </g>
                        </svg>
                    </span>
                    New Transaction
                HTML, $model->createTransactionLink, [
                    'class' => 'btn btn-primary font-weight-bolder font-size-sm'
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
                            <td colspan="8">
                                <p class="text-default">No transactions found</p>
                            </td>
                        </tr
                    HTML) ?>
                </tbody>
            </table>
        </div>
	</div>
</div>
