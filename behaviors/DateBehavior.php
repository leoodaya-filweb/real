<?php

namespace app\behaviors;

use yii\db\ActiveRecord;

class DateBehavior extends \yii\base\Behavior
{
    public $inFormat = 'Y-m-d';
    public $outFormat = 'm/d/Y';

    public $attributes = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'eventBeforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'eventBeforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'eventBeforeUpdate',
        ];
    }

    public function formatDates($format = '')
    {
        $format = $format ?: $this->inFormat;

        foreach ($this->attributes as $attribute) {
            if ((int)$this->owner->{$attribute}) {
                $this->owner->{$attribute} = date($format, strtotime($this->owner->{$attribute}));
            }
        }
    }

    public function eventBeforeValidate($event)
    {
        $this->formatDates();
    }

    public function afterFind($event)
    {
        $this->formatDates($this->outFormat);
    }

    public function eventBeforeInsert($event)
    {
        $this->formatDates();
    }

    public function eventBeforeUpdate($event)
    {
        $this->formatDates();
    }
}