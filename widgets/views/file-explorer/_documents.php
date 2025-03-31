<?php

use yii\helpers\FileHelper;
use app\helpers\Html;
?>

<div class="row">
    <?= Html::foreach($directories, function($folder, $folderName) use ($folderImage) {
        return $this->render('_folder', [
            'folder' => $folder,
            'folderName' => $folderName,
            'folderImage' => $folderImage,
        ]);
    }) ?>
    <?= $this->render('_create-folder', [
        'path' => $path,
        'addFolderImage' => $addFolderImage,
    ]) ?>

    <?= $this->render('_dropzone', [
        'path' => $path,
        'reloadUrl' => $reloadUrl,
        'widgetId' => $widgetId,
    ]) ?>
</div>

<div class="mt-10"></div>

<?php $this->beginContent('@app/views/file/_row-header.php'); ?>
    <?= Html::foreach($files, function($file) {
        return $this->render('/file/_row', ['model' => $file]);
    }) ?>
<?php $this->endContent(); ?>