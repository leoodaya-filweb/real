<?php

namespace app\widgets;

use app\helpers\App;
use app\widgets\Anchor;
 
class AnchorBack extends BaseWidget
{
    public $title = 'Cancel';
    public $options = ['class' => 'btn btn-light-danger font-weight-bold'];
    public $link;
    public $tooltip;
    public $size;

    public function init() 
    {
        // your logic here
        parent::init();
        $this->link = $this->link ?: App::referrer();


        $this->options['class'] .= ' btn-' . $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return Anchor::widget([
            'title' => $this->title,
            'link' => $this->link,
            'options' => $this->options,
            'tooltip' => $this->tooltip,
        ]);
    }
}
