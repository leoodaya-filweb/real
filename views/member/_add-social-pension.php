<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Transaction;
use app\widgets\MemberRecentTransaction;

$t = App::params('transaction_types_menu')[Transaction::SOCIAL_PENSION];
?>


<div class="d-flex justify-content-between mb-5">
    <div>
        <p class="lead font-weight-bold">
            Recent Transactions within 6 months
        </p>
    </div>
    <div>
        <?= Html::a('Click to Continue', $model->updateProfileUrlSocialPension, [
            'class' => 'btn btn-outline-success font-weight-bolder'
        ]) ?>
    </div>
</div>
<?= MemberRecentTransaction::widget(['member' => $model]) ?>
