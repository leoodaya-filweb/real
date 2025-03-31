<?php

use app\helpers\Html;
use app\widgets\Detail;
use app\widgets\MemberRecentTransaction;

$this->registerCss(<<< CSS
    .detail-view {
        margin-top: 0;
    }
CSS);

$member = $model->member;
$transactions = $member->recentTransactions;
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => 'Profile Information',
]); ?>

    <ul class="nav nav-tabs nav-tabs-line">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tab-select-transaction-type">
                Select Transaction Type
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-recent-transactions">
                Recent Transactions within 6 months (<?= is_countable($transactions)? count($transactions): 0 ?>)
            </a>
        </li>
    </ul>


<div class="tab-content mt-5" id="myTabContent">
    <div class="tab-pane fade show active" id="tab-select-transaction-type" role="tabpanel" aria-labelledby="tab-select-transaction-type">
        <?= Html::a('View Full Profile', $member->viewUrl, [
            'class' => 'btn btn-light-primary font-weight-bold mb-5',
            'target' => '_blank'
        ]) ?>

        <?= Html::a('Edit Profile', $member->updateUrl, [
            'class' => 'btn btn-light-primary font-weight-bold mb-5',
            'target' => '_blank'
        ]) ?>
        <?= $model->getMemberDetailView(false) ?>
    </div>
    <div class="tab-pane fade" id="tab-recent-transactions" role="tabpanel" aria-labelledby="tab-recent-transactions">
        <?= MemberRecentTransaction::widget([
            'member' => $member,
            'transactions' => $transactions,
        ]) ?>
    </div>
 </div>


<?php $this->endContent(); ?>