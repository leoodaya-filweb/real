<?php

use app\helpers\Html;

$this->addCssFile('css/reminder');

$this->registerJs(<<< JS
    $('.close-alert').click(function() {
        $(this).closest('.app-alert').remove();
    });
JS);
?>

<div class="app-alert <?= $type ?>-alert" id="<?= $widgetId ?>">
    <div>
        <div class="head-alert">
            <?= $icon ?>
            <?= $head ?>
        </div>
        <p class="content-alert">
            <?= $message ?>
        </p>
    </div>
    <?= Html::if($withClose, '<div class="close-alert"> <i class="ki ki-close"></i> </div>') ?>
</div>
