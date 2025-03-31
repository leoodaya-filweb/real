<?php

namespace app\behaviors;

use app\helpers\App;
use yii\db\ActiveRecord;

class SettingSubclassBehavior extends \yii\base\Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'eventBeforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'eventBeforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'eventBeforeUpdate',
            ActiveRecord::EVENT_INIT => 'eventInit',

        ];
    }

    public function eventInit($event)
    {
        $this->owner->type = $this->owner::TYPE;
    }

    public function eventBeforeValidate($event)
    {
        if ($this->owner->isNewRecord) {
            $this->eventBeforeInsert($event);
        }
        else {
            $this->eventBeforeUpdate($event);
        }
    }

    public function eventBeforeInsert($event)
    {
        $model = $this->owner::findOne([
            'name' => $this->owner->name,
            'type' => $this->owner::TYPE
        ]);

        if ($model) {
            $this->owner->addError('name', 'Name already exist.');

            $event->isValid = false;
        }
    }

    public function eventBeforeUpdate($event)
    {
        $model = $this->owner::find()
            ->where([
                'name' => $this->owner->name,
                'type' => $this->owner::TYPE
            ])
            ->andWhere(['<>', 'id', $this->owner->id])
            ->one();

        if ($model) {
            $this->owner->addError('name', 'Name already exist.');
            $event->isValid = false;
        }
    }
}