<?php

namespace app\widgets;

class HouseholdDetail extends BaseWidget
{
    public $model;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('household-detail', [
            'model' => $this->model
        ]);
    }
}
