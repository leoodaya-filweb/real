<?php

namespace app\behaviors;

use app\helpers\App;
use app\models\Member;
use yii\db\ActiveRecord;
use app\models\Household;

class HouseholdBehavior extends \yii\base\Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => '_beforeValidate',
            ActiveRecord::EVENT_INIT => '_init',
            ActiveRecord::EVENT_AFTER_FIND => 'eventAfterFind',
        ];
    }

    public function eventAfterFind($event)
    {
        $this->owner->transfer_date = date('m/d/Y h:i A', strtotime($this->owner->transfer_date));
    }

    public function _init($event)
    {
        $address = App::setting('address');
        if($this->owner->isNewRecord) {
            $this->owner->region_id = $address->region_id;
            $this->owner->province_id = $address->province_id;
            $this->owner->municipality_id = $address->municipality_id;
            $this->owner->record_status = Household::RECORD_DRAFT;
            $this->owner->transfer_date = App::formatter()->asDateToTimezone('', 'm/d/Y h:i A');
        }
    }

    public function _beforeValidate($event)
    {
        $this->owner->transfer_date = date(
            'Y-m-d H:i:s', 
            strtotime($this->owner->transfer_date)
        );
    }
}