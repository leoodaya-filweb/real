<?php

namespace app\widgets;

class Value extends BaseWidget
{
    public $model;
    public $attribute;

    public $label;
    public $content;

    public function init()
    {
        parent::init();

        if ($this->model) {
            $this->label = $this->model->getAttributeLabel($this->attribute);
            $this->content = $this->model->{$this->attribute};
        }

        if ($this->content === 0) {
            # code...
        }
        else {
            $this->content = $this->content ?: 'None';
        }
    }

    public function run()
    {
        return $this->render('value', [
            'label' => $this->label,
            'content' => $this->content,
        ]);
    }
}
