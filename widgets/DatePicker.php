<?php

namespace app\widgets;

class DatePicker extends BaseWidget
{
    public $form;
    public $model;
    public $attribute;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('date-picker/active-input', [
            'form' => $this->form,
            'model' => $this->model,
            'attribute' => $this->attribute,
        ]);
    }
}
