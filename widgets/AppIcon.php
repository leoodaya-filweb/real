<?php

namespace app\widgets;

class AppIcon extends BaseWidget
{
    public $icon;
    public $iconClass;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->icon == null) {
            return ;
        }
        return $this->render("icon/{$this->icon}", [
            'iconClass' => $this->iconClass
        ]);
    }
}
