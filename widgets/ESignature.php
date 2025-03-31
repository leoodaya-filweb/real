<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Url;

class ESignature extends BaseWidget
{
    public $uploadUrl = ['file/upload'];
    public $model;
    public $form;
    public $attribute;
    public $uploadSuccess;
    public $width = 500;
    public $height = 300;
    public $clearJs;

    public function init() 
    {
        // your logic here
        parent::init();

        $this->uploadUrl = Url::to($this->uploadUrl);
    }


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('e-signature', [
            'uploadUrl' => $this->uploadUrl,
            'width' => $this->width,
            'height' => $this->height,
            'uploadSuccess' => $this->uploadSuccess,
            'model' => $this->model,
            'form' => $this->form,
            'attribute' => $this->attribute,
            'clearJs' => $this->clearJs,
        ]);
    }
}
