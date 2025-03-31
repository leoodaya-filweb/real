<?php

namespace app\behaviors;

use app\helpers\App;
use app\models\Budget;
use app\models\Event;
use app\models\EventMember;
use yii\db\ActiveRecord;

class EventBehavior extends \yii\base\Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'eventAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'eventBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'eventBeforeSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'eventAfterDelete',

            /*ActiveRecord::EVENT_AFTER_UPDATE => 'eventAfterUpdate',
            ActiveRecord::EVENT_AFTER_INSERT => 'eventAfterInsert',*/
        ];
    }

    public function eventAfterDelete($event)
    {
        EventMember::deleteAllRow(['event_id' => $this->owner->id]);
    }

    /*public function eventAfterInsert($event)
    {
        if ($this->owner->status == Event::ONGOING) {
            $budget = new Budget();
            $budget->subtract($this->owner->budget);
        }
    }

    public function eventAfterUpdate($event)
    {
        if (isset($event->changedAttributes['status'])) {
            if ($this->owner->status == Event::ONGOING) {
                $budget = new Budget();
                $budget->subtract($this->owner->budget);
            }
        }
    }*/

    public function eventAfterFind($event)
    {
        $this->owner->date_from = date('m/d/Y', strtotime($this->owner->date_from));
        $this->owner->date_to = date('m/d/Y', strtotime($this->owner->date_to));

        $this->owner->oneday = $this->owner->date_from == $this->owner->date_to;
    }

    public function eventBeforeSave($event)
    {
        $this->owner->date_from = date('Y-m-d', strtotime($this->owner->date_from));
        $this->owner->date_to = date('Y-m-d', strtotime($this->owner->date_to));
    }
}