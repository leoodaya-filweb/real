<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\models\Household;
use app\models\search\HouseholdSearch;

class UpdateHouseholdController extends Controller 
{
    public function actionFindNoByKeywords($keywords='')
    {
        return $this->asJson(
            Household::findByKeywords($keywords, ['h.no'])
        );
    }

    public function actionIndex()
    {
        $model = new Household();

        return $this->render('index', [
            'model' => $model,
            'searchModel' => new HouseholdSearch()
        ]);
    }

    public function actionDetail($no='')
    {
        $model = Household::findOne(['no' => $no]);

        if ($model) {
            $template = App::get('template') ?: '_detail';
          
            return $this->asJson([
                'status' => 'success',
                'model' => $model,
                'detailView' => $this->renderAjax($template, [
                    'model' => $model,
                ])
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'error' => 'No household found.',
            'no' => $no
        ]);
    }
} 