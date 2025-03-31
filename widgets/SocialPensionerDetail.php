<?php

namespace app\widgets;

class SocialPensionerDetail extends BaseWidget
{
    public $model;
    public $template = 'default';

    public function init() 
    {
        // your logic here
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render("social-pensioner/{$this->template}", [
            'model' => $this->model,
        ]);
    }
}
