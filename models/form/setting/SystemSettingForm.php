<?php

namespace app\models\form\setting;

use Yii;
use app\helpers\App;
use app\models\Theme;

class SystemSettingForm extends SettingForm
{
    const NAME = 'system-settings';
    const ASIA_MANILA = 'Asia/Manila';

    const OFF = 0;
    const ON = 1;

    public $timezone;
    public $pagination;
    public $auto_logout_timer;
    public $theme;
    public $whitelist_ip_only;
    public $enable_visitor;

    public $logs_expiration;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['timezone', 'pagination', 'theme', 'auto_logout_timer', 'logs_expiration'], 'required'],
	        [['timezone',], 'string'],
	        [['whitelist_ip_only', 'enable_visitor'], 'safe'],
	        [['pagination', 'auto_logout_timer', 'theme', 'whitelist_ip_only', 'enable_visitor'], 'integer'],
            ['logs_expiration', 'integer', 'min' => 0],
	        ['pagination', 'in', 'range' => array_keys(App::params('pagination'))],
	        ['whitelist_ip_only', 'in', 'range' => array_keys(App::params('whitelist_ip_only'))],
	        ['enable_visitor', 'in', 'range' => array_keys(App::params('enable_visitor'))],
	        ['theme', 'exist', 'targetClass' => 'app\models\Theme', 'targetAttribute' => 'id'],
	        ['timezone', 'in', 'range' => array_keys(App::component('general')->timezoneList())],
        ];
    }

    public function default()
    {
        return [
            'timezone' => [
                'name' => 'timezone',
                'default' => self::ASIA_MANILA,
            ],
            'pagination' => [
                'name' => 'pagination',
                'default' => 25,
            ],
            'auto_logout_timer' => [
                'name' => 'auto_logout_timer',
                'default' => 60 * 60 * 24
            ],
            'theme' => [
                'name' => 'theme',
                'default' => Theme::LIGHT_FLUID,
            ],
            'whitelist_ip_only' => [
                'name' => 'whitelist_ip_only',
                'default' => self::OFF,
            ],
            'enable_visitor' => [
                'name' => 'enable_visitor',
                'default' => self::OFF,
            ],
            'logs_expiration' => [
                'name' => 'logs_expiration',
                'default' => 0,
            ],
        ];
    }
}