<?php

namespace app\models\form\setting;

use Yii;
use app\helpers\App;

class SurveySettingForm extends SettingForm
{
    const NAME = 'survey-settings';
    /* EMAIL */
    public $dominance_percentage;
    public $survey_color;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['dominance_percentage', 'required'],
            ['dominance_percentage', 'integer', 'max' => 100, 'min' => 1],
            ['survey_color', 'safe']
        ];
    }

    public function default()
    {
        return [
            'dominance_percentage' => [
                'name' => 'dominance_percentage',
                'default' => 50
            ],
            'survey_color' => [
                'name' => 'survey_color',
                'default' => App::params('survey_color')
            ],
        ];
    }
}