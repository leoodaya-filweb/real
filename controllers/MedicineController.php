<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\models\Medicine;
use app\models\search\MedicineSearch;

/**
 * MedicineController implements the CRUD actions for Medicine model.
 */
class MedicineController extends Controller 
{
    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            Medicine::findByKeywords($keywords, ['id'])
        );
    }

    /**
     * Lists all Medicine models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MedicineSearch();
        $dataProvider = $searchModel->search(['MedicineSearch' => App::queryParams()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Medicine model.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => Medicine::controllerFind($id),
        ]);
    }

    /**
     * Creates a new Medicine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($transaction_id = 0)
    {
        $model = new Medicine([
            'transaction_id' => $transaction_id
        ]);

        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }
        
        if ($model->load(App::post()) && $model->save()) {
            if (App::isAjax()) {
                return $this->_ajaxCreated($model);
            }

            App::success('Successfully Created');

            return $this->redirect($model->viewUrl);
        }

        if (App::isAjax()) {
            return $this->_ajaxForm($model);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Duplicates a new Medicine model.
     * If duplication is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDuplicate($id)
    {
        $originalModel = Medicine::controllerFind($id);
        $model = new Medicine();
        $model->attributes = $originalModel->attributes;

        if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Duplicated');

            return $this->redirect($model->viewUrl);
        }

        return $this->render('duplicate', [
            'model' => $model,
            'originalModel' => $originalModel,
        ]);
    }

    /**
     * Updates an existing Medicine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = Medicine::controllerFind($id);

        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }
        
        if ($model->load(App::post()) && $model->save()) {
            if (App::isAjax()) {
                return $this->_ajaxCreated($model);
            }

            App::success('Successfully Created');

            return $this->redirect($model->viewUrl);
        }

        if (App::isAjax()) {
            return $this->_ajaxForm($model);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Medicine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Medicine::controllerFind($id);

        if($model->delete()) {
            if (App::isAjax()) {
                return $this->asJson([
                    'status' => 'success'
                ]);
            }
            App::success('Successfully Deleted');
        }
        else {
            App::danger(json_encode($model->errors));
        }

        if (App::isAjax()) {
            return $this->asJson([
                'status' => 'success',
                'model' => $model
            ]);
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

    public function actionPrint()
    {
        return $this->exportPrint();
    }

    public function actionExportPdf()
    {
        return $this->exportPdf();
    }

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
}