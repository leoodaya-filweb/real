<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\models\Masterlist;
use app\models\SocialPensioner;
use app\models\search\MasterlistSearch;

/**
 * MasterlistController implements the CRUD actions for Masterlist model.
 */
class MasterlistController extends Controller 
{
    public function actionFindByNameKeywords($keywords='')
    {
        return $this->asJson(
            Masterlist::findByKeywords($keywords, [
                'CONCAT_WS(" - ", CONCAT_WS(" ", `first_name`, `middle_name`, `last_name`),`id`)'
            ])
        );
    }

    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            Masterlist::findByKeywords($keywords, [
                'qr_id', 
                'last_name', 
                'middle_name', 
                'first_name',
                'CONCAT_WS(" ", `first_name`,  `last_name`)',  
                // 'CONCAT_WS(" ", `last_name`,  `first_name`)',  
                'CONCAT_WS(" ", `first_name`, `middle_name`, `last_name`)',  
                // 'CONCAT_WS(" ", `last_name`, `middle_name`, `first_name`)',  
            ])
        );
    }

    /**
     * Lists all Masterlist models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MasterlistSearch();
        $dataProvider = $searchModel->search(['MasterlistSearch' => App::queryParams()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Masterlist model.
     * @param integer $slug
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($slug)
    {
        $model = Masterlist::controllerFind($slug, 'slug');

        if (App::isAjax()) {
            return $this->asJson([
                'status' => 'success',
                'detailView' => $model->detailView
            ]);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Masterlist model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Masterlist();

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
     * Duplicates a new Masterlist model.
     * If duplication is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDuplicate($slug)
    {
        $originalModel = Masterlist::controllerFind($slug, 'slug');
        $model = new Masterlist();
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
     * Updates an existing Masterlist model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($slug)
    {
        $model = Masterlist::controllerFind($slug, 'slug');

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
     * Deletes an existing Masterlist model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $slug
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($slug)
    {
        $model = Masterlist::controllerFind($slug, 'slug');

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
        return $this->exportCsv();
    }

    // public function _ctionExportXls()
    // {
    //     return $this->exportXls();
    // }

    public function actionExportXlsx()
    {
        return $this->exportXlsx();
    }

    public function actionInActiveData()
    {
        # dont delete; use in condition if user has access to in-active data
    }
    
    public function actionRemoveFromMasterlist($slug)
    {
        $model = Masterlist::controllerFind($slug, 'slug');

        if ($model->removeFromMasterlist()) {
            App::success('Removed from Masterlist');

            return $this->redirect((SocialPensioner::findOne($model->id))->viewUrl);
        }
        else {
            App::danger($model->errorSummary);

            return $this->redirect(App::referrer());
        }
    }
}