<?php

namespace app\behaviors;

use app\helpers\App;
use yii\db\ActiveRecord;

class ValueLabelSubclassBehavior extends \yii\base\Behavior
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
        $this->owner->var = $this->owner::VAR;
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
            'label' => $this->owner->label,
            'var' => $this->owner::VAR
        ]);

        if ($model) {
            $this->owner->addError('label', 'Label already exist.');

            $event->isValid = false;
        }
    }

    public function eventBeforeUpdate($event)
    {
        $model = $this->owner::find()
            ->where([
                'label' => $this->owner->label,
                'var' => $this->owner::VAR
            ])
            ->andWhere(['<>', 'id', $this->owner->id])
            ->one();

        if ($model) {
            $this->owner->addError('label', 'Label already exist.');
            $event->isValid = false;
        }
    }
}