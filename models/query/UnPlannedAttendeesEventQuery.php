<?php

namespace app\models\query;

use app\models\SocialPensionEvent;

class UnPlannedAttendeesEventQuery extends ActiveQuery
{
    public function unplanned()
    {
        return $this->andWhere([
            $this->field('category_type') => SocialPensionEvent::UN_PLANNED_CATEGORY
        ]);
    }
   
    public function count($q = '*', $db = null)
    {
        $this->unplanned();
        return parent::count($q, $db);
    }

    public function all($db = null)
    {
        $this->unplanned();
        return parent::all($db);
    }

    public function one($db = null)
    {
        $this->unplanned();
        return parent::one($db);
    }

    public function sum($q, $db = null) 
    {
        $this->unplanned();
        return parent::sum($q, $db);
    }

    public function average($q, $db = null) 
    {
        $this->unplanned();
        return parent::average($q, $db);
    }

    public function min($q, $db = null) 
    {
        $this->unplanned();
        return parent::min($q, $db);
    }

    public function max($q, $db = null) 
    {
        $this->unplanned();
        return parent::max($q, $db);
    }

    public function scalar($db = null) 
    {
        $this->unplanned();
        return parent::scalar($db);
    }

    public function column($db = null) 
    {
        $this->unplanned();
        return parent::column($db);
    }

    public function exists($db = null) 
    {
        $this->unplanned();
        return parent::exists($db);
    }
}