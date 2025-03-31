<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\models\TechIssue;
use app\models\TechIssueLog;
use app\models\search\TechIssueSearch;
use app\widgets\Detail;

/**
 * TechIssueController implements the CRUD actions for TechIssue model.
 */
class TechIssueController extends Controller 
{
    public function actionFindByKeywords($keywords='', $user_id='')
    { 
        return $this->asJson(
            TechIssue::findByKeywords($keywords, [
                'u.email',
                't.description',
                't.ip',
                't.browser',
                't.os',
                't.device',
            ], 10, [
                't.user_id' => $user_id
            ])
        );
    }

    /**
     * Lists all TechIssue models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TechIssueSearch();
        $dataProvider = $searchModel->search(['TechIssueSearch' => App::queryParams()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TechIssue model.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($token='', $response='html')
    {
        $model = TechIssue::controllerFind($token, 'token');

        if ($response == 'json') {
            return $this->asJson([
                'status' => 'success',
                'totalLogs' => $model->totalTechIssueLogs,
                'logs' => $model->loadLogs,
                'activeTechIssue' => $model,
                'currentUser' => App::identity()
                // 'model' => $model,
                // 'detailView' => $this->renderPartial('_detail-view', ['model' => $model])
            ]);
        }

        $addedLog = false;
        if ($model->isOpen && App::identity('isDeveloper')) {
            $model->status = TechIssue::ONGOING;
            $model->remarks = 'Issue was viewed';
            $model->save();
            $addedLog = true;

            $model->refresh();
        }
        return $this->render('view', [
            'model' => $model,
            'addedLog' => $addedLog,
        ]);
    }

    /**
     * Creates a new TechIssue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public _function actionCreate()
    // {
    //     $model = new TechIssue();

    //     if ($model->load(App::post()) && $model->save()) {
    //         App::success('Successfully Created');

    //         return $this->redirect($model->viewUrl);
    //     }

    //     $model->flashErrors();

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Duplicates a new TechIssue model.
     * If duplication is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDuplicate($token)
    {
        $originalModel = TechIssue::controllerFind($token, 'token');
        $model = new TechIssue();
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
     * Updates an existing TechIssue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($token)
    {
        $model = TechIssue::controllerFind($token, 'token');
        $model->remarks = 'Updated Issue';

        if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Updated');
            return $this->redirect($model->viewUrl);
        }

        $model->flashErrors();

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TechIssue model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($token)
    {
        $model = TechIssue::controllerFind($token, 'token');

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

    public function actionChangeStatus($token)
    {
        $model = TechIssue::controllerFind($token, 'token');

        if ($model->load(App::post()) && $model->save()) {
            return $this->asJson([
                'status' => 'success',
                'message' => 'Successfully Created',
                'model' => $model
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => $model->errorSummary
        ]);
    }

    public function actionRequest()
    {
        $model = new TechIssue();
        if ($model->load(App::post())) {
            if ( $model->save()) {
                App::success('Successfully Created');
                return $this->asJson([
                    'model' => $model,
                    'logs' => $model->techIssueLogs,
                    'status' => 'success',
                    'message' => 'Successfully Created',
                    'viewUrl' => $model->viewUrl
                ]);
            }

            return $this->asJson([
                'status' => 'failed',
                'errors' => $model->errors,
                'message' => $model->errorSummary
            ]);
        }
        $model->flashErrors();

        $searchModel = new TechIssueSearch(['searchAction' => ['tech-issue/request']]);
        $dataProvider = $searchModel->search(['TechIssueSearch' => App::queryParams()]);
        $dataProvider->query->andWhere(['t.user_id' => App::identity('id')]);

        return $this->render('request', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function beforeAction($action)
    {
        switch ($action->id) {
            case 'poll-logs':
                $this->enableCsrfValidation =false;
                break;
            
            default:
                // code...
                break;
        }

        return parent::beforeAction($action);
    }

    public function actionPollLogs()
    {
        $maxLogId_post = (int) (App::post('maxLogId') ?: 0);
        $totalLogs_post = (int) (App::post('totalLogs') ?: 0);

        $model = TechIssue::findOne(['token' => App::post('token')]);

        // for ($i=0; $i < $counter; $i++) { 
            $response = [];

            $totalLogs = TechIssueLog::find()
                ->where(['tech_issue_id' => $model->id])
                ->count();

            if ($totalLogs > 0 && ($totalLogs != $totalLogs_post)) {
                $response['totalLogs'] = $totalLogs;
            }

            $logs = TechIssueLog::find()
                ->where(['tech_issue_id' => $model->id])
                ->andWhere(['>', 'id', $maxLogId_post])
                ->orderBy(['id' => SORT_DESC])
                ->limit(20)
                ->all();

            if ($logs) {
                $response['logs'] = array_reverse($logs);
            }

            if ($response) {
                $response['status'] = 'success';
                return $this->asJson($response);
            }
            
            // sleep(1);
        // }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'no changes'
        ]);
    }

    public function actionAddNewLog()
    {
        if (($post = App::post()) != null) {
            if (($model = TechIssue::findOne(['token' => $post['token']])) != null) {
                $log = new TechIssueLog([
                    'tech_issue_id' => $model->id,
                    'status' => $model->status,
                    'remarks' => $post['content'],
                    'attachments' => $post['attachments'] ?? []
                ]);

                if ($log->save()) {
                    $log->refresh();
                    return $this->asJson([
                        'status' => 'success',
                        'log' => $log
                    ]);
                }

                return $this->asJson([
                    'status' => 'failed',
                    'errorSummary' => $log->errorSummary
                ]);
            }
            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => 'no active technical issue'
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'No post data'
        ]);
    }

    public function actionLoadPreviousLogs()
    {
        if (($post = App::post()) != null) {
            if (($model = TechIssue::findOne(['token' => $post['token']])) != null) {
                if (($logs = $model->getLoadLogs((int)$post['minLogId'], '<')) != null) {
                    return $this->asJson([
                        'status' => 'success',
                        'logs' => $logs
                    ]);
                }
                return $this->asJson([
                    'status' => 'failed',
                    'errorSummary' => 'no logs'
                ]);
            }
            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => 'no active technical issue'
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'No post data'
        ]);
    }
}