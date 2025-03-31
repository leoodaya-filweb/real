<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\models\Allowance;
use app\models\search\AllowanceSearch;
use app\widgets\Detail;

/**
 * AllowanceController implements the CRUD actions for Allowance model.
 */
class AllowanceController extends Controller 
{
    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            Allowance::findByKeywords($keywords, ['id'])
        );
    }

    /**
     * Lists all Allowance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AllowanceSearch();
        $dataProvider = $searchModel->search(['AllowanceSearch' => App::queryParams()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Allowance model.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = Allowance::controllerFind($id);

        if (App::isAjax() && $model) {
            return $this->asJson([
                'status' => 'success',
                'model' => $model,
                'detailView' => Detail::widget(['model' => $model])
            ]);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Allowance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($scholarship_id='')
    {
        $model = new Allowance([
            'scholarship_id' => $scholarship_id,
            'status' => Allowance::RECEIVED
        ]);

        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }
        
        if ($model->load(App::post()) && $model->save()) {
            if (App::isAjax()) {
                $data = Allowance::find()
                    ->select(['SUM(amount) AS total'])
                    ->where(['scholarship_id' => $scholarship_id])
                    ->asArray()
                    ->one();

                $response['status'] = 'success';
                $response['totalAllowance'] = App::formatter()->asPeso($data['total'] ?? 0);
                $response['allowances'] = App::foreach(
                    Allowance::findAll(['scholarship_id' => $scholarship_id]), 
                    fn ($allowance) => $this->renderPartial('/scholarship/view/_allowance', [
                        'allowance' => $allowance
                    ])
                );

                return $this->asJson($response);
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
     * Duplicates a new Allowance model.
     * If duplication is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDuplicate($token)
    {
        $originalModel = Allowance::controllerFind($token, 'token');
        $model = new Allowance();
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
     * Updates an existing Allowance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($token)
    {
        $model = Allowance::controllerFind($token, 'token');

        if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Updated');
            return $this->redirect($model->viewUrl);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Allowance model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($token)
    {
        $model = Allowance::controllerFind($token, 'token');

        if($model->delete()) {
            if (App::isAjax()) {
                $data = Allowance::find()
                    ->select(['SUM(amount) AS total'])
                    ->where(['scholarship_id' => $model->scholarship_id])
                    ->asArray()
                    ->one();

                return $this->asJson([
                    'status' => 'success',
                    'message' => 'Deleted',
                    'totalAllowance' => App::formatter()->asPeso($data['total'] ?? 0)
                ]);
            }
            App::success('Successfully Deleted');
        }
        else {
            if (App::isAjax()) {
                return $this->asJson([
                    'status' => 'failed',
                    'errorSummary' => $model->errorSummary
                ]);
            }
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

    public function actionExportXls()
    {
        return $this->exportXls();
    }

    public function actionExportXlsx()
    {
        return $this->exportXlsx();
    }

    public function actionInActiveData()
    {
        # dont delete; use in condition if user has access to in-active data
    }
}