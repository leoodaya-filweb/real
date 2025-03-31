<?php

namespace app\behaviors;

use app\helpers\App;
use app\models\Member;
use app\models\Database;
use yii\db\ActiveRecord;
use app\models\Household;
use yii\helpers\ArrayHelper;

class MemberBehavior extends \yii\base\Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'eventAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'eventBeforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'eventBeforeUpdate',
            ActiveRecord::EVENT_AFTER_UPDATE => 'eventAfterUpdate',
        ];
    }

    public function filterSkills()
    {
        if ($this->owner->skills) {
            $skills = json_decode($this->owner->skills, true);
            $skills = array_unique(array_map('trim', $skills));
            $this->owner->skills = json_encode(array_filter($skills));
        }
    }

    public function isSoloMember()
    {
        $members = Member::find()
            ->where(['household_id' => $this->owner->household_id])
            ->count();
        
        if ($members > 1) {
            Member::updateAll(
                ['solo_member' => Member::SOLO_MEMBER_NO],
                ['household_id' => $this->owner->household_id],
            );
            $this->owner->solo_member = Member::SOLO_MEMBER_NO;
        }
        else {
            $this->owner->solo_member = Member::SOLO_MEMBER_YES;
        }
    }

    public function eventBeforeInsert($event)
    {
        $model = $this->owner;

        $this->owner->qr_id = $model->qr_id ?: $this->createQRId();

        if ($model->isHead) {
            if (($heads = Member::findAllHead($model->household_id)) != null) {
                Member::updateAll(['head' => Member::FAMILY_HEAD_NO], [
                    'id' => array_keys(ArrayHelper::map($heads, 'id', 'id'))
                ]);
            }
        }

        $this->filterSkills();
        $this->isSoloMember();
    }

    public function eventBeforeUpdate($event)
    {
        $model = $this->owner;
        $this->owner->qr_id = $model->qr_id ?: $this->createQRId();

        if ($model->isHead) {
            if (($heads = Member::findAllHeadExcept($model->id, $model->household_id)) != null) {
                Member::updateAll(['head' => Member::FAMILY_HEAD_NO], [
                    'id' => array_keys(ArrayHelper::map($heads, 'id', 'id'))
                ]);
            }
        }

        $this->filterSkills();
        $this->isSoloMember();
    }

    public function createQRId()
    {
        $qr_id = implode('-', [
            $this->owner->initial,
            time()
        ]);

        if (($member = Member::findOne(['qr_id' => $qr_id])) != null) {
            return $this->createQRId();
        }
        
        return $qr_id;
    }

    public function eventAfterFind($event)
    {
        $this->owner->last_name = strtoupper($this->owner->last_name);
        $this->owner->middle_name = strtoupper($this->owner->middle_name);
        $this->owner->first_name = strtoupper($this->owner->first_name);
    }

    public function eventAfterUpdate($event)
    {
        if (($database = $this->owner->database) != null) {
            if ($this->owner->isActive) {
                Database::updateAll([
                    'record_status' => Database::RECORD_ACTIVE,
                    'status' => 'Active'
                ], ['id' => $database->id]);
            }
            else {
                Database::updateAll([
                    'record_status' => Database::RECORD_INACTIVE,
                    'status' => 'Inactive'
                ], ['id' => $database->id]);
            }
        }
    }
}