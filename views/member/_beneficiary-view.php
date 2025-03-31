<?php

use app\helpers\Html;
?>

<div class='d-flex'>
    <?= Html::tag('div', 
        Html::image($model->photo, [
            'w' => 50, 
            'h' => 50, 
            'ratio' => 'false', 
            'quality' => 90]
        ), [
            'class' => 'symbol mr-3',
            'style' => 'width:50px;',
            'loading' => 'lazy'
        ]
    ) ?>
    <div>
        <?= implode('<br>', [
            Html::a($model->fullname, $model->viewUrl, [
                'class' => 'text-dark-75 font-weight-bold beneficiary-name'
            ]),
            Html::tag('small', "{$model->householdNo} | {$model->qr_id} | " .  Html::tag('label', "<a target='_blank' href='{$model->viewUrl}'>Profile</a>", [
                'class' => 'badge badge-secondary'
            ])),

        ]) ?>
    </div>
</div>
