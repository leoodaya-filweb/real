<?php

use app\helpers\Html;

$this->registerCss(<<< CSS
    .btn-show-instructions {
        color: #3F4254 !important;
        background-color: #ffffff !important;
        border-color: #ffffff !important;
    }
    .btn-show-instructions:hover {
        background-color: #f7f7f7 !important;
        border-color: #f7f7f7 !important;
    }
    .btn.btn-outline-white i {
        color: #3F4254 !important;
    }
    .swal2-container .swal2-html-container {
        max-height: 100%;
        overflow: auto;
    }
    .swal2-container .swal2-html-container ul {
        text-align: left;
    }
CSS);

$content = $this->render($template, [
    'transaction' => $transaction,
    'widgetId' => $widgetId,
    'checked' => $checked,
    'xmark' => $xmark,
]);

$this->registerjs(<<< JS
    $('#btn-instructions-{$widgetId}').click(function() {
        Swal.fire('Complete Instructions First!', `{$content}`, 'warning');
    });
JS)

?>
<?= Html::button($buttonContent, $buttonOptions) ?>