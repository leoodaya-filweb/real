<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\Url;
use app\helpers\Html;
use app\models\Event;
use app\models\Member;
use app\models\EventMember;
use yii\helpers\ArrayHelper;
use app\models\UnPlannedAttendeesEvent;
use app\models\search\UnPlannedAttendeesEventSearch;

class UnPlannedAttendeesEventController extends Controller
{
    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            UnPlannedAttendeesEvent::findByKeywords($keywords, ['name', 'description'])
        );
    }

    public function actionIndex()
    {
        $searchModel = new UnPlannedAttendeesEventSearch();
        $dataProvider = $searchModel->search(['UnPlannedAttendeesEventSearch' => App::queryParams()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionChangeRecordStatus()
    {
        return $this->changeRecordStatus();
    }

    public function actionInActiveData()
    {
        # dont delete; use in condition if user has access to in-active data
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

    public function actionDelete($token)
    {
        $model = UnPlannedAttendeesEvent::controllerFind($token, 'token');

        if($model->delete()) {
            App::success('Successfully Deleted');
        }
        else {
            App::danger(json_encode($model->errors));
        }

        return $this->redirect($model->indexUrl);
    }

    public function actionView($token, $id='')
    {
        if ($id) {
            $model = UnPlannedAttendeesEvent::findOne($id);
            return $this->asJson([
                'status' => 'success',
                'link' => Anchor::widget([
                    'title' => 'View Social Pension Event',
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

        $model = UnPlannedAttendeesEvent::controllerFind($token, 'token');
        $data = $model->claimed(App::queryParams());

        $searchModel = $data['searchModel'];
        $searchModel->setAge(['event_id' => $model->id]);
        $dataProvider = $data['dataProvider'];

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($token='', $tab='general-information')
    {
        $model = UnPlannedAttendeesEvent::findByToken($token);

        if ($tab != 'general-information' && !$model) {
            App::warning('Please complete previous steps first!');
            return $this->redirect(['create']);
        }

        $model = $model ?: new UnPlannedAttendeesEvent([
            'status' => UnPlannedAttendeesEvent::PENDING,
            'record_status' => UnPlannedAttendeesEvent::RECORD_DRAFT,
            'category_type' => UnPlannedAttendeesEvent::UN_PLANNED_CATEGORY,
            'amount' => 0
        ]);

        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }

        $stepId = ArrayHelper::map($model::STEP_FORM, 'slug', 'id')[$tab];

        if (($post = App::post()) != null) {
            $post['UnPlannedAttendeesEvent']['oneday'] = $post['UnPlannedAttendeesEvent']['oneday'] ?? false;
            
            if ($tab == 'beneficiaries') {
                unset($post['_csrf']);
                $post['social_pension_status'] = [UnPlannedAttendees::SOCIAL_PENSIONER];
                $post['UnPlannedAttendeesEvent']['beneficiaries'] = $post;
            }

            if ($model->load($post)) {
                if ($model->save()) {
                    if ($tab == 'beneficiaries') {
                        $model->refresh();
                        $model->populateEventMembers();
                    }

                    App::success('Successfully Created');

                    if ($tab == 'summary') {
                        return $this->redirect(['view', 'token' => $model->token]);
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

    public function actionUpdate($token='', $tab='general-information')
    {
        $model = UnPlannedAttendeesEvent::controllerFind($token, 'token');

        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }

        $stepId = ArrayHelper::map($model::STEP_FORM, 'slug', 'id')[$tab];

        if (($post = App::post()) != null) {
            $post['UnPlannedAttendeesEvent']['oneday'] = $post['UnPlannedAttendeesEvent']['oneday'] ?? false;

            if ($tab == 'beneficiaries') {
                unset($post['_csrf']);
                $post['social_pension_status'] = [UnPlannedAttendees::SOCIAL_PENSIONER];
                $post['UnPlannedAttendeesEvent']['beneficiaries'] = $post;
            }

            if ($model->load($post)) {
                if ($model->save()) {
                    if ($tab == 'beneficiaries') {
                        $model->refresh();
                        $model->populateEventMembers();
                    }

                    App::success('Successfully Updated');

                    if ($tab == 'summary') {
                        return $this->redirect(['view', 'token' => $model->token]);
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

    public function actionSummaryReport($token)
    {
        $event = UnPlannedAttendeesEvent::controllerFind($token, 'token');

        return $this->asJson([
            'status' => 'success',
            'report' => $this->renderAjax('_summary', [
                'event' => $event
            ])
        ]);
    }
}