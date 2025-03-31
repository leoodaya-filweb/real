<div class="btn-group" role="group" aria-label="Basic example">
    <a href="<?= $model->viewerUrl ?>" target="_blank" class="btn btn-light-primary btn-sm btn-icon btn-view-file">
        <i class="fa fa-eye"></i>
    </a>
    <button data-token="<?= $model->token ?>" data-name="<?= $model->name ?>" type="button" class="btn btn-light-warning btn-sm btn-icon btn-edit-file">
        <i class="fa fa-edit"></i>
    </button>
    <button type="button" data-token="<?= $model->token ?>" class="btn btn-light-danger btn-sm btn-icon btn-remove-file">
        <i class="fa fa-trash"></i>
    </button>
</div>