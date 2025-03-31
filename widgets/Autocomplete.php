<?php

namespace app\widgets;

use Yii;
use app\helpers\Html;
use app\helpers\Url;
 
class Autocomplete extends BaseWidget
{
    public $input;
    public $url;
    public $data = [];
    public $submitOnclick = true;
    public $submitOnclickJs = "$(this).closest('form').submit();";

    public function init() 
    {
        // your logic here
        parent::init();
        $this->url = $this->url ?: Url::to(['find-by-keywords']);
        $this->data = json_encode($this->data);

        $this->input = $this->input ?: Html::input('text', 'autocomplete', '', [
            'class' => 'form-control form-control-lg',
            'placeholder' => 'Type to search'
        ]);
    }

    public function ajax()
    {
        return Url::userCanRoute($this->url) ? 'true': 'false';
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('autocomplete', [
            'input' => $this->input,
            'url' => $this->url,
            'data' => $this->data,
            'submitOnclickJs' => $this->submitOnclickJs,
            'submitOnclick' => $this->submitOnclick ? 'true': 'false',
            'ajax' => $this->ajax(),
        ]);
    }
}
