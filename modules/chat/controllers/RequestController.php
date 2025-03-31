<?php

namespace app\modules\chat\controllers;

use Yii;
use app\helpers\App;
use app\modules\chat\models\Request;

class RequestController extends Controller
{
    public function actionCreate()
    {
        $model = new Request();

        if ($model->load(['Request' => App::post()]) && $model->save()) {
            return $this->asJson([
                'status' => 'success',
                'model' => $model
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => $model->errorSummary
        ]);
    }
}