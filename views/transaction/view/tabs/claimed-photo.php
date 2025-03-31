<?php

use app\helpers\App;
use app\helpers\Html;
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => 'Claimed Photo',
    'toolbar' => <<< HTML
        <div class="card-toolbar">
            <div class="lead">Claimed At: {$model->claimedAt}</div>
        </div>
    HTML
]); ?>
    <div class="row">
        <div class="col-md-5">
            <?= Html::image($model->social_pension_photo, ['w' => 500], [
                'class' => 'img-fluid'
            ]) ?>
        </div>
        <div class="col-md-7">
            <?= Html::if(($file = $model->claimedPhoto) != null, function() use($file) {
                return <<< HTML
                    <table class="table table-bordered font-size-sm">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td> {$file->name} </td>
                            </tr>
                            <tr>
                                <th>Extension</th>
                                <td> {$file->extension} </td>
                            </tr>
                            <tr>
                                <th>Size</th>
                                <td> {$file->fileSize} </td>
                            </tr>
                            <tr>
                                <th>Width</th>
                                <td> {$file->width} </td>
                            </tr>
                            <tr>
                                <th>Height</th>
                                <td> {$file->height} </td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td> {$file->location} </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td> {$file->createdAt} </td>
                            </tr>
                            <tr>
                                <th>Download</th>
                                <td> <a href="{$file->downloadUrl}" class="btn btn-sm btn-light-primary font-weight-bolder">Download</a> </td>
                            </tr>
                        </tbody>
                    </table>
                HTML;
            }) ?>
            
        </div>
    </div>

<?php $this->endContent(); ?>