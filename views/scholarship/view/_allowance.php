<?php

use app\helpers\Html;
?>

<tr>
    <td>
        <a href="#" class="text-dark text-hover-primary font-weight-bolder font-size-lg semester" data-id="<?= $allowance->id ?>">
            <?= $allowance->semester ?>
        </a>
        <div class="text-muted font-weight-bold">
            <?= $allowance->statusBadge ?>
        </div>
    </td>
    <td>
        <span class="">
            <?= $allowance->formattedAmount ?>
        </span>
    </td>
    <td>
        <span class="">
            <?= $allowance->date ?>
        </span>
    </td>
    <td>
        <div class="btn-group">
            <?= Html::button('<i class="fa fa-eye"></i>', [
                'class' => 'btn btn-icon btn-light-primary btn-sm semester',
                'data-id' => $allowance->id
            ]) ?>
            <?= Html::button('<i class="fa fa-trash"></i>', [
                'class' => 'btn btn-icon btn-light-danger btn-sm btn-remove-allowance',
                'data-delete-url' => $allowance->deleteUrl,
            ]) ?>
        </div>
    </td>
</tr>