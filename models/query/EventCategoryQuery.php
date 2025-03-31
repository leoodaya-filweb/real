<?php

namespace app\models\query;

use app\models\EventCategory;

/**
 * This is the ActiveQuery class for [[\app\models\EventCategory]].
 *
 * @see \app\models\EventCategory
 */
class EventCategoryQuery extends ActiveQuery
{
    public function type()
    {
        return $this->andWhere([$this->field('type') => EventCategory::TYPE]);
    }

    public function count($q = '*', $db = null)
    {
        $this->type();
        return parent::count($q, $db);
    }

    public function all($db = null)
    {
        $this->type();
        return parent::all($db);
    }

    public function one($db = null)
    {
        $this->type();
        return parent::one($db);
    }

    public function sum($q, $db = null) 
    {
        $this->type();
        return parent::sum($q, $db);
    }

    public function average($q, $db = null) 
    {
        $this->type();
        return parent::average($q, $db);
    }

    public function min($q, $db = null) 
    {
        $this->type();
        return parent::min($q, $db);
    }

    public function max($q, $db = null) 
    {
        $this->type();
        return parent::max($q, $db);
    }
}
