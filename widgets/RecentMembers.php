<?php

namespace app\widgets;

 
class RecentMembers extends BaseWidget
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render("recent-members");
    }
}
