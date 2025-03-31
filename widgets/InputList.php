<?php

namespace app\widgets;

use app\helpers\App;
 
class InputList extends BaseWidget
{
    public $label;
    public $name;
    public $data;
    public $type = 'input';
    public $inputType = 'text';

    public function run()
    {
        return $this->render('input-list', [
            'label' => $this->label,
            'name' => $this->name,
            'data' => $this->data,
            'type' => $this->type,
            'inputType' => $this->inputType,
        ]);
    }
}
