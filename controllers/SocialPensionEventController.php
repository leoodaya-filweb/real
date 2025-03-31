<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Event;
use app\models\EventMember;
use app\models\Masterlist;
use app\models\SocialPension;
use app\models\SocialPensionEvent;
use app\models\search\SocialPensionEventSearch;
use yii\helpers\ArrayHelper;

class SocialPensionEventController extends Controller
{
    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            SocialPensionEvent::findByKeywords($keywords, ['name', 'description'])
        );
    }

    public function actionIndex()
    {
        $searchModel = new SocialPensionEventSearch();
        $dataProvider = $searchModel->search(['SocialPensionEventSearch' => App::queryParams()]);

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
        $model = SocialPensionEvent::controllerFind($token, 'token');
        
        EventMember::deleteAllRow(['event_id' => $model->id]);

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
            $model = SocialPensionEvent::findOne($id);
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

        $model = SocialPensionEvent::controllerFind($token, 'token');
        $tab = App::get('tab') ?: 'unclaim';

        $data = $model->beneficiaries(App::queryParams());
        $searchModel = $data['searchModel'];
        $searchModel->setAge(['event_id' => $model->id]);
        $dataProvider = $data['dataProvider'];

        return $this->render('view', [
            'model' => $model,
            'tab' => $tab,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($token='', $tab='general-information')
    {
        $model = SocialPensionEvent::findByToken($token);

        if ($tab != 'general-information' && !$model) {
            App::warning('Please complete previous steps first!');
            return $this->redirect(['create']);
        }

        $model = $model ?: new SocialPensionEvent([
            'status' => SocialPensionEvent::PENDING,
            'record_status' => SocialPensionEvent::RECORD_DRAFT,
            'type' => SocialPensionEvent::ASSISTANCE,
            'assistance_type' => SocialPensionEvent::CASH,
            'category_type' => SocialPensionEvent::SOCIAL_PENSION_CATEGORY,
        ]);

        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }

        $stepId = ArrayHelper::map($model->stepForm, 'slug', 'id')[$tab];

        if (($post = App::post()) != null) {
            $post['SocialPensionEvent']['oneday'] = $post['SocialPensionEvent']['oneday'] ?? false;

            if ($model->load($post)) {
                if ($model->save()) {
                    App::success('Successfully Created');

                    if ($tab == 'general-information' && $token == null) {
                        $model->populatePensioners();
                    }

                    if ($tab == 'summary') {
                        return $this->redirect(['view', 'token' => $model->token]);
                    }

                    if (App::isAjax()) {
                        return $this->asJson([
                            'status' => 'success',
                            'redirect' => Url::to([
                                'create',
                                'token' => $model->token,
                                'tab' => ArrayHelper::map($model->stepForm, 'id', 'slug')[$stepId + 1]
                            ])
                        ]);
                    }
                    return $this->redirect([
                        'create',
                        'token' => $model->token,
                        'tab' => ArrayHelper::map($model->stepForm, 'id', 'slug')[$stepId + 1]
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
        $model = SocialPensionEvent::controllerFind($token, 'token');

        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }

        $stepId = ArrayHelper::map($model->stepForm, 'slug', 'id')[$tab];

        if (($post = App::post()) != null) {
            $post['SocialPensionEvent']['oneday'] = $post['SocialPensionEvent']['oneday'] ?? false;

            if ($model->load($post)) {
                if ($model->save()) {

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
                                'tab' => ArrayHelper::map($model->stepForm, 'id', 'slug')[$stepId + 1]
                            ])
                        ]);
                    }
                    return $this->redirect([
                        'update',
                        'token' => $model->token,
                        'tab' => ArrayHelper::map($model->stepForm, 'id', 'slug')[$stepId + 1]
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

    public function actionBulkAction()
    {
        return $this->bulkAction();
    }

    public function actionAddPensioner($token='', $keywords='', $social_pensioner_id='')
    {
        $response = [];

        $model = SocialPensionEvent::controllerFind($token, 'token');

        if ($model) {

            if ($social_pensioner_id) {
                if (($masterlist = Masterlist::findOne($social_pensioner_id)) != null) {
                    if($model->addSocialPensioner($masterlist)) {
                        App::success('Social pensioner added');
                    }
                    else {
                        App::danger($model->errorSummary);
                    }
                }
                else {
                    App::danger('Social pensioner not found');
                }

                return $this->redirect(App::referrer());
            }

            $arr = explode(' - ', $keywords);
            $id = end($arr);

            $masterlist = Masterlist::findOne($id);

            if ($masterlist) {
                $response['status'] = 'success';
                $response['detailView'] = $masterlist->detailView;

                $exist = EventMember::find()
                    ->where([
                        'event_id' => $model->id,
                        'social_pensioner_id' => $masterlist->id
                    ])
                    ->exists();

                if ($exist) {
                    $response['confirmBtn'] = Html::tag('label', 'Already Added', [
                        'class' => 'badge badge-success'
                    ]);
                }
                else {
                    $response['confirmBtn'] = Html::a('Add to List', ['add-pensioner', 'token' => $model->token, 'social_pensioner_id' => $masterlist->id], [
                        'class' => 'btn btn-outline-success font-weight-bold mb-5',
                        'data-confirm' => 'Are you sure?'
                    ]);
                }
            }
            else {
                $response['status'] = 'failed';
            }
        }
        else {
            $response['status'] = 'failed';
        }

        return $this->asJson($response);
    }
}