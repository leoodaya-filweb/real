<?php

namespace app\modules\chat\components;

use yii\helpers\Json;
use app\modules\chat\helpers\Url;
use app\modules\chat\helpers\App;
use app\modules\chat\models\File;

class ViewComponent extends \app\components\ViewComponent
{
    public function init()
    {
        parent::init();

        $this->registerJsVar('chatModule', [
            'myRequestUrl' => Url::toRoute(['/request/my-request']),
            'appUrl' => Url::base() . '/',
            'baseUrl' => Url::base() . '/community-board/',
            'csrfToken' => App::request('csrfToken'),
            'csrfParam' => App::request('csrfParam'),
            'fileUploadUrl' => Url::toRoute(['/file/upload']),
            'acceptedFiles' => array_map(fn($val) => ".{$val}", array_merge(
                File::EXTENSIONS['image'],
                File::EXTENSIONS['file']
            )),
            'imageAcceptedFiles' => array_map(fn($val) => ".{$val}", File::EXTENSIONS['image'])
        ]);
    }
}