<?php

namespace app\filters;

use Yii;
use app\helpers\App;
use app\helpers\Url;
use app\models\Budget;

class SettingFilter extends \yii\base\ActionFilter
{
    public $exempted = [
        'file/viewer',
        'file/display',
        'site/login',
    ];

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // if ((!in_array(App::controllerAction(), $this->exempted))) {

        //     $budget = $this->budget();
            
        //     if ($budget === false) {
        //         return $budget;
        //     }
        // }

        return true;
    }

    public function budget()
    {
        if (($initialBudget = Budget::initial()) == null) {
            if (App::isWeb()) {

                $currenturl = App::absoluteUrl();
                $settingUrl = Url::to(['setting/general', 'tab' => 'budget'], true);

                if ($currenturl != $settingUrl) {
                    if (! App::isAjax()) {
                        App::warning('Set initial budget for this year');
                        $this->owner->redirect(['setting/general', 'tab' => 'budget']);
                        return false;
                    }
                }
            }
            else {
                return false;
            }
        }

        return true;
    }
}