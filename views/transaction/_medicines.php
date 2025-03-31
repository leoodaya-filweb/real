<?php

use app\helpers\Html;
?>

<table class="table table-bordered table-head-solid">
    <thead>
        <tr>
            <th width="50">#</th>
            <th>Name</th>
            <th width="150" class="text-right">Quantity</th>
            <th>Unit</th>
            <th class="text-center" width="100">Action</th>
        </tr>
    </thead>
    <tbody>
        <?= Html::if(($medicines = $model->medicines) != null, function() use($medicines) {
            return Html::foreach($medicines, function($medicine, $key) {
                $serial = $key + 1;
                return <<< HTML
                    <tr>
                        <td>{$serial}</td>
                        <td>{$medicine->name}</td>
                        <td class="text-right">{$medicine->quantity}</td>
                        <td>{$medicine->unit}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-light-primary btn-sm btn-icon btn-edit-medicine" data-id="{$medicine->id}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-light-danger btn-sm btn-icon btn-delete-medicine" data-id="{$medicine->id}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                HTML;
            });
        }) ?>
    </tbody>
</table>