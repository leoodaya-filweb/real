<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\models\Database;
use app\models\form\PrioritySectorForm;
use app\models\form\export\ExportCsvForm;
use app\models\form\export\ExportExcelForm;
use app\models\form\setting\PrioritySectorSettingsForm;
use app\models\search\DatabaseSearch;
use app\widgets\Grid;
use yii\helpers\ArrayHelper;
use app\models\form\DatabaseImportForm;
use app\jobs\ImportDatabaseJob;
use app\models\Queue;

/**
 * DatabaseController implements the CRUD actions for Database model.
 */
class DatabaseController extends Controller 
{
    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            Database::findByKeywords($keywords, [
                'sector_id',
                'last_name',
                'first_name',
                'middle_name',
                'trim(CONCAT_WS(" ", `first_name`, `last_name`))',
                'trim(CONCAT_WS(" ", `last_name`, `first_name`))',
                'trim(CONCAT_WS(" ", `first_name`, `middle_name`, `last_name`))',
                'trim(CONCAT_WS(" ", `last_name`, `first_name`, `middle_name`))',
                'barangay'
            ])
        );
    }

    public function actionFindBySelectorId($priority_sector, $sector_id)
    {
        $model = Database::find()
            ->where([
                'priority_sector' => $priority_sector,
                'sector_id' => $sector_id,
            ])
            ->asArray()
            ->one();

        return $this->asJson(
            ($model ?: null)
        );
    }

    /**
     * Lists all Database models.
     * @return mixed
     */
    public function actionIndex($priority_sector=null)
    {
        $searchModel = new DatabaseSearch([
            'status' => 'Active',
            'priority_sector' => $priority_sector
        ]);
        
        $dataProvider = $searchModel->search(['DatabaseSearch' => App::queryParams()]);

        $searchModelReport = new DatabaseSearch(['load_params'=>false]);
        $dataProviderReport = $searchModelReport->searchreport([
		    'DatabaseSearch' => App::queryParams()
		]);

        
        return $this->render('card', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderReport' => $dataProviderReport,
        ]);
    }

    public function actionMember($print=null)
    {
        $searchModel = new DatabaseSearch([
            'status' => 'Active',
        ]);
        $dataProvider = $searchModel->search(['DatabaseSearch' => App::queryParams()]);

        $searchModelReport = new DatabaseSearch(['load_params'=>false]);
        $dataProviderReport = $searchModelReport->searchreport([
            'DatabaseSearch' => App::queryParams()
        ]);

        $from = $dataProviderReport->query->createCommand()->rawSql;
        
        $rowsummary = Yii::$app->db->createCommand("SELECT  
           sum(male_active) as male_active_total,
           sum(female_active) as female_active_total,
           sum(active) as active_total
        FROM ($from)sc")->queryOne();
        
        
        if($print==1){
          $this->layout = "@app/views/layouts/print_v2";
          
          $dataProvider->pagination = false;
          //$dataProvider->sort = false;
         // $dataProvider->query->orderby(['date_registered'=>SORT_DESC]);
          $dataProvider->query->all();

         if ($searchModel->priority_sector==11){
             $render='_printvawc';
         }else{
             $render='_printindex'; 
         }
          
          return $this->render($render, [
            'rowsummary' => $rowsummary,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderReport' => $dataProviderReport,
          ]);
          
        }
        
        
        return $this->render('index', [
            'rowsummary' => $rowsummary,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderReport' => $dataProviderReport,
        ]);
        
    }

    /**
     * Displays a single Database model.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$model = Database::controllerFind($id);

        return $this->render("view/{$model->viewTemplate}", [
            'model' => $model ,
        ]);
    }

    /**
     * Creates a new Database model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($priority_sector=null)
    {
        $model = new Database([
            'priority_sector' => $priority_sector
        ]);
        $model->setTheScenario();

        if ($priority_sector == null) {
            App::warning('Please select priority sector to create!');
            return $this->redirect($model->indexUrl);
        }

        if (($post = App::post()) != null) {

            $isExist = Database::find()->where(['priority_sector' => $priority_sector, 'sector_id' => $post['Database']['sector_id']])->exists();

            if ($isExist) {
                App::danger('Sector ID already exist in this priority sector.');
            }else{
                
                $post['Database']['skills'] = $post['Database']['skills'] ?? [];
                $post['Database']['reasons'] = $post['Database']['reasons'] ?? [];
                $post['Database']['client_category'] = $post['Database']['client_category'] ?? [];
                $post['Database']['interests'] = $post['Database']['interests'] ?? [];
                $post['Database']['benefit_code'] = $post['Database']['benefit_code'] ?? [];
                $post['Database']['incase_emergency'] = $post['Database']['incase_emergency'] ?? [];
                if ($model->load($post)) {
                    if ($model->save()) {
                        App::success('Successfully Created');

                        return $this->redirect($model->viewUrl);
                    }
                    else {
                        App::danger($model->errorSummary);
                    }
                }

            }

        }

		if (Yii::$app->request->isAjax) {
			return $this->renderAjax($model->formTemplate, [
                'model' => $model,
            ]);
		}

        return $this->render('create', [
            'model' => $model,
			'form' => $model->formTemplate
        ]);
    }

    /**
     * Updates an existing Database model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = Database::controllerFind($id);
        $model->setTheScenario();
		
        if (($post = App::post()) != null) {
            $post['Database']['skills'] = $post['Database']['skills'] ?? [];
            $post['Database']['reasons'] = $post['Database']['reasons'] ?? [];
            $post['Database']['client_category'] = $post['Database']['client_category'] ?? [];
            $post['Database']['interests'] = $post['Database']['interests'] ?? [];
            $post['Database']['benefit_code'] = $post['Database']['benefit_code'] ?? [];
            $post['Database']['incase_emergency'] = $post['Database']['incase_emergency'] ?? [];
            
            if ($model->load($post)) {
               if ($model->save()) {
                    App::success('Successfully Updated');
                    return $this->redirect($model->viewUrl);
                }
                else {
                    App::danger($model->errorSummary);
                }
            }
        }


        if (Yii::$app->request->isAjax) {
            return $this->renderAjax($model->formTemplate, [
                'model' => $model,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'form' =>$model->formTemplate
        ]);

    }

    public function actionReport($print=null)
    {
		$priority_sector = Database::priorityReIndex();
		
        $searchModel = new DatabaseSearch(['withBaktom' => false]);
        $dataProvider = $searchModel->searchreport(['DatabaseSearch' => App::queryParams()]);
        $searchModel->searchAction	= ['database/report'];	
		
		$from = $dataProvider->query->createCommand()->rawSql;
		
		$rowsummary = Yii::$app->db->createCommand("SELECT  
            sum(male_active) as male_active_total,
            sum(female_active) as female_active_total,
            sum(active) as active_total
            FROM ($from)sc")
            ->queryOne();

            if($print) {
    			$this->layout = "@app/views/layouts/print";
    			
    			return $this->render('report_print', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        			'rowsummary'=>$rowsummary,
        			'priority_sector'=>$priority_sector
                ]);
            }  
		   
        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'rowsummary'=>$rowsummary,
			'priority_sector'=>$priority_sector
        ]);
    }

    public function actionReportPerBarangay($print=null)
    {
		$priority_sector = Database::priorityReIndex(false);
		
        $searchModel = new DatabaseSearch();
		
        $dataProvider = $searchModel->searchreportbarangay(['DatabaseSearch' => App::queryParams()],$priority_sector);
        $searchModel->searchAction	= ['database/report-per-barangay'];	
		
		$priority = [];
        foreach($priority_sector as $key=>$row){
            array_push($priority,
                "sum(".$row['id']."_active) as ".$row['id']."_active_total",
                "sum(".$row['id']."_active_male) as ".$row['id']."_active_male_total",
                "sum(".$row['id']."_active_female) as ".$row['id']."_active_female_total",
            );
        }
		
        array_push($priority,
            "sum(active_male) as active_male_total",
            "sum(active_female) as active_female_total",
            "sum(active) as active_total",
        );

        $priority_imp = implode(", ",$priority);
		 
		$from = $dataProvider->query->createCommand()->rawSql;

        $rowsummary = Yii::$app->db->createCommand("SELECT  $priority_imp FROM ($from)sc")
           ->queryOne();
		
        if($print) {
            $this->layout = "@app/views/layouts/print";
            return $this->render('report_barangay_print', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'rowsummary' => $rowsummary,
                'priority_sector' => $priority_sector
            ]);
		}
		
        return $this->render('report_barangay', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'rowsummary'=>$rowsummary,
			'priority_sector'=>$priority_sector
        ]);
    }


    /**
     * Deletes an existing Database model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Database::controllerFind($id);

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
	
	public function actionExportXlsxReport()
    {
		$searchModel = new DatabaseSearch(['withBaktom' => false]);
        $dataProvider = $searchModel->searchreport(['DatabaseSearch' => App::queryParams()]);
		$dataProvider->pagination = false;
		
		$report_export = $this->renderPartial('report_export', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        $model = new ExportExcelForm([
            'content' => $report_export,// 'test', 
            'type' => 'xlsx',
            'ini_set' => true
        ]);
        return $model->export();
    }

    public function actionExportCsvReport()
    {
        $searchModel = new DatabaseSearch(['withBaktom' => false]);
        $dataProvider = $searchModel->searchreport(['DatabaseSearch' => App::queryParams()]);
        $dataProvider->pagination = false;
        
        $report_export = $this->renderPartial('report_export', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        $model = new ExportCsvForm([
            'content' => $report_export,// 'test', 
            'ini_set' => true
        ]);
        return $model->export();
    }


    public function actionExportCsvReportPerBarangay()
    {
        $priority_sector = Database::priorityReIndex(false);
        
        $searchModel = new DatabaseSearch(['withBaktom' => false]);
        $dataProvider = $searchModel->searchreportbarangay(['DatabaseSearch' => App::queryParams()],$priority_sector);
        $dataProvider->pagination = false;


        $priority=[];
        foreach($priority_sector as $key=>$row){
            array_push($priority,
           "sum(".$row['id']."_active) as ".$row['id']."_active_total",
           "sum(".$row['id']."_active_male) as ".$row['id']."_active_male_total",
           "sum(".$row['id']."_active_female) as ".$row['id']."_active_female_total",
          );
        }
        
        
        array_push($priority,
           "sum(active_male) as active_male_total",
           "sum(active_female) as active_female_total",
           "sum(active) as active_total",
        );
        
        $priority_imp=implode(", ",$priority);
        
        $from = $dataProvider->query->createCommand()->rawSql;

        $rowsummary = Yii::$app->db->createCommand("SELECT  $priority_imp FROM ($from)sc ")
           ->queryOne();
        
        $report_export = $this->renderPartial('report-per-barangay-export', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rowsummary'=>$rowsummary,
            'priority_sector'=>$priority_sector
        ]);

        $model = new ExportCsvForm([
            'content' => $report_export,// 'test', 
            'ini_set' => true
        ]);
        return $model->export();
    }



    public function actionExportXlsxReportPerBarangay()
    {
        $priority_sector = Database::priorityReIndex(false);
        
        $searchModel = new DatabaseSearch(['withBaktom' => false]);
        $dataProvider = $searchModel->searchreportbarangay(['DatabaseSearch' => App::queryParams()],$priority_sector);
        $dataProvider->pagination = false;


        $priority=[];
        foreach($priority_sector as $key=>$row){
            array_push($priority,
           "sum(".$row['id']."_active) as ".$row['id']."_active_total",
           "sum(".$row['id']."_active_male) as ".$row['id']."_active_male_total",
           "sum(".$row['id']."_active_female) as ".$row['id']."_active_female_total",
          );
        }
        
        
        array_push($priority,
           "sum(active_male) as active_male_total",
           "sum(active_female) as active_female_total",
           "sum(active) as active_total",
        );
        
        $priority_imp=implode(", ",$priority);
        
        $from = $dataProvider->query->createCommand()->rawSql;

        $rowsummary = Yii::$app->db->createCommand("SELECT  $priority_imp FROM ($from)sc ")
           ->queryOne();
        
        $report_export = $this->renderPartial('report-per-barangay-export', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rowsummary'=>$rowsummary,
            'priority_sector'=>$priority_sector
        ]);

        $model = new ExportExcelForm([
            'content' => $report_export,// 'test', 
            'ini_set' => true,
            'type' => 'xlsx'
        ]);
        return $model->export();
    }

    /*public function _ctionInActiveData()
    {
        # dont delete; use in condition if user has access to in-active data
    }*/

    public function actionPrioritySector()
    {
        $model = new PrioritySectorSettingsForm();
        
        return $this->render('priority-sector', [
            'model' => $model
        ]);
    }

    public function actionSavePrioritySector()
    {
        $model = new PrioritySectorForm();

        if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Added');
        }
        else {
            App::danger(Html::errorSummary($model));
        }

        return $this->redirect(App::referrer());
    }


    public function actionDeletePrioritySector($id)
    {
        $model = new PrioritySectorForm(['id' => $id]);

        if ($model->delete()) {
            App::success('Successfully Deleted');
        }
        else {
            App::danger(Html::errorSummary($model));
        }
        
        return $this->redirect(App::referrer());
    }

    public function actionInActiveData()
    {
        # dont delete; use in condition if user has access to in-active data
    }

    public function actionUnregisteredSenior()
    {
        $searchModel = new DatabaseSearch([
            'status' => 'Active',
        ]);
        $dataProvider = $searchModel->search(['DatabaseSearch' => App::queryParams()]);
        $dataProvider->query->unregisteredSenior();

        return $this->render('unregistered-senior', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExportXlsxUnregisteredSenior()
    {
        $searchModel = new DatabaseSearch([
            'status' => 'Active',
        ]);
        $dataProvider = $searchModel->search(['DatabaseSearch' => App::queryParams()]);
        $dataProvider->query->unregisteredSenior();
        $dataProvider->pagination = false;

        $model = new ExportExcelForm([
            'content' => Grid::widget([
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'columns' => $searchModel->exportColumns,
                'withActionColumn' => false,
            ]),
            'ini_set' => true,
            'type' => 'xlsx'
        ]);
        return $model->export();
    }

    public function actionCsvUnregisteredSenior()
    {
        $searchModel = new DatabaseSearch([
            'status' => 'Active',
        ]);
        $dataProvider = $searchModel->search(['DatabaseSearch' => App::queryParams()]);
        $dataProvider->query->unregisteredSenior();
        $dataProvider->pagination = false;

        $model = new ExportCsvForm([
            'content' => Grid::widget([
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'columns' => $searchModel->exportColumns,
                'withActionColumn' => false,
            ]),
            'ini_set' => true,
        ]);
        return $model->export();
    }

    public function actionImport()
    {
        $model = new DatabaseImportForm();

        if ($model->load(App::post())) {
            if ($model->validate()) {
                Queue::push(new ImportDatabaseJob([
                    'file_token' => $model->file_token,
                    'user_id' => App::identity('id')
                ]));
                App::success('The database will be imported in the queue. There will be a system notification once the importation was completed.');
                // App::success('The survey imported successfully.');
            }
            else {
                App::danger(Html::errorSummary($model));
            }

            return $this->redirect(['import']);
        }

        return $this->render('import', [
            'model' => $model,
            'prioritySector' => new PrioritySectorSettingsForm()
        ]);
    }

    public function actionValidateFile($file_token='')
    {
        $model = new DatabaseImportForm([
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
}