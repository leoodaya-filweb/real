<?php

namespace app\models\query;

use app\models\EventMember;

/**
 * This is the ActiveQuery class for [[\app\models\EventMember]].
 *
 * @see \app\models\EventMember
 */
class EventMemberQuery extends ActiveQuery
{
    public function received()
    {
        return $this->andWhere([
            $this->field('status') => [
                EventMember::UNCLAIM,
                EventMember::UNATTENDED,
            ]
        ]);
    }
}