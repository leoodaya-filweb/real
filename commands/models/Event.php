<?php

namespace app\commands\models;

use Yii;

class Event extends \app\models\Event
{
    // public function rules()
    // {
    //     $rules = parent::rules();
    //     $rules[] = [['created_at', 'updated_at'], 'safe'];
    //     return $rules;
    // }
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['BlameableBehavior']);
        // unset($behaviors['TimestampBehavior']);
        return $behaviors;
    }
}
