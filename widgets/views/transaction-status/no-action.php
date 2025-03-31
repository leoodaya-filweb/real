<?php

use app\helpers\App;
use app\helpers\Html;

?>

<label class="badge badge-<?= $model->transactionStatusClass ?>">
    <?= $model->transactionStatusLabel ?>
</label>
