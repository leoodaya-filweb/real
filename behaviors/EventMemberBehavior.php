<?php

namespace app\behaviors;

use app\models\Member;
use yii\db\ActiveRecord;

class EventMemberBehavior extends \yii\base\Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'eventBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'eventBeforeSave',
        ];
    }

    public function eventBeforeSave($event)
    {
        if (($member = Member::findOne($this->owner->member_id)) != null) {
            $household = $member->household;

            $this->owner->name                   = $member->fullname; 
            $this->owner->qr_id                  = $member->qr_id; 
            $this->owner->household_no           = $member->householdNo; 
            $this->owner->family_head            = $member->head; 
            $this->owner->solo_parent            = $member->solo_parent;
            $this->owner->solo_member            = $member->solo_member;
            $this->owner->gender                 = $member->genderName;
            $this->owner->civil_status           = $member->civilStatusName;
            $this->owner->educational_attainment = $member->educationalAttainmentLabel;
            $this->owner->pwd                    = $member->pwd;
            $this->owner->pwd_type               = $member->pwdTypeName;
            $this->owner->barangay               = $member->barangayName;
            $this->owner->purok_no               = $household->purok_no;
            $this->owner->age                    = $member->currentAge;
        }
    }
}