<?php

namespace app\models\query;

use app\models\Event;

/**
 * This is the ActiveQuery class for [[\app\models\Event]].
 *
 * @see \app\models\Event
 */
class EventsQuery extends ActiveQuery
{
    public function planned()
    {
        return $this->andWhere([
            $this->field('attendees_type') => Event::PLANNED,
        ]);
    }

    public function unplanned()
    {
        return $this->andWhere([
            $this->field('attendees_type') => Event::UN_PLANNED,
        ]);
    }
}