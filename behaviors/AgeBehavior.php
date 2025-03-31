<?php

namespace app\behaviors;

use app\helpers\App;
use yii\db\ActiveRecord;

class AgeBehavior extends \yii\base\Behavior
{
    public $ageAttribute = 'age';
    public $dateAttribute = 'birth_date';
    public $condition = true;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'eventBeforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'eventBeforeUpdate',
        ];
    }

    public function setTheAge()
    {
        $condition = is_callable($this->condition) ? call_user_func($this->condition, $this->owner): $this->condition;
        
        if ($condition) {
            $this->owner->{$this->ageAttribute} = App::formatter(
                'AsAge', 
                $this->owner->{$this->dateAttribute}
            );
        }
    }

    public function eventBeforeInsert($event)
    {
        $this->setTheAge();
    }

    public function eventBeforeUpdate($event)
    {
        $this->setTheAge();
    }
}