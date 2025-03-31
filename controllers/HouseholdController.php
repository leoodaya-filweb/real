<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\jobs\ImportHouseholdJob;
use app\models\Household;
use app\models\Member;
use app\models\Queue;
use app\models\form\HouseholdSummaryForm;
use app\models\form\BulkImportHouseholdForm;
use app\models\form\household\FamilyCompositionForm;
use app\models\form\household\FamilyHeadForm;
use app\models\form\household\GeneralInformationForm;
use app\models\search\HouseholdSearch;
use app\models\search\MemberSearch;
use app\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * HouseholdController implements the CRUD actions for Household model.
 */
class HouseholdController extends Controller 
{
    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            Household::findByKeywords($keywords, [
                'b.name', 
                'h.no', 
                'h.zone_no', 
                'h.purok_no', 
                'h.blk_no', 
                'h.lot_no', 
                'h.street'
            ])
        );
    }

    /**
     * Lists all Household models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HouseholdSearch();
        $dataProvider = $searchModel->search(['HouseholdSearch' => App::queryParams()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Household model.
     * @param integer $no
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($no, $step='overview')
    {
        if ($step == 'id' && App::isAjax()) {
            $model = Household::controllerFind($no, 'id');

            return $this->asJson([
                'status' => 'success',
                'model' => $model
            ]);
        }
        $model = Household::controllerFind($no, 'no');
        $step_forms = $model::STEP_FORM_VIEW;

        return $this->render('view', [
            'model' => $model,
            'step' => ArrayHelper::map($step_forms, 'slug', 'id')[$step],
            'step_forms' => $step_forms,
        ]);
    }


    protected function getModel($no, $step, $action='create')
    {
        $household = Household::findOne(['no' => $no]);
        switch ($step) {
            case 'general-information':
                if ($household) {
                    $model = $household;
                }
                else {
                    $model = new Household();
                    $model->setTheNo();
                }
                $model->scenario = 'manual';
                break;
            case 'map':
              
                if($household) {
                    $model = $household;
                    $model->scenario = 'map';
                }
                else {
                    $model = [
                        'message' => 'Please complete Household information first.',
                        'url' => [$action]
                    ];
                }
                break;
            case 'family-head':
                $model = ($household)? $household->familyHeadForm: [
                    'message' => 'Please complete Household information first.',
                    'url' => [$action]
                ];
                break;
            case 'family-composition':
                if ($household) {
                    if ($household->familyHead) {
                        $model = $household->familyCompositionForm;
                    }
                    else {
                        $model = [
                            'message' => 'Please complete Family head information first.',
                            'url' => [$action, 'no' => $household->no, 'step' => 'family-head']
                        ];
                    }
                }
                else {
                    $model = [
                        'message' => 'Please complete Household information first.',
                        'url' => [$action]
                    ];
                }
                break;

            case 'summary':
                if ($household) {
                    if (($head = $household->familyHead) != null) {
                        $model = new HouseholdSummaryForm([
                            'household_no' => $household->no,
                            'head_id' => $head->id,
                            'members_id' => $household->familyCompositionsId
                        ]);
                    }
                    else {
                        $model = [
                            'message' => 'Please complete Family head information first.',
                            'url' => [$action, 'no' => $household->no, 'step' => 'family-head']
                        ];
                    }
                }
                else {
                    $model = [
                        'message' => 'Please complete Household information first.',
                        'url' => [$action]
                    ];
                }
                break;
            default:
              
                break;
        }

        return $model;
    }
    /**
     * Creates a new Household model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($no='', $step='general-information')
    {
        $model = $this->getModel($no, $step);
        $step_forms = Household::STEP_FORM;
        $stepId = ArrayHelper::map($step_forms, 'slug', 'id')[$step];

        if (is_array($model)) {
            App::warning($model['message']);
            return $this->redirect($model['url']);
        }

        if (($post = App::post()) != null) {
           
            if ($step == 'summary') {
                if ($model->save()) {
                    App::success('Successfully Created');
                    return $this->redirect([
                        'view', 
                        'no' => $model->householdNo
                    ]);
                }
                else {
                    App::danger($model->errors);
                    return $this->redirect([
                        'create', 
                        'no' => $model->householdNo, 
                        'step' => $step
                    ]);
                }
            }
            
            $post['Household']['files'] = $post['Household']['files'] ?? null;
            
            if ($model->load($post) && $model->save()) {
                $model->new_cbms=1;
              if($model->save()) {
                App::success('Successfully Created');
                return $this->redirect([
                    'create', 
                    'no' => $model->householdNo,
                    'step' => ArrayHelper::map($step_forms, 'id', 'slug')[$stepId + 1]
                ]);
                 }
                
                
            }
        }
        return $this->render('create', [
            'household' => Household::findOne(['no' => $model->householdNo]) ?: new Household(),
            'model' => $model,
            'step' => $stepId,
            'step_forms' => $step_forms,
        ]);
    }

    /**
     * Duplicates a new Household model.
     * If duplication is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function _actionDuplicate($id)
    // {
    //     $originalModel = Household::controllerFind($id);
    //     $model = new Household();
    //     $model->attributes = $originalModel->attributes;

    //     if ($model->load(App::post()) && $model->save()) {
    //         App::success('Successfully Duplicated');

    //         return $this->redirect($model->viewUrl);
    //     }

    //     return $this->render('duplicate', [
    //         'model' => $model,
    //         'originalModel' => $originalModel,
    //     ]);
    // }

    /**
     * Updates an existing Household model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($no='', $step='general-information')
    {
        $model = $this->getModel($no, $step, 'update');
        $step_forms = Household::STEP_FORM;
        $stepId = ArrayHelper::map($step_forms, 'slug', 'id')[$step];

        if (is_array($model)) {
            App::warning($model['message']);
            return $this->redirect($model['url']);
        }

        if (($post = App::post()) != null) {
            if ($step == 'summary') {
                if ($model->save()) {
                    App::success('Successfully Updated');
                    return $this->redirect([
                        'view', 
                        'no' => $model->householdNo
                    ]);
                }
                else {
                    App::danger($model->errors);
                    return $this->redirect([
                        'update', 
                        'no' => $model->householdNo, 
                        'step' => $step
                    ]);
                }
            }

            $post['Household']['files'] = $post['Household']['files'] ?? null;
            
            if ($model->load($post) && $model->save()) {
                App::success('Successfully Updated');
                return $this->redirect([
                    'update', 
                    'no' => $model->householdNo,
                    'step' => ArrayHelper::map($step_forms, 'id', 'slug')[$stepId + 1]
                ]);
            }
        }

        $data = [
            'household' => Household::findOne(['no' => $model->householdNo]) ?: new Household(),
            'model' => $model,
            'step' => $stepId,
            'step_forms' => Household::STEP_FORM,
        ];

        return $this->render('update', $data);
    }

    /**
     * Deletes an existing Household model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($no)
    {
        $model = Household::controllerFind($no, 'no');

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

    public function actionImport()
    {
        $model = new BulkImportHouseholdForm();
        $household = new Household();

        if ($model->load(App::post())) {
            if ($model->validate()) {
                Queue::push(new ImportHouseholdJob([
                    'file_token' => $model->file_token,
                    'user_id' => App::identity('id')
                ]));
                App::success('The household data will be imported in the queue.');
            }
            else {
                App::danger(Html::errorSummary($model));
            }

            return $this->redirect(['import']);
        }

        return $this->render('import', [
            'household' => $household,
            'model' => $model,
        ]);
    }

    public function actionValidateFile($file_token='')
    {
        $model = new BulkImportHouseholdForm([
            'scenario' => 'contentValidation',
            'file_token' => $file_token
        ]);
        if ($model->validate()) {
            return $this->asJson([
                'status' => 'success',
                'message' => 'Valid'
            ]);
        }
        else {
            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => Html::errorSummary($model)
            ]);
        }
    }

    public function actionSaveFamilyComposition()
    {
        $model = new FamilyCompositionForm();

        if ($model->load(App::post())) {
            if ($model->saveMember()) {
                App::success('Successfully Created');
            }
            else {
                App::danger($model->errors);
            }
        }

        return $this->redirect(App::referrer());
    }

    public function actionAddFamilyComposition($no, $member_id='')
    {
        $household = Household::findOne(['no' => $no]);
        if ($household) {
            $model = $household->getFamilyCompositionForm($member_id);

            if (App::get('ajaxValidate')) {
                if ($model->load(App::post())) {
                    return $this->asJson(ActiveForm::validate($model));
                }
            }

            if ($model->load(App::post())) {
                $model->new_cbms=1;
                if ($model->save()) {
                    App::success('Family Composition Added');
                }
                else {
                    App::danger($model->errors);
                }
                return $this->redirect(App::referrer());
            }

            return $this->asJson([
                'status' => 'success',
                'form' => $this->renderAjax('_form/_family-composition-form', [
                    'model' => $model,
                    'household' => $household,
                ])
            ]);
        }
        else {
            return $this->asJson([
                'status' => 'failed',
                'error' => 'No household found.'
            ]);
        }
    }

    public function actionDeleteMember($id='')
    {
        $model = Member::findOne($id);

        if ($model && $model->delete()) {
            return $this->asJson([
                'status' => 'success',
                'message' => 'Member has been deleted.'
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'error' => 'There\'s a problem in deleting.'
        ]);
    }

    public function actionUpdateMember($id)
    {
        $model = Member::findOne($id);
        $model->scenario = 'family-composition';

        if ($model && $model->load(App::post())) {
            if ($model->save()) {
                App::success('Family Composition Updated');
            }
            else {
                App::danger($model->errors);
            }

            return $this->redirect(App::referrer());
        }


        if ($model) {
            return $this->asJson([
                'status' => 'success',
                'form' => $this->renderAjax('_form/_family-composition-form', [
                    'model' => $model,
                    'household' => Household::findOne($model->household_id),
                    'action' => ['household/update-member', 'id' => $model->id]
                ])
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'error' => 'No data found.'
        ]);
    }
}