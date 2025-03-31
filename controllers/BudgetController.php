<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\models\Budget;
use app\models\Transaction;
use app\models\form\AddBudgetForm;
use app\models\form\DisbursedBudgetForm;
use app\models\search\BudgetSearch;

/**
 * BudgetController implements the CRUD actions for Budget model.
 */
class BudgetController extends Controller 
{
    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            Budget::findByKeywords($keywords, ['id'])
        );
    }

    /**
     * Lists all Budget models.
     * @return mixed
     */
    public function actionIndex()
    {
        $currentYear = date('Y', strtotime(App::formatter()->asDateToTimezone()));
        $year = App::get('year') ?: $currentYear;
        $model = new Budget(['year' => $year]);
        $model->type = Transaction::EMERGENCY_WELFARE_PROGRAM;

        if ($model->load(App::post())) {
            if ($model->save()) {
                App::success('Successfully Changed');
            }
            else {
                App::danger(Html::errorSummary($model));
            }

            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Budget model.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => Budget::controllerFind($id),
        ]);
    }

    /**
     * Creates a new Budget model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($action=Budget::INITIAL)
    {
        switch ($action) {
            case Budget::INITIAL:
                $model = new Budget([
                    'type' => Transaction::EMERGENCY_WELFARE_PROGRAM,
                    'specific_to' => Budget::INITIAL,
                    'action' => Budget::INITIAL,
                ]);
                $model->setToCurrentYear();
                $template = '_initial-budget';
                break;

            case Budget::ADD:
                $model = new AddBudgetForm();
                $template = '_add-budget';
                break;

            case Budget::SUBTRACT:
                $model = new DisbursedBudgetForm();
                $template = '_disburse-budget';
                break;
            
            default:
                // code...
                break;
        }

        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }

        if ($model->load(App::post()) && $model->save()) {
            if (App::isAjax()) {
                return $this->_ajaxCreated($model);
            }
            App::success('Successfully Recorded');

            return $this->redirect($model->viewUrl);
        }

        if (App::isAjax()) {
            return $this->_ajaxForm($model, $template);
        }

        return $this->redirect(App::referrer());
    }

    /**
     * Duplicates a new Budget model.
     * If duplication is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*_ublic function actionDuplicate($id)
    {
        $originalModel = Budget::controllerFind($id);
        $model = new Budget();
        $model->attributes = $originalModel->attributes;

        if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Duplicated');

            return $this->redirect($model->viewUrl);
        }

        return $this->render('duplicate', [
            'model' => $model,
            'originalModel' => $originalModel,
        ]);
    }*/

    /**
     * Updates an existing Budget model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($id, $action=Budget::INITIAL)
    {
        switch ($action) {
            case Budget::INITIAL:
                $model = Budget::controllerFind($id);
                $template = '_initial-budget';
                break;

            case Budget::ADD:
                $model = new AddBudgetForm([
                    'budget_id' => $id,
                    'scenario' => 'update' 
                ]);
                $template = '_add-budget';
                break;

            case Budget::SUBTRACT:
                $model = new DisbursedBudgetForm([
                    'budget_id' => $id,
                    'scenario' => 'update' 
                ]);
                $template = '_disburse-budget';
                break;
            
            default:
                // code...
                break;
        }


        if (App::get('ajaxValidate')) {
            return $this->_ajaxValidate($model);
        }

        if ($model->load(App::post()) && $model->save()) {
            if (App::isAjax()) {
                return $this->_ajaxCreated($model);
            }

            App::success('Successfully Updated');
            return $this->redirect($model->viewUrl);
        }

        if (App::isAjax()) {
            return $this->_ajaxForm($model, $template, [
                'validationUrl' => ['budget/update', 'action' => $action, 'id' => $id, 'ajaxValidate' => true]
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Budget model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Budget::controllerFind($id);

        if($model->delete()) {
            App::success('Successfully Deleted');
        }
        else {
            App::danger(json_encode($model->errors));
        }

        return $this->redirect(App::referrer());
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