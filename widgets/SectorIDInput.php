<?php

namespace app\widgets;

use app\models\Member;

class SectorIDInput extends BaseWidget
{
    public $priority_sector;
    public $form;
    public $model;
    public $label;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render("sector-id-input", [
            'priority_sector' => $this->priority_sector,
            'model' => $this->model,
            'form' => $this->form,
            'label' => $this->label,
        ]);
    }
}
