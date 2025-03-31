<?php

namespace app\widgets;

 
class RecentHouseholds extends BaseWidget
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render("recent-households");
    }
}
