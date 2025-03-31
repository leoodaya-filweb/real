<?php

namespace app\behaviors;

use app\models\SocialPensioner;
use yii\db\ActiveRecord;

class SocialPensionerBehavior extends \yii\base\Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'eventAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'eventBeforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'eventBeforeUpdate',
        ];
    }

    public function eventAfterFind($event)
    {
        $this->owner->is_pwd = $this->owner->pwd_score == SocialPensioner::PWD_SCORE ? true: false;
        $this->owner->is_senior = $this->owner->senior_score == SocialPensioner::SENIOR_SCORE ? true: false;
        $this->owner->is_solo_parent = $this->owner->solo_parent_score == SocialPensioner::SOLO_PARENT_SCORE ? true: false;
        $this->owner->is_solo_member = $this->owner->solo_member_score == SocialPensioner::SOLO_MEMBER_SCORE ? true: false;
    }

    public function setScore()
    {
        $this->owner->pwd_score = $this->owner->is_pwd ? SocialPensioner::PWD_SCORE: 0;
        $this->owner->senior_score = $this->owner->is_senior ? SocialPensioner::SENIOR_SCORE: 0;
        $this->owner->solo_parent_score = $this->owner->is_solo_parent ? SocialPensioner::SOLO_PARENT_SCORE: 0;
        $this->owner->solo_member_score = $this->owner->is_solo_member ? SocialPensioner::SOLO_MEMBER_SCORE: 0;
        $this->owner->accessibility_score = $this->owner->computeAccessibilityScore();
    }

    public function eventBeforeUpdate($event)
    {
        $this->setScore();
    }

    public function eventBeforeInsert($event)
    {
        $this->setScore();
    }
}