<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\File;
use app\widgets\ImageGallery;

$file = File::findByToken($model->{$attribute});
?>

<tr>
    <td>
        <div class="d-flex">
            <div>
                <?= Html::image($model->{$attribute}, ['w' => 120], [
                    'class' => "img-thumbnail {$attribute} mw-120",
                    'loading' => 'lazy',
                ]) ?>
            </div>
            <div>
                <div class="ml-4">
                    <b><?= strtoupper(str_replace('_', ' ', $attribute)) ?></b>
                    <br><?= $file ? $file->nameWithExtension: '' ?>
                    <br><?= $file ? App::formatter('asFileSize', $file->size): '' ?>
                </div>
            </div>
        </div>
    </td>
    <td class="text-center">
        <?= ImageGallery::widget([
            'tag' => 'Setting',
            'model' => $model,
            'fixedSize' => false,
            'attribute' => $attribute,
            'ajaxSuccess' => <<< JS
                if(s.status == 'success') {
                    $('img.{$attribute}').attr('src', s.src);
                }
            JS
        ]) ?> 
    </td>
</tr>