<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\models\Masterlist;
use app\models\SocialPensioner;
use app\models\search\SocialPensionerSearch;

/**
 * SocialPensionerController implements the CRUD actions for SocialPensioner model.
 */
class SocialPensionerController extends Controller 
{
    public function actionFindByKeywords($keywords='', $status=SocialPensioner::PENDING)
    {
        return $this->asJson(
            SocialPensioner::findByKeywords($keywords, [
                'qr_id', 
                'last_name', 
                'middle_name', 
                'first_name',
                'CONCAT_WS(" ", `first_name`,  `last_name`)',  
                'CONCAT_WS(" ", `last_name`,  `first_name`)',  
                'CONCAT_WS(" ", `first_name`, `middle_name`, `last_name`)',  
                'CONCAT_WS(" ", `last_name`, `middle_name`, `first_name`)',  
            ], 10, [
                'status' => $status
            ])
        );
    }

    /**
     * Lists all SocialPensioner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SocialPensionerSearch([
            'status' => SocialPensioner::PENDING
        ]);
        $dataProvider = $searchModel->search(['SocialPensionerSearch' => App::queryParams()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SocialPensioner model.
     * @param integer $slug
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($slug)
    {
        return $this->render('view', [
            'model' => SocialPensioner::controllerFind($slug, 'slug'),
        ]);
    }

    /**
     * Creates a new SocialPensioner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SocialPensioner();

        if ($model->load(App::post())) {
            if ($model->save()) {
                App::success('Successfully Created');
                return $this->redirect($model->viewUrl);
            }
            else {
                $model->formatDates($model->outFormat);
                App::danger($model->errorSummary);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Duplicates a new SocialPensioner model.
     * If duplication is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDuplicate($slug)
    {
        $originalModel = SocialPensioner::controllerFind($slug, 'slug');
        $model = new SocialPensioner();
        $model->attributes = $originalModel->attributes;

        if ($model->load(App::post())) {
            if ($model->save()) {
                App::success('Successfully Duplicated');
                return $this->redirect($model->viewUrl);
            }
            else {
                $model->formatDates($model->outFormat);
                App::danger($model->errorSummary);
            }
        }

        return $this->render('duplicate', [
            'model' => $model,
            'originalModel' => $originalModel,
        ]);
    }

    /**
     * Updates an existing SocialPensioner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($slug)
    {
        $model = SocialPensioner::controllerFind($slug, 'slug');

        if ($model->load(App::post())) {
            if ($model->save()) {
                App::success('Successfully Updated');
                return $this->redirect($model->viewUrl);
            }
            else {
                $model->formatDates($model->outFormat);
                App::danger($model->errorSummary);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SocialPensioner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $slug
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($slug)
    {
        $model = SocialPensioner::controllerFind($slug, 'slug');

        if($model->delete()) {
            App::success('Successfully Deleted');
        }
        else {
            App::danger(json_encode($model->errors));
        }

        return $this->redirect($model->indexUrl);
    }

    public function actionChangeRecordStatus()
    {
        return $this->changeRecordStatus();
    }

    public function actionBulkAction()
    {
        return $this->bulkAction();
    }

    /*public function _ctionPrint()
    {
        return $this->exportPrint();
    }

    public function _ctionExportPdf()
    {
        return $this->exportPdf();
    }*/

    public function actionExportCsv()
    {
        return $this->exportCsv(new SocialPensionerSearch([
            'status' => SocialPensioner::PENDING
        ]));
    }

    // public function _ctionExportXls()
    // {
    //     return $this->exportXls();
    // }

    public function actionExportXlsx()
    {
        return $this->exportXlsx(new SocialPensionerSearch([
            'status' => SocialPensioner::PENDING
        ]));
    }

    public function actionInActiveData()
    {
        # dont delete; use in condition if user has access to in-active data
    }

    public function actionAddToMasterlist($slug)
    {
        $model = SocialPensioner::controllerFind($slug, 'slug');

        if ($model->addToMasterlist()) {
            App::success('Added to Masterlist');

            return $this->redirect((Masterlist::findOne($model->id))->viewUrl);
        }
        else {
            App::danger($model->errorSummary);

            return $this->redirect(App::referrer());
        }
    }
}