<?php

namespace app\widgets;

class Table extends BaseWidget
{
    public $th;
    public $td;

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
        return $this->render('table', [
            'th' => $this->th,
            'td' => $this->td,
        ]);
    }
}
