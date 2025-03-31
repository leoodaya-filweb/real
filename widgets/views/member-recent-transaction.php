<?php

use app\helpers\Html;

$this->registerCss(<<< CSS
    
    #member-recent-transaction-{$widgetId}_filter {
    	text-align: right;
    }
    #member-recent-transaction-{$widgetId}_filter label,
    #member-recent-transaction-{$widgetId}_length label {
        display: inline-flex;
    }

    #member-recent-transaction-{$widgetId}_filter input {
        margin-top: -5px;
        margin-left: 5px;
    }
    #member-recent-transaction-{$widgetId}_length select{
        margin-top: -5px;
        margin-left: 5px;
        margin-right: 5px;
    }
    #member-recent-transaction-{$widgetId}_paginate {
        float: right;
    }

CSS);

$this->registerWidgetJs($widgetFunction, <<< JS
    $('#member-recent-transaction-{$widgetId}').DataTable();
JS);
?>

<table class="table table-bordered table-head-solid" id="member-recent-transaction-<?= $widgetId ?>">
    <thead>
        <tr>
            <th>#</th>
            <th>transaction type</th>
            <th width="100">status</th>
            <th class="text-right" width="100">date</th>
            <th class="text-center" width="100">action</th>
        </tr>
    </thead>
    <tbody>
        <?= Html::if($transactions, function() use($transactions) {

            return Html::foreach($transactions, function($transaction, $key) {
                $serial = ($key + 1);
                return <<< HTML
                    <tr>
                        <td>{$serial}</td>
                        <td>{$transaction->transactionTypeTag}</td>
                        <td>{$transaction->statusBadge}</td>
                        <td class="text-right">{$transaction->date}</td>
                        <td class="text-center">{$transaction->viewBtn}</td>
                    </tr>
                HTML;
            });
        }) ?>
    </tbody>
</table>