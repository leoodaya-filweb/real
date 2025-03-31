<?php

namespace app\widgets;

class ColumnChart extends BaseWidget
{
    public $template = 'default';
    public $data;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render("column-chart/{$this->template}", [
            'data' => json_encode($this->data)
        ]);
    }
}
