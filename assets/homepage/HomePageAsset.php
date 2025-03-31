<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets\homepage;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HomePageAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/homepage';
    public $css = [
        'bootstrap-3.4.1/css/bootstrap.min.css',
    ];
    public $js = [
        'ajax.googleapis.com/jquery/3.6.0/jquery.min.js',
        'bootstrap-3.4.1/js/bootstrap.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}