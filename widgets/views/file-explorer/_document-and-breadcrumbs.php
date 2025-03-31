<div class="document-breadcrumbs">
    <?= $this->render('_breadcrumbs', [
        'breadcrumbs' => $breadcrumbs,
    ]) ?>
</div>

<div class="documents-container">
    <?= $this->render('_documents', [
        'directories' => $directories,
        'files' => $files,
        'path' => $path,
        'reloadUrl' => $reloadUrl,
        'widgetId' => $widgetId,
        'folderImage' => $folderImage,
        'addFolderImage' => $addFolderImage,
    ]) ?>
</div>
