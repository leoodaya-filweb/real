<?php

use app\helpers\Html;

$this->registerCss(<<< CSS
    .l-border {
        border-radius: 2px;
        border-left: 3px solid #337ab7;
        background: #eaf1f7;
        padding: 5px 8px;
		box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px -1px;
    }
CSS, [], 'value-css');
?>
<div class="l-border">
    <?= Html::if($label, Html::tag('label', $label, [
        'class' => 'font-weight-bolder'
    ])) ?>
    <p class="value">
        <?= $content ?>
    </p>
</div>