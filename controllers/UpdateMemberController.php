<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\models\Member;
use app\models\search\MemberSearch;

class UpdateMemberController extends Controller 
{
    public function actionIndex()
    {
        $model = new Member();
        
        return $this->render('index', [
            'model' => $model,
            'searchModel' => new MemberSearch()
        ]);
    }
} 