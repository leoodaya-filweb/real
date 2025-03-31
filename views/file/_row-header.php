<?php

use app\helpers\Html;

$tableId = $tableId ?? 'table-file';

$this->registerCss(<<< CSS
    .app-iconbox {
        box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px 0
    }
    
    #{$tableId}_filter {
        text-align: right;
    }
    #{$tableId}_filter label,
    #{$tableId}_length label {
        display: inline-flex;
    }

    #{$tableId}_filter input {
        margin-top: -5px;
        margin-left: 5px;
    }
    #{$tableId}_length select{
        margin-top: -5px;
        margin-left: 5px;
        margin-right: 5px;
    }
    #{$tableId}_paginate {
        float: right;
    }
    .th-file {
        width: 80% !important;
    }
CSS);


$this->registerJs(<<< JS
    $('#{$tableId}').DataTable({
        pageLength: 5,
        order: [[0, 'desc']]
    });
JS);

$withAction = $withAction ?? true;
?>
<table class="table table-bordered table-head-solid app-iconbox" id="<?= $tableId ?>">
    <thead>
        <tr>
            <th class="th-file">File</th>
            <?= Html::if($withAction, Html::tag('th', 'action', ['width' => 100, 'class' => 'text-center'])) ?>
        </tr>
    </thead>
    <tbody class="files-container">
        <?= $content ?>
    </tbody>
</table>