<?php

namespace app\components;

use Yii;
use app\helpers\App;
use yii\helpers\Json;
use app\helpers\Url;

class ViewComponent extends \yii\web\View
{
    public function init()
    {
        parent::init();

        /*$options = Json::htmlEncode([
            'appName' => App::appName(),
            'baseUrl' => Url::base() . '/',
            'language' => App::appLanguage(),
            'api' => Url::base(true) . '/api/v1/',
            'csrfToken' => App::request('csrfToken'),
            'csrfParam' => App::request('csrfParam'),
            'params' => App::params()
        ]);*/
        /*$js = <<< JS
            var app = {$options};
            // console.log(app)
        JS;
        $this->registerJs($js, \yii\web\View::POS_HEAD, 'app');*/

        $this->registerJsVar('app', [
            'appName' => App::appName(),
            'baseUrl' => Url::base() . '/',
            'language' => App::appLanguage(),
            'api' => Url::base(true) . '/api/v1/',
            'csrfToken' => App::request('csrfToken'),
            'csrfParam' => App::request('csrfParam'),
            'params' => App::params()
        ]);

        $loadingIcon = App::baseUrl('default/loader-blocks.gif');
     
        $this->registerCss(<<< CSS
            .mw-500 {width: -webkit-fill-available !important; max-width: 500px !important;}
            .mw-400 {width: -webkit-fill-available !important; max-width: 400px !important;}
            .mw-200 {width: -webkit-fill-available !important; max-width: 200px !important;}
            .mw-150 {width: -webkit-fill-available !important; max-width: 150px !important;}
            .mw-100 {width: -webkit-fill-available !important; max-width: 100px !important;}
            .mw-120 {width: -webkit-fill-available !important; max-width: 120px !important;}
            .dropdown-item-hover {
                color: #101221;
                background-color: #F3F6F9;
            }
            body {
                background: #EAF1F7;
            }

            .datepicker.datepicker-orient-top,
            .content .bootstrap-select .dropdown-menu {
                z-index: 999 !important;
            }
            .detail-view th {
                width: 20%;
                white-space: nowrap;
            }
            .report-title {
                font-size: 2rem !important;
                font-weight: 500;
                text-transform:uppercase;
            }
            .report-subtitle {
                font-size: 1.5rem !important;
                font-weight: 500;
                text-transform:uppercase;
            }
            .page-loading {
                /* https://c.tenor.com/pgO8hZgOW5AAAAAC/loading-bar.gif */
                /* https://img.pikbest.com/png-images/20190918/cartoon-snail-loading-loading-gif-animation_2734139.png!bw340 */
                /* https://cdn.dribbble.com/users/2973561/screenshots/5757826/loading__.gif  */
                /* https://www.akriboscapital.com/assets/bg/loader-blocks.gif  */
                /*background: white url('{$loadingIcon}') no-repeat center center / 10rem;*/
            }
        CSS);
    }

	public function registerWidgetJs($widgetFunction, $js, $position = parent::POS_READY, $key = null)
    {
        $js = <<< JS
            var {$widgetFunction} = function() {
                var load = function() {
                    {$js}
                }
                return {
                    init: function() {
                        load();
                    }
                }
            }(); {$widgetFunction}.init();
        JS;

        parent::registerjs($js, $position, $key);
    }

    public function registerWidgetCssFile ($files, $depends=[])
    {
        $depends = $depends ?: [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
        ];
        $files = is_array($files) ? $files: [$files];
        foreach ($files as $css) {
            $this->registerCssFile(App::publishedUrl("/widget/css/{$css}.css", Yii::getAlias('@app/assets')), [
                'depends' => $depends
            ]);
        }
    }

    public function registerWidgetJsFile ($files, $depends=[])
    {
        $depends = $depends ?: [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
        ];
        $files = is_array($files) ? $files: [$files];
        foreach ($files as $js) {
            $this->registerJsFile(App::publishedUrl("/widget/js/{$js}.js", Yii::getAlias('@app/assets')), [
                'depends' => $depends
            ]);
        }
    }

    public function addJsFile ($files, $depends=[], $options=[])
    {
        $depends = $depends ?: [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
        ];
        $options['depends'] = $depends;
        $files = is_array($files) ? $files: [$files];
        foreach ($files as $js) {
            $this->registerJsFile(App::publishedUrl("/{$js}.js", Yii::getAlias('@app/assets')), $options);
        }
    }

    public function addCssFile ($files, $depends=[])
    {
        $depends = $depends ?: [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
        ];
        $files = is_array($files) ? $files: [$files];
        foreach ($files as $css) {
            $this->registerCssFile(App::publishedUrl("/{$css}.css", Yii::getAlias('@app/assets')), [
                'depends' => $depends
            ]);
        }
    }
}