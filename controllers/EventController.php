<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Event;
use app\models\EventMember;
use app\models\Member;
use app\models\form\event\EventDocumentForm;
use app\models\form\export\ExportCsvForm;
use app\models\form\export\ExportExcelForm;
use app\models\form\export\ExportPdfForm;
use app\models\search\EventMemberSearch;
use app\models\search\EventSearch;
use app\models\search\MemberSearch;
use app\widgets\Anchor;
use app\widgets\EventSummary;
use yii\helpers\ArrayHelper;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller 
{
    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            Event::findByKeywords($keywords, ['name', 'description'])
        );
    }

    /**
     * Lists all Event models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventSearch();
        $dataProvider = $searchModel->search(['EventSearch' => App::queryParams()]);
        $dataProvider->query->default_category();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Event model.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($token, $id='')
    {
        if ($id) {
            $model = Event::findOne($id);
            return $this->asJson([
                'status' => 'success',
                'link' => Anchor::widget([
                    'title' => 'View Event',
                    'link' => $model->viewUrl,
                    'text' => true,
                    'options' => [
                        'target' => '_blank',
                        'class' => 'btn btn-light-primary btn-sm font-weight-bolder'
                    ]
                ]),
                'detail' => $model->detailView
            ]);
        }

        $model = Event::controllerFind($token, 'token');

        if ($model->category_type == Event::SOCIAL_PENSION_CATEGORY) {
            return $this->redirect(['social-pension-event/view', 'token' => $model->token]);
        }

        $tab = App::get('tab') ?: 'unclaim';

        $data = $model->beneficiaries(App::queryParams());
        $claimed = $model->claimed(App::queryParams());

        $u_searchModel = $data['searchModel'];
        $u_searchModel->setAge(['event_id' => $model->id]);
        $u_dataProvider = $data['dataProvider'];

        $c_searchModel = $claimed['searchModel'];
        $c_searchModel->setAge(['event_id' => $model->id]);
        $c_dataProvider = $claimed['dataProvider'];

        return $this->render('view', [
            'model' => $model,
            'tab' => $tab,
            'unclaimClass' => ($tab == 'unclaim')? 'active': '',
            'claimClass' => ($tab == 'claimed')? 'active': '',
            'u_searchModel' => $u_searchModel,
            'u_dataProvider' => $u_dataProvider,
            'c_searchModel' => $c_searchModel,
            'c_dataProvider' => $c_dataProvider,
        ]);
    }

    /**
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($token='', $tab='general-information')
    {
        $model = Event::findByToken($token);

        if ($tab != 'general-information' && !$model) {
            App::warning('Please complete previous steps first!');
            return $this->redirect(['create']);
        }

        $model = $model ?: new Event([
            'status' => Event::PENDING,
            'record_status' => Event::RECORD_DRAFT,
            'amount' => 0
        ]);
        $model->scenario = Event::SCENARIO_DEFAULT;

        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }

        $stepId = ArrayHelper::map($model::STEP_FORM, 'slug', 'id')[$tab];

        if (($post = App::post()) != null) {
            $post['Event']['oneday'] = $post['Event']['oneday'] ?? false;
            if ($tab == 'beneficiaries') {
                unset($post['_csrf']);
                $post['Event']['beneficiaries'] = $post;
            }

            if ($model->load($post)) {
                if ($model->save()) {
                    if ($tab == 'beneficiaries') {
                        $model->refresh();
                        $model->populateEventMembers();
                    }

                    App::success('Successfully Created');

                    if ($tab == 'summary') {
                        return $this->redirect($model->viewUrl);
                    }

                    if (App::isAjax()) {
                        return $this->asJson([
                            'status' => 'success',
                            'redirect' => Url::to([
                                'create',
                                'token' => $model->token,
                                'tab' => ArrayHelper::map($model::STEP_FORM, 'id', 'slug')[$stepId + 1]
                            ])
                        ]);
                    }
                    return $this->redirect([
                        'create',
                        'token' => $model->token,
                        'tab' => ArrayHelper::map($model::STEP_FORM, 'id', 'slug')[$stepId + 1]
                    ]);
                }
                else {
                    App::danger($model->errorSummary);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'tab' => $tab,
        ]);
    }

    /**
     * Duplicates a new Event model.
     * If duplication is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDuplicate($token)
    {
        $originalModel = Event::controllerFind($token, 'token');
        $model = new Event();
        $model->attributes = $originalModel->attributes;
        $model->record_status = Event::RECORD_DRAFT;
        $model->status = Event::PENDING;

        if ($model->save()) {
            App::success('Successfully Duplicated');
            return $this->redirect($model->updateUrl);
        }
        else {
            App::danger($model->errorSummary);
            return $this->redirect(App::referrer());
        }

        // return $this->render('duplicate', [
        //     'model' => $model,
        //     'originalModel' => $originalModel,
        // ]);
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($token='', $tab='general-information')
    {
        $model = Event::controllerFind($token, 'token');
        $model->scenario = Event::SCENARIO_DEFAULT;

        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }

        $stepId = ArrayHelper::map($model::STEP_FORM, 'slug', 'id')[$tab];

        if (($post = App::post()) != null) {
            $post['Event']['oneday'] = $post['Event']['oneday'] ?? false;

            if ($tab == 'beneficiaries') {
                unset($post['_csrf']);
                $post['Event']['beneficiaries'] = $post;
            }

            if ($model->load($post)) {
                if ($model->save()) {
                    if ($tab == 'beneficiaries') {
                        $model->refresh();
                        $model->populateEventMembers();
                    }

                    App::success('Successfully Updated');

                    if ($tab == 'summary') {
                        return $this->redirect($model->viewUrl);
                    }

                    if (App::isAjax()) {
                        return $this->asJson([
                            'status' => 'success',
                            'redirect' => Url::to([
                                'update',
                                'token' => $model->token,
                                'tab' => ArrayHelper::map($model::STEP_FORM, 'id', 'slug')[$stepId + 1]
                            ])
                        ]);
                    }
                    return $this->redirect([
                        'update',
                        'token' => $model->token,
                        'tab' => ArrayHelper::map($model::STEP_FORM, 'id', 'slug')[$stepId + 1]
                    ]);
                }
                else {
                    App::danger($model->errorSummary);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'tab' => $tab,
        ]);
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($token)
    {
        $model = Event::controllerFind($token, 'token');

        EventMember::deleteAllRow(['event_id' => $model->id]);

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

    public function actionClaim($id, $member_id)
    {
        return ;
    }

    public function actionFindBeneficiaries()
    {
        $searchModel = new MemberSearch([
            'living_status' => Member::ALIVE
        ]);
        $dataProvider = $searchModel->search(['MemberSearch' => App::queryParams()]);

        return $this->asJson([
            'status' => 'success',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => App::formatter('asNumber', $dataProvider->totalCount)
        ]);
    }

    public function actionRemoveMembers($token)
    {
        $response = [];

        $model = Event::findByToken($token);

        if ($model) {
            if (($post = App::post()) != null && isset($post['member_ids'])) {

                if ($model->removeMembers($post['member_ids'])) {
                    App::success('Member removed from the list.');
                    $response['status'] = 'success';
                    $response['message'] = 'Removed.';
                }
                else {
                    $response['status'] = 'failed';
                    $response['error'] = $model->errorSummary;
                }
            }
            else {
                $response['status'] = 'failed';
                $response['error'] = 'No post data.';
            }
        }
        else {
            $response['status'] = 'failed';
            $response['error'] = 'No event found.';
        }

        return $this->asJson($response);
    }

    public function actionAddMember($keywords='', $token='', $member_id='', $template='_detail')
    {
        $event = Event::findByToken($token);
        if (! $event) {
            return $this->asJson([
                'status' => 'failed',
                'error' => 'No event found.'
            ]);
        }

        if ($member_id) {
            if ($event->addMember($member_id)) {
                App::success('Member added.');
            }
            else {
                App::danger($event->errorSummary);
            }

            return $this->redirect(App::referrer());
        }

        $arr = explode(' - ', $keywords);


        $member = Member::findOne(['qr_id' => end($arr)]);

        if ($member) {
            return $this->asJson([
                'status' => 'success',
                'detailView' => $this->renderAjax("/member/{$template}", [
                    'model' => $member,
                    'event' => $event,
                    'eventMember' => EventMember::findOne([
                        'member_id' => $member->id,
                        'event_id' => $event->id,
                    ]) ?: new EventMember([
                        'member_id' => $member->id,
                        'event_id' => $event->id,
                    ])
                ]),
                'confirmBtn' => $event->getConfirmBtn($member)
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'error' => 'No member found.'
        ]);
    }

    public function actionChangeStatus($token, $status)
    {
        $model = Event::controllerFind($token, 'token');

        if ($model) {

            if ($status == Event::PENDING && ! $model->canPending) {
                App::danger('Event cannot set to pending');
                return $this->redirect(App::referrer());
            }

            if (Event::updateAll(['status' => $status], ['id' => $model->id])) {
                App::success('Status Changed');
            }
            else {
                App::danger($model->errorSummary);
            }
        }
        else {
            App::danger('Event not found');
        }

        return $this->redirect(App::referrer());
    }

    public function actionAddDocument()
    {
        $response = [];

        if (($post = App::post()) != null) {
            $model = new EventDocumentForm([
                'event_id' => $post['id'] ?? 0,
                'file_token' => $post['token'] ?? '',
            ]);

            if ($model->save()) {
                $response['status'] = 'success';
                $response['message'] = 'Document Added';
                $response['model'] = $model;
                $response['event'] = $model->getEvent();
                $response['file'] = $model->getFile();
                $response['row'] = $this->renderPartial('/file/_row', [
                    'model' => $model->getFile()
                ]);
            }
            else {
                $response['status'] = 'failed';
                $response['error'] = Html::errorSummary($model);
            }
        }
        else {
            $response['status'] = 'failed';
            $response['error'] = 'No post data';
        }

        return $this->asJson($response);
    }

    public function actionRemoveDocument()
    {
        $response = [];

        if (($post = App::post()) != null) {
            $model = new EventDocumentForm([
                'event_id' => $post['id'] ?? 0,
                'file_token' => $post['token'] ?? '',
            ]);
            $model->scenario = 'remove';

            if ($model->save()) {
                $response['status'] = 'success';
                $response['message'] = 'Document Removed';
                $response['model'] = $model;
                $response['event'] = $model->getEvent();
                $response['file'] = $model->getFile();
            }
            else {
                $response['status'] = 'failed';
                $response['error'] = Html::errorSummary($model);
            }
        }
        else {
            $response['status'] = 'failed';
            $response['error'] = 'No post data';
        }

        return $this->asJson($response);
    }

    public function actionSummaryReport($token)
    {
        $event = Event::controllerFind($token, 'token');

        return $this->asJson([
            'status' => 'success',
            'report' => $this->renderAjax('_summary', [
                'event' => $event
            ])
        ]);
    }

    public function actionExportSummary($token, $type='print', $template='index')
    {
        $event = Event::controllerFind($token, 'token');
        
        switch ($type) {
            case 'print':
                $this->layout = 'print';
                return $this->render('/layouts/_print', [
                    'content' => EventSummary::widget([
                        'event' => $event,
                        'template' => $template
                    ])
                ]);
                break;

            case 'pdf':
                $model = new ExportPdfForm([
                    'content' => EventSummary::widget([
                        'event' => $event,
                        'template' => $template
                    ])
                ]);
                return $model->export();
                break;

            case 'csv':
                $model = new ExportCsvForm([
                    'content' => EventSummary::widget([
                        'event' => $event,
                        'template' => $template
                    ])
                ]);
                return $model->export();
                break;

            case 'xlsx':
                $model = new ExportExcelForm([
                    'type' => 'xlsx',
                    'content' => EventSummary::widget([
                        'event' => $event,
                        'template' => $template
                    ])
                ]);
                return $model->export();
                break;
            
            
            default:
                // code...
                break;
        }
    }
}