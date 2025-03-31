<?php

namespace app\models\query;

use app\models\SocialPensionEvent;

class SocialPensionEventQuery extends ActiveQuery
{
    public function social_pensioner()
    {
        return $this->andWhere([
            $this->field('category_type') => SocialPensionEvent::SOCIAL_PENSION_CATEGORY
        ]);
    }
   
    public function count($q = '*', $db = null)
    {
        $this->social_pensioner();
        return parent::count($q, $db);
    }

    public function all($db = null)
    {
        $this->social_pensioner();
        return parent::all($db);
    }

    public function one($db = null)
    {
        $this->social_pensioner();
        return parent::one($db);
    }

    public function sum($q, $db = null) 
    {
        $this->social_pensioner();
        return parent::sum($q, $db);
    }

    public function average($q, $db = null) 
    {
        $this->social_pensioner();
        return parent::average($q, $db);
    }

    public function min($q, $db = null) 
    {
        $this->social_pensioner();
        return parent::min($q, $db);
    }

    public function max($q, $db = null) 
    {
        $this->social_pensioner();
        return parent::max($q, $db);
    }

    public function scalar($db = null) 
    {
        $this->social_pensioner();
        return parent::scalar($db);
    }

    public function column($db = null) 
    {
        $this->social_pensioner();
        return parent::column($db);
    }

    public function exists($db = null) 
    {
        $this->social_pensioner();
        return parent::exists($db);
    }
}