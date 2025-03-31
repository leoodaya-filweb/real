<?php

namespace app\components;

class UrlManagerComponent extends \yii\web\UrlManager
{
    public $enablePrettyUrl = true;
    public $showScriptName = false;
    public $rules = [
        [
            'class' => 'yii\rest\UrlRule', 
            'controller' => 'api/v1/user',
            'pluralize' => false
        ],

        'cv/<token>' => 'site/certificate-verification',
        'images' => 'site/images',

        /*Community Board module*/
        'community-board' => 'community-board/default/index',
        'community-board/<token>' => 'community-board/default/index',
        'community-board/<controller>/<action>/<token>' => 'community-board/<controller>/<action>',
        'community-board/<controller>/<action>' => 'community-board/<controller>/<action>', 

        '/' => 'site/index',

        'my-files' => 'file/my-files',
        'my-setting' => 'setting/my-setting',
        'my-role' => 'role/my-role',
        'my-account' => 'user/my-account',
        'my-password' => 'user/my-password',

        '<action:index|login|reset-password|contact|about|home>' => 'site/<action>',

        'setting/general/<tab>' => 'setting/general',
        'setting/general' => 'setting/general',
        
        'open-event' => 'un-planned-attendees-event/index',
        'open-event/<action>' => 'un-planned-attendees-event/<action>',

        '<controller>' => '<controller>/index',
        '<controller:(notification|event|post-activity-report)>/<action>/<token>' => '<controller>/<action>',

        '<controller:(masterlist|social-pensioner|event-category|setting|ip|user|theme|backup|role|transaction|transaction-type|assistance-type)>/<action>/<slug>' => '<controller>/<action>',
        '<controller:(member)>/<action>/<qr_id>' => '<controller>/<action>',

        '<controller>/<id:\d+>' => '<controller>/view',
        '<controller>/<action>/<id:\d+>' => '<controller>/<action>',
        '<controller>/<action>' => '<controller>/<action>', 
    ];
}