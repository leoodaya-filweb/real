<?php

$this->registerCss(<<< CSS
    .image-input [data-action="rename"] {
        position: absolute;
        right: 15px;
        top: -10px;
    }
CSS);
?>


<div class="image-input image-input-outline file-container">
    <label data-token="<?= $file->token ?>" class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow btn-remove-file" data-action="change" data-toggle="tooltip" title="" data-original-title="Remove">
        <i class="fa fa-trash icon-sm text-danger"></i>
    </label>

    <label data-token="<?= $file->token ?>" class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow btn-rename-file" data-action="rename" data-toggle="tooltip" title="" data-original-title="Remove">
        <i class="fa fa-edit icon-sm text-primary"></i>
    </label>
    <div class="image-input-wrapper" style="background-image: url(<?= $file->displayPath ?>); width: 200px"></div>
    <a class="document-view" target="_blank" title="View Document" data-toggle="popover"  data-content="<?= $file->name ?>" href="<?= $file->displayPath ?>" id="document-id-<?= $file->id ?>">
        <p class="badge badge-secondary mb-1"><?= $file->truncatedName ?></p>
    </a>
</div>
