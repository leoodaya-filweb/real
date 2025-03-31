<?php

namespace app\widgets;

class Iconbox extends BaseWidget
{
    public $url = '#';
    public $title;
    public $content;
    public $iconContent;
    public $anchorOptions = [
        'class' => 'text-dark text-hover-primary font-weight-bold font-size-h4 mb-3'
    ];

    public $wrapperClass = 'card card-custom';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('iconbox', [
            'title' => $this->title,
            'content' => $this->content,
            'iconContent' => $this->iconContent,
            'wrapperClass' => $this->wrapperClass,
            'url' => $this->url,
            'anchorOptions' => $this->anchorOptions,
        ]);
    }
}
