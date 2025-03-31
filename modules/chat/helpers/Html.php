<?php

namespace app\modules\chat\helpers;

class Html extends \app\helpers\Html 
{
	public static function a($text, $url = null, $options = [])
    {
        return \yii\helpers\Html::a($text, $url, $options);
    }


    public static function if($condition = true, $content='', $params=[])
    {
        if ($condition) {
            if (is_callable($content)) {
                return call_user_func($content, $condition, $params);
            }
            return $content;
        }
    }

    public static function ifELse($condition = true, $trueContent='', $falseContent='', $params=[])
    {
        if ($condition) {
            if (is_callable($trueContent)) {
                return call_user_func($trueContent, $condition, $params);
            }

            return $trueContent;
        }

        if (is_callable($falseContent)) {
            return call_user_func($falseContent, $condition, $params);
        }
        return $falseContent;
    }

    public static function ifElseIf($arr=[], $params=[])
    {
        if ($arr) {
            foreach ($arr as $key => $data) {
                if ($data['condition']) {
                    if (is_callable($data['content'])) {
                        return call_user_func($data['content'], $data['condition'], $params);
                    }
                    return $data['content'];
                }
            }
        }
    }
}
