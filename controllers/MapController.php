<?php

namespace app\controllers;

use Yii;

class MapController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}