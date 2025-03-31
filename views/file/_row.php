<?php

use app\helpers\Html;

$withAction = $withAction ?? true;
?>
<tr>
    <td>
        <?= $this->render('_row-filename', [
            'model' => $model
        ]) ?>
    </td>
    <?= Html::if(
        $withAction, 
        Html::tag('td', $this->render('_row-actions', ['model' => $model ]), ['class' => 'text-center'])
    ) ?>
</tr>