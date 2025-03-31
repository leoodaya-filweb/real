<?php
namespace app\modules\chat;

use Yii;

/**
 * space module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\chat\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {

        // add own params to application params
        $params = require __DIR__ . '/config/params.php';
        Yii::$app->params = array_merge(Yii::$app->params, $params);

        Yii::$app->request->parsers = ['application/json' => 'yii\web\JsonParser'];

        // set own components in `Yii::$app`
		Yii::$app->setComponents([
            'formatter' => ['class' => 'app\modules\chat\components\FormatterComponent'],
            'user' => ['class' => 'app\modules\chat\components\UserComponent'],
            'view' => ['class' => 'app\modules\chat\components\ViewComponent'],
		]);

        parent::init();

        // custom initialization code goes here
    }
}
