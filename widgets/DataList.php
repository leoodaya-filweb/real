<?php

namespace app\widgets;

class DataList extends BaseWidget
{
    public $form;
    public $model;
    public $attribute;
    public $data;
    public $sort = true;

    public function init()
    {
        parent::init();

        if ($this->sort) {
            sort($this->data);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('date-list/active-input', [
            'form' => $this->form,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'data' => $this->data,
        ]);
    }
}
