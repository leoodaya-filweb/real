<?php

use app\helpers\App;
?>

<div class="d-flex">
    <div>
        <?= $model->show([
            'class' => 'img-fluid',
            'loading' => 'lazy',
            'width' => 100,
            'style' => 'border-radius: 4px;width: 80px; height: 80px'
        ], 100) ?>
    </div>
    <div>
        <div class="ml-4">
            <span class="app-hidden"><?= strtotime($model->created_at) ?></span>
            <?= App::formatter('asFulldate', $model->created_at) ?>
            <br><b id="file-<?= $model->id ?>"><?= strtoupper($model->name) ?></b>
            <br><?= $model->fileSize ?>
            <br><?= strtoupper($model->extension) ?>
        </div>
    </div>
</div>