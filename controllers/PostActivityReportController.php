<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\models\PostActivityReport;
use app\models\search\PostActivityReportSearch;

/**
 * PostActivityReportController implements the CRUD actions for PostActivityReport model.
 */
class PostActivityReportController extends Controller 
{
    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            PostActivityReport::findByKeywords($keywords, [
                'date',
                'for',
                'subject',
                'title',
                'location',
                'date_of_activity',
                'concerned_office',
                'prepared_by',
                'noted_by',
            ])
        );

    }

    /**
     * Lists all PostActivityReport models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostActivityReportSearch();
        $dataProvider = $searchModel->search(['PostActivityReportSearch' => App::queryParams()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PostActivityReport model.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($token)
    {
        return $this->render('view', [
            'model' => PostActivityReport::controllerFind($token, 'token'),
        ]);
    }

    /**
     * Creates a new PostActivityReport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PostActivityReport([
            'subject' => 'Post Activity Report',
            'date' => App::formatter()->asDateToTimezone(null, 'm/d/Y'),
            'date_of_activity' => App::formatter()->asDateToTimezone(null, 'm/d/Y'),
            'for' => implode("\n", [
                App::setting('personnel')->mayor
                ,'Municipal Mayor'
            ]),
            'prepared_by' => 'OFELIA M. DE LA TORRE',
            'prepared_by_position' => 'KALIPI Focal Person',
            'noted_by' => App::setting('personnel')->mswdo,
            'noted_by_position' => 'MSWDO'
        ]);

        if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Created');

            return $this->redirect($model->viewUrl);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Duplicates a new PostActivityReport model.
     * If duplication is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDuplicate($token)
    {
        $originalModel = PostActivityReport::controllerFind($token, 'token');
        $model = new PostActivityReport();
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
     * Updates an existing PostActivityReport model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($token)
    {
        $model = PostActivityReport::controllerFind($token, 'token');

        if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Updated');
            return $this->redirect($model->viewUrl);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PostActivityReport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($token)
    {
        $model = PostActivityReport::controllerFind($token, 'token');

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

    public function actionPrintable($token)
    {
        $this->layout  = 'print_v2';
        return $this->render('printable', [
            'model' => PostActivityReport::controllerFind($token, 'token'),
        ]);
    }
}