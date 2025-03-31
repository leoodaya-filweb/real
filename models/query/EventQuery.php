<?php

namespace app\models\query;

use app\models\Event;

/**
 * This is the ActiveQuery class for [[\app\models\Event]].
 *
 * @see \app\models\Event
 */
class EventQuery extends ActiveQuery
{
    public function default_category()
    {
        return $this->andWhere([
            $this->field('category_type') => Event::DEFAULT_CATEGORY,
        ]);
    }

    public function unplanned_category()
    {
        return $this->andWhere([
            $this->field('category_type') => Event::UN_PLANNED_CATEGORY,
        ]);
    }

    public function social_pension_category()
    {
        return $this->andWhere([
            $this->field('category_type') => Event::SOCIAL_PENSION_CATEGORY,
        ]);
    }
}