<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\ArrayHelper;
use app\models\Allowance;
use app\models\Scholarship;
use app\models\search\ScholarshipSearch;

/**
 * ScholarshipController implements the CRUD actions for Scholarship model.
 */
class ScholarshipController extends Controller 
{
    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            Scholarship::findByKeywords($keywords, ['id'])
        );
    }

    /**
     * Lists all Scholarship models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScholarshipSearch();
        $dataProvider = $searchModel->search(['ScholarshipSearch' => App::queryParams()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Scholarship model.
     * @param integer $token
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($token, $tab='general')
    {
        $model = Scholarship::controllerFind($token, 'token');

        if ($tab == 'interview') {
            $model->scenario = Scholarship::SCENARIO_INTERVIEW;
            $model->interviewer = $model->interviewer ?: $model->createdByName;
        }

        return $this->render('view', [
            'model' => $model,
            'tab' => $tab,
        ]);
    }

    /**
     * Creates a new Scholarship model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($token='', $tab='general-information')
    {
        $model = Scholarship::findOrCreate(['token' => $token]);
        if ($model->isNewRecord) {
            $model->record_status = Scholarship::RECORD_DRAFT;
        }
        
        $stepId = ArrayHelper::map($model::STEP_FORM, 'slug', 'id')[$tab];

        if (($post = App::post()) != null) {
            if ($tab == 'educations') {
                $post['Scholarship']['educations'] = array_values($post['Scholarship']['educations'] ?? []);
            }
            if ($tab == 'review') {
                $model->status = Scholarship::FOR_INTERVIEW;
                $model->record_status = Scholarship::RECORD_ACTIVE;
            }

            if ($model->load($post) && $model->save()) {
                App::success('Successfully Created');

                if ($tab == 'review') {
                    return $this->redirect($model->viewUrl);
                }

                return $this->redirect([
                    'create',
                    'token' => $model->token,
                    'tab' => ArrayHelper::map($model::STEP_FORM, 'id', 'slug')[$stepId + 1]
                ]);

                return $this->redirect($model->viewUrl);
            }
            $model->flashErrors();
        }

        return $this->render('create', [
            'model' => $model,
            'tab' => $tab,
        ]);
    }
 

    /**
     * Updates an existing Scholarship model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($token='', $tab='general-information')
    {
        $model = Scholarship::findOrCreate(['token' => $token]);
        $stepId = ArrayHelper::map($model::STEP_FORM, 'slug', 'id')[$tab];

        if (($post = App::post()) != null) {
            if ($tab == 'educations') {
                $post['Scholarship']['educations'] = array_values($post['Scholarship']['educations'] ?? []);
            }
            if ($tab == 'review') {
                $model->status = $model->isPending ? Scholarship::FOR_INTERVIEW: $model->status;
                $model->record_status = Scholarship::RECORD_ACTIVE;
            }
            if ($model->load($post) && $model->save()) {
                App::success('Successfully Updated');

                if ($tab == 'review') {
                    return $this->redirect($model->viewUrl);
                }

                return $this->redirect([
                    'update',
                    'token' => $model->token,
                    'tab' => ArrayHelper::map($model::STEP_FORM, 'id', 'slug')[$stepId + 1]
                ]);

                return $this->redirect($model->viewUrl);
            }

            $model->flashErrors();
        }

        return $this->render('update', [
            'model' => $model,
            'tab' => $tab,
        ]);
    }

    /**
     * Deletes an existing Scholarship model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Scholarship::controllerFind($id);

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

    public function actionAddDocument()
    {
        $document = App::post('document');
        $token = App::post('token');

        $model = Scholarship::controllerFind($token, 'token');

        if ($document && $model) {
            $documents = $model->documents;
            array_push($documents, $document);

            // if ($model->save()) {
                Scholarship::updateAll(
                    ['documents' => json_encode($documents)],
                    ['token' => $model->token]
                );

                return $this->asJson([
                    'status' => 'success',
                    'message' => 'Document added'
                ]);
            // }

            // return $this->asJson([
            //     'status' => 'failed',
            //     'errorSummary' => $model->errorSummary
            // ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'Document not Found'
        ]);
    }

    public function actionSaveEducation($token)
    {
        $educations = App::post('Scholarship')['educations'] ?? [];
        $model = Scholarship::controllerFind($token, 'token');

        if ($model) {
            $model->educations = $educations;
            $model->save();
            // Scholarship::updateAll(
            //     ['educations' => json_encode($educations)],
            //     ['token' => $token]
            // );
            $model->refresh();

            return $this->asJson([
                'status' => 'success',
                'message' => 'Educations saved',
                'model' => $model,
                'educations' => App::foreach($model->educations, fn($education) => $this->renderPartial('view/_education', [
                    'education' => $education
                ]))
            ]);
        }

        return $this->asJson([
            'status' => 'failed',
            'errorSummary' => 'Document not Found'
        ]);
    }

    public function actionSaveInterview($token)
    {
        $model = Scholarship::controllerFind($token, 'token');

        if (!$model) {
            App::danger('No Scholarship found');
            return $this->redirectReferrer();
        }

        if (($post = App::post()) == null) {
            App::danger('No post data');
            return $this->redirectReferrer();
        }

        $post['Scholarship']['notes'] = $post['Scholarship']['notes'] ?? [];
        $model->load($post);

        if (! $model->save()) {
            App::danger($model->errorSummary);
            return $this->redirectReferrer();
        }

        App::success('Interview Saved!');
        return $this->redirectReferrer();
    }

    public function actionAddInterviewAttachment()
    {
        if (($document = App::post('document')) == null) {
            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => 'Document not Found'
            ]);
        }
        if (($model = Scholarship::controllerFind(App::post('token'), 'token')) == null) {
            return $this->asJson([
                'status' => 'failed',
                'errorSummary' => 'Scholarship not Found'
            ]);
        }

        $interview_attachments = $model->interview_attachments;
        array_push($interview_attachments, $document);

        Scholarship::updateAll(
            ['interview_attachments' => json_encode($interview_attachments)],
            ['token' => $model->token]
        );

        return $this->asJson([
            'status' => 'success',
            'message' => 'Attachments added'
        ]);
    }
}