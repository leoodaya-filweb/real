<?php

namespace app\widgets;

use app\widgets\AnchorBack;
use app\helpers\Html;
 
class AnchorForm extends BaseWidget
{
    public $glue = ' ';
    public $submitLabel = 'Save';
    public $size;

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
        $anchors = [
            Html::submitButton($this->submitLabel, [
                'class' => 'mr-2 btn btn-success font-weight-bold btn-' . $this->size,
                'name' => 'confirm_button',
                'value' => $this->submitLabel
            ]),
            AnchorBack::widget(['size' => $this->size]),
        ];

        return implode($this->glue, $anchors);
    }
}
