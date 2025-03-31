<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\jobs\ImportHouseholdJob;
use app\models\Household;
use app\models\Queue;
use app\models\form\BulkImportHouseholdForm;

class ImportHouseholdController extends Controller 
{
    public function actionIndex()
    {
        $model = new BulkImportHouseholdForm();
        $household = new Household();

        if ($model->load(App::post())) {
            if ($model->validate()) {
                Queue::push(new ImportHouseholdJob([
                    'file_token' => $model->file_token,
                    'user_id' => App::identity('id')
                ]));
                App::success('The household data will be imported in the queue.');
            }
            else {
                App::danger(Html::errorSummary($model));
            }

            return $this->redirect(['index']);
        }

        return $this->render('/household/import', [
            'household' => $household,
            'model' => $model,
        ]);
    }
} 