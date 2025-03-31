<?php

namespace app\models\form;

use Yii;
use app\models\ActiveRecord;
use app\models\Household;
use app\models\Member;

class HouseholdSummaryForm extends \yii\base\Model
{
    public $household_no;
    public $head_id;
    public $members_id;

    public $_household;
    public $_head;
    public $_members;

    public function rules()
    {
        return [
            [['household_no', 'head_id'], 'required'],
            [['household_no', 'head_id'], 'integer'],
            ['members_id', 'validateMembersId'],
            ['household_no', 'exist', 'targetClass' => 'app\models\Household', 'targetAttribute' => 'no'],
            ['head_id', 'exist', 'targetClass' => 'app\models\Member', 'targetAttribute' => 'id'],
        ];
    }

    public function validateMembersId($attribute, $params)
    {
        if (($members_id = $this->members_id) != null) {
            if (is_array($members_id)) {
                $count = Member::find()
                    ->where(['id' => $members_id])
                    ->count();

                if ($count != count($members_id)) {
                    $this->addError($attribute, 'There\'s a member id that does not exist.');
                }
            }
            else {
                if (($m = Member::findOne($members_id)) == null) {
                    $this->addError($attribute, 'Member don\'t exist');
                }
            }
        }
    }

    public function getHousehold()
    {
        if ($this->_household == null) {
            $this->_household = Household::findOne(['no' => $this->household_no]);
        }

        return $this->_household;
    }

    public function getHead()
    {
        if ($this->_head == null) {
            $this->_head = Member::findOne($this->head_id);
        }

        return $this->_head;
    }

    public function getMembers()
    {
        if ($this->_members == null) {
            $this->_members = Member::findAll($this->members_id);
        }

        return $this->_members;
    }

    public function save()
    {
        if ($this->validate()) {

            $household = $this->getHousehold();
            $household->record_status = ActiveRecord::RECORD_ACTIVE;

            $head = $this->getHead();

            if ($household->save()) {

                $head->record_status = ActiveRecord::RECORD_ACTIVE;

                if ($head->save()) {
                    $response = true;

                    if (($members = $this->getMembers()) != null) {
                        foreach ($members as $member) {
                            $member->record_status = ActiveRecord::RECORD_ACTIVE;
                            if ($member->save()) {
                                
                            }
                            else {
                                $response = false;
                                $this->addError('member', $member->errors);
                            }
                        }
                    }

                    return $response;
                }
                else {
                    $this->addError('head', $head->errors);
                }
            }
            else {
                $this->addError('household', $household->errors);
            }
        }

        return false;
    }

    public function getHouseholdNo()
    {
        return $this->household_no;
    }
}