<?php

namespace app\models\form;

use Yii;
use app\helpers\App;
use app\models\Household;
use app\models\HouseholdMember;
use app\models\Member;

class TransferToExistingHouseholdForm extends \yii\base\Model
{
    public $member_id;
    public $household_id;
    public $head;

    public $_household;
    public $_member;

    public function rules()
    {
        return [
            [['member_id', 'household_id', 'head'], 'required'],
            [['member_id', 'household_id', 'head'], 'integer'],
            ['member_id', 'exist', 'targetClass' => 'app\models\Member', 'targetAttribute' => 'id'],
            ['household_id', 'exist', 'targetClass' => 'app\models\Household', 'targetAttribute' => 'id'],
            ['head', 'default', 'value' => Member::FAMILY_HEAD_NO],
            ['head', 'in', 'range' => [
                Member::FAMILY_HEAD_YES,
                Member::FAMILY_HEAD_NO,
            ]],
        ];
    }

    public function init()
    {
        parent::init();
        $this->head = Member::FAMILY_HEAD_NO;
    }

    public function getHousehold()
    {
        if ($this->_household == null) {
            $this->_household = Household::findOne($this->household_id);
        }

        return $this->_household;
    }

    public function getMember()
    {
        if ($this->_member == null) {
            $this->_member = Member::findOne($this->member_id);
        }

        return $this->_member;
    }

    public function save()
    {
        if ($this->validate()) {
            $address = App::setting('address');

            $household = $this->getHousehold();
            $member = $this->getMember();
            $old_household_id = $member->household_id;
            
            $member->household_id = $household->id;
            $member->head = $this->head;

            if ($member->save()) {

                $household_member = new HouseholdMember([
                    'household_id' => $old_household_id,
                    'member_id' => $member->id,
                ]);

                if ($household_member->save()) {
                    return [
                        'household_member' => $household_member,
                        'household' => $household,
                        'member' => $member,
                    ];
                }
                else {
                    $this->addError('household_member', $household_member->errors);
                }
            }
            else {
                $this->addError('member', $member->errors);
            }
        }
    }
}