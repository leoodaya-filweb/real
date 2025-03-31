<?php

namespace app\models\form\setting;

use Yii;
use app\helpers\App;

class PrioritySectorSettingsForm extends SettingForm
{
    const NAME = 'priority-sector-settings';

    public $data;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['data'], 'required'],
        ];
    }

    public function default()
    {
        return [
            'data' => [
                'name' => 'data',
                'default' => App::params('priority_sector')
            ],
        ];
    }
}