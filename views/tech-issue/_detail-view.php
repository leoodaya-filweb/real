<?php

use app\widgets\Detail;
?>
<div class="row">
    <div class="col-md-6">
        <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
            'title' => 'General Information',
            'stretch' => true
        ]) ?>
            <?= Detail::widget([
                'model' => $model,
                'options' => [
                    'class' => 'detail-view table table-active table-bordered table-striped mt-0'
                ]
            ]) ?>
        <?php $this->endContent() ?>
    </div>
    <div class="col-md-6">
        <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
            'title' => 'Logs',
            'stretch' => true
        ]) ?>
            <?= $this->render('_logs', [
                'model' => $model
            ]) ?>
        <?php $this->endContent() ?>
    </div>
</div>