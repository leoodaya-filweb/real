<?php

namespace app\models\form\user;

use Yii;
use app\helpers\App;
use app\models\User;
use app\models\UserMeta;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ProfileForm extends UserForm
{
    const META_NAME = 'profile';

    public $first_name;
    public $last_name;
    public $position;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return $this->setRules([
            [['first_name', 'last_name', ], 'required'],
            [['first_name', 'last_name', 'position'], 'string'],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'position' => 'Position',
        ];
    } 

    public function getDetailColumns()
    {
        return [
            'first_name:raw',
            'last_name:raw',
            'position:raw',
        ];
    }

    public function getFullname()
    {
        return implode(' ', array_filter([
            $this->first_name,
            $this->last_name,
        ]));
    }
}