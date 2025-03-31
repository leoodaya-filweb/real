<?php

namespace app\helpers;

use Yii;
use app\helpers\App;
use app\helpers\Url;
use app\widgets\Anchor;
use yii\web\Request;
use app\widgets\ExportButton;
use app\widgets\Anchors;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class Html extends \yii\helpers\Html
{
    public static function a($text, $url = null, $options = [])
    {
        return Anchor::widget([
            'title' => $text ,
            'link' => $url,
            'options' => $options,
        ]);
    }
    
    public static function nbsp($count=1)
    {
        $space = '';
        for ($i=0; $i < $count; $i++) { 
            $space .= "&nbsp;";
        }
        return $space;
    }

    public static function navController($link)
    {
       /* $request = new Request([
            'url' => parse_url(\yii\helpers\Url::to($link, true), PHP_URL_PATH)
        ]);
        $url = App::urlManager()->parseRequest($request);
        list($controller, $actionID) = App::app()->createController($url[0]);

        return $controller ? $controller->id: '';*/

        // local
        if(! filter_var($link, FILTER_VALIDATE_URL)) {
            $link = App::baseUrl(\yii\helpers\Url::to($link));
            $link = str_replace('//', '/', $link);
        }

        $request = new Request([
            'url' => parse_url($link, PHP_URL_PATH)
        ]);
        $url = App::urlManager()->parseRequest($request);
        list($controller, $actionID) = App::app()->createController($url[0]);

        return $controller ? $controller->id: '';
    }

    public static function image($token, $params=[], $options=[])
    {
        $options['class'] = ($options['class'] ?? '') . ' mw-' . ($params['w'] ?? 0);
        return parent::img(Url::image($token, $params), $options);
    }

    public static function imagePath($token, $options)
    {
        return parent::img(Url::image($token, [], false, true), $options);
    }

    public static function download($token, $params=[], $options=[])
    {
        return parent::img(Url::download($token, $params), $options);
    }

    public static function isHtml($string)
    {
        if($string != strip_tags($string)) {
            // is HTML
            return true;
        }
        else {
            // not HTML
            return false;
        }
    }

    public static function if($condition=true, $content='', $params=[])
    {
        return App::if($condition, $content, $params);
    }

    public static function ifELse($condition=true, $trueContent='', $falseContent='', $params=[])
    {
        return App::ifELse($condition, $trueContent, $falseContent, $params);
    }

    public static function ifElseIf($arr=[], $params=[])
    {
        return App::ifElseIf($arr, $params);
    }

    public static function foreach($array=[], $function='', $glue=' ')
    {
        return App::foreach($array, $function, $glue);
    }


    public static function content($content, $params)
    {
        if ($params['wrapCard'] ?? true) {
            // return $content;
            return Yii::$app->controller->view->render('@app/views/layouts/_card_wrapper-container', [
                'content' => $content
            ]);
        }

        return $content;
    }

    public static function exportButton($params)
    {
        if ($params['showExportButton'] ?? '') {
            return ExportButton::widget([
                'printUrl' => $params['printUrl'] ?? '',
                'pdfUrl' => $params['pdfUrl'] ?? '',
                'csvUrl' => $params['csvUrl'] ?? '',
                'xlsxUrl' => $params['xlsxUrl'] ?? '',
            ]);
        }
    }

    public static function createButton($params)
    {
        $controller = $params['createController'] ?? App::controllerID();

        $title = $params['createTitle'] ?? 'Create ' . ucwords(str_replace('-', ' ', $controller));
        if ($params['showCreateButton'] ?? '') {
            return Anchors::widget([
                'names' => 'create',
                'forceTitle' => $title,
                'controller' => $controller,
            ]);
        }
    }

    public static function advancedFilter($searchModel)
    {
        if ($searchModel) {
            $searchTemplate = $searchModel->searchTemplate ?? implode('/', [
                App::controllerID(),
                '_search'
            ]);

            return App::view()->render("/{$searchTemplate}", [
                'model' => $searchModel,
            ]);
        }
    }

    public static function errorSummary($models, $options = [])
    {
        $header = isset($options['header']) ? $options['header'] : '<p>' . Yii::t('yii', 'Please fix the following errors:') . '</p>';
        $footer = ArrayHelper::remove($options, 'footer', '');
        $encode = ArrayHelper::remove($options, 'encode', true);
        $showAllErrors = ArrayHelper::remove($options, 'showAllErrors', false);
        unset($options['header']);
        $lines = self::collectErrors($models, $encode, $showAllErrors);
        if (empty($lines)) {
            // still render the placeholder for client-side validation use
            $content = '<ul></ul>';
            $options['style'] = isset($options['style']) ? rtrim($options['style'], ';') . '; display:none' : 'display:none';
        } else {
            $content = '<ul><li>' . implode("</li>\n<li>", $lines) . '</li></ul>';
        }

        return Html::tag('div', $header . $content . $footer, $options);
    }

    private static function collectErrors($models, $encode, $showAllErrors)
    {
        $lines = [];
        if (!is_array($models)) {
            $models = [$models];
        }

        foreach ($models as $model) {
            $lines = array_unique(array_merge($lines, $model->getErrorSummary($showAllErrors)));
        }

        // If there are the same error messages for different attributes, array_unique will leave gaps
        // between sequential keys. Applying array_values to reorder array keys.
        $lines = array_values($lines);

        if ($encode) {
            foreach ($lines as &$line) {
                $line = is_array($line)? json_encode($line): $line;
                $line = Html::encode($line);
            }
        }

        return $lines;
    }

    public static function number($value=0)
    {
        return App::formatter('asNumber', $value);
    }

    public static function popupCenter($text, $url = null, $options = [])
    {
        $options['onClick'] = "popupCenter('{$url}')";

        return self::a($text, '#', $options);
    }
}