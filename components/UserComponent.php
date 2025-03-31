<?php

namespace app\components;

class UserComponent extends \yii\web\User
{
    // public $enableAutoLogin = true;
    public $identityClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['VisitLogBehavior'] = [
            'class' => 'app\behaviors\VisitLogBehavior'
        ];

        return $behaviors;
    }
}