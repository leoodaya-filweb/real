<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\models\Event;
use app\models\EventMember;
use app\widgets\ExportContent;
use app\models\search\EventMemberSearch;
use app\models\form\export\ExportPdfForm;

/**
 * EventMemberController implements the CRUD actions for EventMember model.
 */
class EventMemberController extends Controller 
{
    public function actionFindByKeywords($keywords='', $event_id='', $type='pending')
    {
        $status = $type == 'pending' ? [
            EventMember::UNCLAIM,
            EventMember::UNATTENDED,
        ] :[
            EventMember::CLAIMED,
            EventMember::ATTENDED,
        ];

        return $this->asJson(
            EventMember::findByKeywords($keywords, [
                'name',
                'qr_id',
                'household_no',
                'gender',
                'civil_status',
                'educational_attainment',
                'pwd_type',
                'barangay',
                'purok_no',
                'age',
            ], 10, [
                'event_id' => $event_id,
                'status' => $status
            ])
        );
    }

    /**
     * Lists all EventMember models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventMemberSearch();
        $dataProvider = $searchModel->search(['EventMemberSearch' => App::queryParams()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EventMember model.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => EventMember::controllerFind($id),
        ]);
    }

    /**
     * Creates a new EventMember model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EventMember();

        if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Created');

            return $this->redirect($model->viewUrl);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Duplicates a new EventMember model.
     * If duplication is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDuplicate($id)
    {
        $originalModel = EventMember::controllerFind($id);
        $model = new EventMember();
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
     * Updates an existing EventMember model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = EventMember::controllerFind($id);

        if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Updated');
            return $this->redirect($model->viewUrl);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EventMember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = EventMember::controllerFind($id);

        if($model->delete()) {
            App::success('Successfully Deleted');
        }
        else {
            App::danger(json_encode($model->errors));
        }

        return $this->redirect(App::referrer());
        // return $this->redirect($model->indexUrl);
    }

    public function actionChangeRecordStatus()
    {
        return $this->changeRecordStatus();
    }

    public function actionBulkAction()
    {
        return $this->bulkAction();
    }

    public function actionPrint($reportName = 'Beneficiaries')
    {
        return $this->render('/layouts/_print', [
            'content' => ExportContent::widget([
                'file' => 'pdf',
                'reportName' => $reportName,
                'searchModel' => new EventMemberSearch(),
            ])
        ]);
    }

    public function actionExportPdf($reportName = 'Beneficiaries')
    {
        $model = new ExportPdfForm([
            'content' => ExportContent::widget([
                'file' => 'pdf',
                'reportName' => $reportName,
                'searchModel' => new EventMemberSearch(),
            ])
        ]);
        return $model->export();
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

    public function actionReceive()
    {
        if (($post = App::post()) != null) {

            $model = EventMember::findOne([
                'event_id' => $post['event_id'],
                'member_id' => $post['member_id'],
                'status' => [
                    EventMember::UNCLAIM,
                    EventMember::UNATTENDED,
                ]
            ]);

            $model = $model ?: new EventMember([
                'event_id' => $post['event_id'],
                'member_id' => $post['member_id'],
                'status' => EventMember::ATTENDED
            ]);

            if ($model) {

                $model->photo = $post['photo'];

                if ($model->received()) {

                    $event = $model->event;
                    // $data = $event->beneficiaries(App::queryParams());
                    // $claimed = $event->claimed(App::queryParams());

                    return $this->asJson([
                        'status' => 'success',
                        'model' => $model,
                        // 'member' => $model->member,
                        'message' => 'Assistance Received.',
                        // 'beneficiaries' => $this->renderAjax('/event/_beneficiaries-grid', [
                        //     'dataProvider' => $data['dataProvider'],
                        //     'searchModel' => $data['searchModel'],
                        //     'model' => $event,
                        // ]),
                        // 'claimed' => $this->renderAjax('/event/_claimed', [
                        //     'dataProvider' => $claimed['dataProvider'],
                        //     'searchModel' => $claimed['searchModel'],
                        //     'model' => $event,
                        // ])
                    ]);
                }
                else {
                    return $this->asJson([
                        'status' => 'failed',
                        'error' => Html::errorSummary($model)
                    ]);
                }
            }
            else {
                return $this->asJson([
                    'status' => 'failed',
                    'error' => 'data not found or already attended/claimed.'
                ]);
            }
        }

        return $this->asJson([
            'status' => 'failed',
            'error' => 'No post data.'
        ]);
    }
}