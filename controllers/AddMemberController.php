<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\models\Member;

class AddMemberController extends Controller 
{
    public function actionIndex()
    {
        $model = new Member([
            'pensioner' => Member::NOT_PENSIONER,
            'scenario' => 'family-head',
            'record_status' => Member::RECORD_ACTIVE
        ]);

        if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Created');

            return $this->redirect($model->viewUrl);
        }

        return $this->render('/member/create', [
            'model' => $model,

        ]);
    }
} 