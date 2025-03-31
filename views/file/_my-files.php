<?php

use app\helpers\App;
use app\helpers\Url;
use yii\helpers\StringHelper;

$params = [
    'class' => "img-thumbnail pointer",
    'loading' => 'lazy',
    'data-id' => $model->id,
    'data-name' => $model->name,
    'data-extension' => $model->extension,
    'data-size' => $model->fileSize,
    'data-width' => $model->width,
    'data-height' => $model->height,
    'data-location' => $model->location,
    'data-token' => $model->token,
    'data-mimetype' => $model->mimeType,
    'data-viewer_url' => $model->viewerUrl,
    'data-created_at' => App::formatter('asFulldate', $model->created_at),
    'data-created_by' => $model->createdByEmail,
    'title' => $model->name,
    'data-can-delete' => 'false',
    // 'data-can-delete' => $model->canDelete ? 'true': 'false',
    'data-download-url' => Url::to(['file/download', 'token' => $model->token], true),
];
?>

<?= $model->show($params) ?>
<p>
    <?= StringHelper::truncate($model->name, 15) ?>
</p>

