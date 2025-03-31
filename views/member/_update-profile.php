<?php

use app\helpers\Html;
use app\widgets\MemberDetail;

?>
<?= Html::a('Update Profile', $model->updateUrl, [
    'class' => 'btn btn-light-primary font-weight-bolder'
]) ?>
<?= MemberDetail::widget([
    'model' => $model,
    'withTransactionBtn' => false,
    'withViewBtn' => false,
]) ?>