<?php

namespace app\models\query;

use app\models\Masterlist;

/**
 * This is the ActiveQuery class for [[\app\models\Masterlist]].
 *
 * @see \app\models\Masterlist
 */
class MasterlistQuery extends ActiveQuery
{
    public function added()
    {
        return $this->andWhere([$this->field('status') => Masterlist::ADDED]);
    }

    public function count($q = '*', $db = null)
    {
        $this->added();
        return parent::count($q, $db);
    }

    public function all($db = null)
    {
        $this->added();
        return parent::all($db);
    }

    public function one($db = null)
    {
        $this->added();
        return parent::one($db);
    }

    public function sum($q, $db = null) 
    {
        $this->added();
        return parent::sum($q, $db);
    }

    public function average($q, $db = null) 
    {
        $this->added();
        return parent::average($q, $db);
    }

    public function min($q, $db = null) 
    {
        $this->added();
        return parent::min($q, $db);
    }

    public function max($q, $db = null) 
    {
        $this->added();
        return parent::max($q, $db);
    }
}