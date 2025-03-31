<?php

namespace app\widgets;

use app\models\Member;

class SearchQrCode extends BaseWidget
{
    public $model;
    public $title = 'Search QR Code';
    public $template = '_add-transaction';
    public $modalTitle = 'Add Transaction:';

    public function init()
    {
        parent::init();

        $this->model = new Member();
    }

    public function run()
    {
        return $this->render("search-qr-code", [
            'model' => $this->model,
            'title' => $this->title,
            'template' => $this->template,
            'modalTitle' => $this->modalTitle,
        ]);
    }
}
