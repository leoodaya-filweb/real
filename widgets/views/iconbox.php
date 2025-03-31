<?php

use app\helpers\Html;

$this->registerCss(<<< CSS
    .app-iconbox {
        box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px 0 !important;
    }
    .app-iconbox .icon-content {
        white-space: nowrap;
    }
CSS);
?>
<div class="<?= $wrapperClass ?> app-iconbox card card-custom card-stretch icon-box">
    <div class="card-body">
        <div class="d-flex align-items-center p-5">
            <div class="mr-6 icon-content">
                <?= $iconContent ?>
            </div>
            <div class="d-flex flex-column">
                <?= Html::a($title, $url, $anchorOptions) ?>
                <div class="text-dark-75">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</div>