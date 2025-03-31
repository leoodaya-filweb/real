<?php

namespace app\widgets;

 
class LatestEvents extends BaseWidget
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render("latest-events");
    }
}
