<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\chat\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ThemeAsset extends AssetBundle
{
    // public $basePath = '@webroot'; 
    public $sourcePath = '@app/modules/chat/assets/assetsfiles';
    public $baseUrl = '@web';
    public $css = [   
        'https://unpkg.com/@vuepic/vue-datepicker@latest/dist/main.css',
        'css/chat.css'
    ];
    public $js = [    
        'vue3/vue.global.prod.js',
        'https://unpkg.com/@vuepic/vue-datepicker@latest',
        'js/profile.js',
        // 'js/chat.js',
    ];

    public $depends = [
        'app\themes\keen\sub\demo1\main\assets\KeenDemo1AppAsset',
        'app\themes\keen\sub\demo1\light\assets\KeenDemo1LightAppAsset',
        'app\themes\keen\assets\KeenAsset',
        'app\assets\AppAsset'
    ];
}