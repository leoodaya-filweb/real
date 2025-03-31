<?php

use app\helpers\Html;
?>

<div class="member-fields-container">
    <h4 class="mb-10 font-weight-bold text-dark">
        Family Head Details
        <?= Html::a('<i class="fa fa-edit text-warning"></i>', ['update', 'no' => $model->no, 'step' => 'family-head'], [
            'data-toggle' => 'tooltip',
            'title' => 'Edit'
        ]) ?>
    </h4>
    <?= Html::if($head, function() use($head) {
        return $head->detailView;
    }) ?>
</div>
