<?php

namespace app\models\form;

use Yii;
use app\helpers\App;
use app\models\Household;
use app\models\HouseholdMember;
use app\models\Member;

class TransferToNewHouseholdForm extends \yii\base\Model
{
    public $member_id;
    public $head;

    public $barangay_id;
    public $street;
    public $zone_no;
    public $blk_no;
    public $lot_no;
    public $household_no;
    public $transfer_date;
    public $purok_no;

    public $sitio;
    public $landmark;
    public $files = [];


    public $longitude;
    public $latitude;
    public $altitude;

    public $_member;
    public $_household;

    public function rules()
    {
        return [
            [['member_id', 'household_no', 'transfer_date', 'barangay_id', 'longitude', 'latitude',], 'required'],
            [['member_id', 'household_no', 'barangay_id', 'head', 'lot_no', 'zone_no'], 'integer'],
            [['purok_no', 'lot_no', 'zone_no', 'sitio', 'landmark', 'files'], 'safe'],
            [['transfer_date'], 'string'],
            ['member_id', 'exist', 'targetClass' => 'app\models\Member', 'targetAttribute' => 'id'],
            ['barangay_id', 'exist', 'targetClass' => 'app\models\Barangay', 'targetAttribute' => 'id'],
            [['blk_no'], 'string', 'max' => 32],
            ['head', 'default', 'value' => Member::FAMILY_HEAD_YES],
            ['head', 'in', 'range' => [
                Member::FAMILY_HEAD_YES,
                Member::FAMILY_HEAD_NO,
            ]],
            ['household_no', 'validateHouseholdNo'],
            ['transfer_date', 'validateTransferDate'],
            [['longitude', 'latitude', 'altitude', 'street'], 'string', 'max' => 255],
        ];
    }

    public function init()
    {
        parent::init();
        $this->head = Member::FAMILY_HEAD_YES;
        $this->household_no = (new Household())->setTheNo();
        $this->transfer_date = App::formatter()->asDateToTimezone('', 'm/d/Y h:i A');
    }

    public function validateTransferDate($attribute, $params)
    {
        $today = strtotime(App::formatter()->asDateToTimezone());
        $transfer_date = strtotime($this->transfer_date);

        if ($transfer_date > $today) {
            $this->addError($attribute, 'Transfer date is greater than the date today.');
        }
    }

    public function validateHouseholdNo($attribute, $params)
    {
        if (($household = $this->getHousehold()) != null) {
            $this->addError($attribute, 'Existing Household No.');
        }
    }

    public function getHousehold()
    {
        if ($this->_household == null) {
            $this->_household = Household::findOne(['no' => $this->household_no]);
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

            $household = new Household();
            $household->region_id = $address->region_id;
            $household->province_id = $address->province_id;
            $household->municipality_id = $address->municipality_id;

            $household->no = $this->household_no;
            $household->transfer_date = $this->transfer_date;
            $household->barangay_id = $this->barangay_id;
            $household->purok_no = $this->purok_no ?: null;
            $household->lot_no = $this->lot_no ?: null;
            $household->zone_no = $this->zone_no ?: null;
            $household->blk_no = $this->blk_no;
            $household->street = $this->street;
            $household->sitio = $this->sitio;
            $household->landmark = $this->landmark;
            $household->longitude = $this->longitude;
            $household->latitude = $this->latitude;
            $household->altitude = $this->altitude;
            $household->files = $this->files;

            $household->record_status = Household::RECORD_ACTIVE;

            if ($household->save()) {
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
            else {
                $this->addError('household', $household->errors);
            }
        }
    }

    public function beforeValidate()
    {
        if (! parent::beforeValidate()) {
            return false;
        }

        $this->transfer_date = date('Y-m-d H:i:s', strtotime($this->transfer_date));

        return true;
    }
}