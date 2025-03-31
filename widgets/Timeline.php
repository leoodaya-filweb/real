<?php

namespace app\widgets;

class Timeline extends BaseWidget
{
    public $template = 'transaction-logs';
    public $model;

    public function run()
    {
        return $this->render("timeline/{$this->template}", [
            'model' => $this->model
        ]);
    }
}
