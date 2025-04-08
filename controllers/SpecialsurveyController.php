<?php

namespace app\controllers;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\jobs\ImportSurveyJob;
use app\models\BarangayCoordinates;
use app\models\Barangay;
use app\models\Queue;
use app\models\Specialsurvey;
use app\models\Household;
use app\models\form\SpecialsurveyImportForm;
use app\models\form\export\ExportCsvForm;
use app\models\form\export\ExportExcelForm;
use app\models\form\setting\AddressSettingForm;
use app\models\form\setting\SurveySettingForm;
use app\models\search\SpecialsurveySearch;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * SpecialsurveyController implements the CRUD actions for Specialsurvey model.
 */
class SpecialsurveyController extends Controller 
{
	public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['AccessControl'] = [
            'class' => 'app\filters\AccessControl',
            'publicActions' => ['barangay-coordinates']
        ];

        return $behaviors;
    }
    
    public function actionFindByKeywords($keywords='')
    {
        return $this->asJson(
            Specialsurvey::findByKeywords($keywords, [
            	'last_name',  
	            'first_name',  
	            'middle_name',  
	            'age',  
	            'date_of_birth',  
	            'civil_status',  
	            'house_no',  
	            'sitio',  
	            'municipality',  
	            'province',  
	            'religion',  
	            'date_survey',  
	            'remarks',  
	            'house_no',  
	            'survey_name',  
	            'CONCAT_WS(" ", `first_name`,  `last_name`)',  
                'CONCAT_WS(" ", `last_name`,  `first_name`)',  
                'CONCAT_WS(" ", `first_name`, `middle_name`, `last_name`)',  
                'CONCAT_WS(" ", `last_name`, `middle_name`, `first_name`)',  
            ])
        );
    }

    /**
     * Lists all Specialsurvey models.
     * @return mixed
     */
    public function actionIndex($criteria='')
    {
        $survey_color = Specialsurvey::surveyColorReIndex();
        
        $searchModel = new SpecialsurveySearch();

        
        $queryParams=App::queryParams();
        
        if (isset($queryParams['criteria1_color_id'])) {
            unset($queryParams['criteria1_color_id']);
            $criteria = $criteria ?: 1;
        }
        if (isset($queryParams['criteria2_color_id'])) {
            unset($queryParams['criteria2_color_id']);
            $criteria = $criteria ?: 2;
        }
        if (isset($queryParams['criteria3_color_id'])) {
            unset($queryParams['criteria3_color_id']);
            $criteria = $criteria ?: 3;
        }
        if (isset($queryParams['criteria4_color_id'])) {
            unset($queryParams['criteria4_color_id']);
            $criteria = $criteria ?: 4;
        }
        if (isset($queryParams['criteria5_color_id'])) {
            unset($queryParams['criteria5_color_id']);
            $criteria = $criteria ?: 5;
        }
        $criteria = $criteria ?: 1;
        
        $dataProvider = $searchModel->search(['SpecialsurveySearch' =>  $queryParams]);
        
        
        
    //$criteria=2;    
$dataProvider->query->select(['
t.*, 
(t.criteria'.$criteria.'_color_id) as criteria1_color_id
']);
        
        $color_survey = $queryParams['color_survey'];
        if($color_survey){
        $color_survey = explode(',', $color_survey);
        $dataProvider->query->andFilterWhere(['t.criteria'.$criteria.'_color_id' => $color_survey]);
        }


    if (Yii::$app->request->isAjax) {
        
        return $this->renderAjax('index_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'survey_color'=>$survey_color
        ]);
        
    }else{

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'survey_color'=>$survey_color
        ]);
    }
        
        
        
    }
    
     public function actionVotersList($print=null)
    {
		$survey_color = Specialsurvey::surveyColorReIndex();
		
		if(!isset($_GET['precinct_no'])  &&  (!isset($_GET['barangay']) || $_GET['barangay']=="")   ){
		$_GET['barangay']='Bagong Silang';
		}
		
		
        $searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->search(['SpecialsurveySearch' => App::queryParams()]);
        
        
        
         $dataProvider->pagination = false;
          $dataProvider->query->all();
       if($print) {
         $this->layout = "@app/views/layouts/print_voters";
        
         }

        return $this->render('voters-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'survey_color'=>$survey_color
        ]);
    }

    /**
     * Displays a single Specialsurvey model.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => Specialsurvey::controllerFind($id),
        ]);
    }

    /**
     * Creates a new Specialsurvey model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Specialsurvey();

        if ($model->load(App::post()) ) {
            
            if(!$model->encoder){
              $model->encoder= App::identity()->fullname;
            }
            
            if($model->save()){
            App::success('Successfully Created');

            return $this->redirect($model->viewUrl);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Duplicates a new Specialsurvey model.
     * If duplication is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDuplicate($id)
    {
        $originalModel = Specialsurvey::controllerFind($id);
        $model = new Specialsurvey();
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
     * Updates an existing Specialsurvey model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = Specialsurvey::controllerFind($id);

        if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Updated');
            return $this->redirect($model->viewUrl);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionImportcsv()
    {
        $model = new SpecialsurveyImportForm();

        if ($model->load(App::post())) {
            if ($model->validate()) {
                Queue::push(new ImportSurveyJob([
                    'file_token' => $model->file_token,
                    'user_id' => App::identity('id')
                ]));
                App::success('The survey data will be imported in the queue. There will be a system notification once the importation was completed.');
                // App::success('The survey imported successfully.');
            }
            else {
                App::danger(Html::errorSummary($model));
            }

            return $this->redirect(['importcsv']);
        }

        return $this->render('_form_csv', [
            'model' => $model,
        ]);


        /* if ($model->load(Yii::$app->request->post())){

        	$model->csv_file = UploadedFile::getInstances($model, 'csv_file');

        	if($model->csv_file) {
				$get_survey_color = App::setting('surveyColor')->survey_color;
				$survey_colors_id = $get_survey_color?ArrayHelper::index($get_survey_color, 'id'):[];
				$survey_colors_label = $get_survey_color?ArrayHelper::index($get_survey_color, 'label'):[];
				$fp = fopen($model->csv_file[0]->tempName, 'r');

				if($fp) {
					$line = fgetcsv($fp, 1000, ",");
					$first_time = true;

					do {
						if ($first_time == true) {
							$first_time = false;
							continue;
						}
				
						$survey_exist=Specialsurvey::findOne([
							'last_name'=>trim($line[0]),
							'first_name'=>trim($line[1]),
							'middle_name'=>trim($line[2]),  
							'date_of_birth'=>(trim($line[5])?date('Y-m-d', strtotime(trim($line[5])) ):null), 
							'date_survey'=>trim($line[19])
						]);
	
						if($survey_exist) {
							continue;
						}
						else {
							$model_c = new Specialsurvey(); 
						}
	
						$model_c->last_name=trim($line[0]);
						$model_c->first_name=trim($line[1]);
						$model_c->middle_name=trim($line[2]);
						$model_c->gender=trim($line[3]);
						$model_c->age=trim($line[4]);
						$model_c->date_of_birth=trim($line[5])?date('Y-m-d', strtotime(trim($line[5])) ):null;
						$model_c->civil_status=trim($line[6]);
						$model_c->house_no=trim($line[7]);
						$model_c->sitio=trim($line[8]);
						$model_c->purok=trim($line[9]);
						$model_c->barangay=trim($line[10]);
						$model_c->municipality=trim($line[11]);
						$model_c->province=trim($line[12]);
						$model_c->religion=trim($line[13]);
						$model_c->criteria1_color_id=is_numeric(trim($line[14]))?trim($line[14]): $survey_colors_label[ucwords(strtolower(trim($line[14])))]['id'] ;
						$model_c->criteria2_color_id=is_numeric(trim($line[15]))?trim($line[15]): $survey_colors_label[ucwords(strtolower(trim($line[15])))]['id'] ;
						$model_c->criteria3_color_id=is_numeric(trim($line[16]))?trim($line[16]): $survey_colors_label[ucwords(strtolower(trim($line[16])))]['id'] ;
						$model_c->criteria4_color_id=is_numeric(trim($line[17]))?trim($line[17]): $survey_colors_label[ucwords(strtolower(trim($line[17])))]['id'] ;
						$model_c->criteria5_color_id=is_numeric(trim($line[18]))?trim($line[18]): $survey_colors_label[ucwords(strtolower(trim($line[18])))]['id'] ;
						$model_c->date_survey=trim($line[19])?date('Y-m-d', strtotime(trim($line[19])) ):null;
						$model_c->remarks=trim($line[20]);

						$model_c->save();
					}
					while( ($line = fgetcsv($fp, 1000, ",")) != FALSE);
				}
			
				return $this->redirect(['specialsurvey/index']);
			}
		}

		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('_form_csv', [
				'model' => $model,
			]); 
        }

        return $this->render('_form_csv', [
        	'model' => $model,
        ]);*/
    }

    public function actionPopulationCoordinates($criteria = '', $brgy='', $hs='')
    {
        
        $queryParams = App::queryParams();
        
        if (isset($queryParams['criteria1_color_id'])) {
			unset($queryParams['criteria1_color_id']);
			$criteria = $criteria ?: 1;
		}
		if (isset($queryParams['criteria2_color_id'])) {
			unset($queryParams['criteria2_color_id']);
			$criteria = $criteria ?: 2;
		}
		if (isset($queryParams['criteria3_color_id'])) {
			unset($queryParams['criteria3_color_id']);
			$criteria = $criteria ?: 3;
		}
		if (isset($queryParams['criteria4_color_id'])) {
			unset($queryParams['criteria4_color_id']);
			$criteria = $criteria ?: 4;
		}
		if (isset($queryParams['criteria5_color_id'])) {
			unset($queryParams['criteria5_color_id']);
			$criteria = $criteria ?: 5;
		}
		$criteria = $criteria ?: 1;
        
        
        
        if($hs){
            $household= Household::find()->where(['no'=>$hs])->one();
            $voters = Specialsurvey::find()->alias('t')
            ->select(['t.*, (t.criteria'.$criteria.'_color_id) as criteria1_color_id'])
            ->where(['household_no'=>$hs])
            ->andFilterWhere(['t.survey_name'=>$queryParams['survey_name']])
            ->all();
              $survey_color = Specialsurvey::surveyColorReIndex();
                   
            $output=null;
            if($voters){
                
           $output.='
               <div>
               
               
               
               
                Total Number of Assistance: '.$household->totalTransactions.'
                <br/>Total Amount: '.$household->totalAmountTransactions.'
                <br/>Social Pension: '.$household->social_pension.'
                <br/>
               </div>
               <table class="table table-striped">
                 <tr>
                 <th></th>
                 <th>VOTER\'S</th>
                 <th>SEX / BIRTHDATE</th>
                 </tr>
                ';
                
                foreach($voters as $key=>$row){
                    $output .='<tr>';
                    $output .= '<td><span class="badge badge-pill" style="background-color: '.$survey_color[$row->criteria1_color_id]['color'].'; height: 20px; width: 20px; padding:0px!Important;">&nbsp; &nbsp; </span></td>';
                    $output .= '<td>'.$row->last_name.', '.$row->first_name.'</td>';
                    $output .= '<td>'.$row->gender.' / '.$row->date_of_birth.'</td>';
                    $output .='</tr>';
                }
                
                $output .='</table>';
            }

           return $output;
           
        }
        
        if($brgy==1){
         $mdata = Barangay::find()->all();
        
         foreach($mdata as $key=>$row){
		
		 $features[]= ["id"=> $row['id'],
		                 "type"=>"Feature",
                         "properties"=> $row,   
                         "geometry"=> [
                              "type"=> "Point",
                              "coordinates"=> [$row['longitude'],$row['latitude']]
                          ]    
                    ];
                                  
         }
         
         
          $brgy = [
			"type" => "FeatureCollection",
			"features" => $features,
			//"output" => $output,
	  	];
         return $this->asJsonNumeric($brgy);
        
        
        }
        
        
        
        
        
        
        
        
        
        $searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->searchvoters(['SpecialsurveySearch' => $queryParams]);
        
         $dataProvider->query->select(['t.first_name','t.middle_name', 't.last_name', 't.household_no',
        '(t.criteria'.$criteria.'_color_id) as criteria1_color_id',
        'count(t.id) as total_voters', 'hs.longitude','hs.latitude'
        ]);
        
        $color_survey = $queryParams['color_survey'];
        if($color_survey){
        $color_survey = explode(',', $color_survey);
        $dataProvider->query->andFilterWhere(['t.criteria'.$criteria.'_color_id' => $color_survey]);
        }
        
        
	//	$mdata = $dataProvider->getModels();
	    $mdata = $dataProvider->query->all();
		
		//print_r($mdata);
		$survey_color = App::setting('surveyColor')->survey_color;
		$survey_color = App::mapParams($survey_color, $key='id', $value='label');

		foreach($mdata as $key=>$row){
		    
		  //$voters = Specialsurvey::find()->where(['household_no'=>$row['household_no']])->asArray()->all();
		  // $row['voters'] = $voters;
		  
		 $row['color_label'] = $survey_color[$row['criteria1_color_id']];
		
		 $features[]= ["id"=> $row['id'],
		                 "type"=>"Feature",
                         "properties"=> $row,
                         "geometry"=> [
                              "type"=> "Point",
                              "coordinates"=> [$row['longitude'],$row['latitude']]
                          ]    
                    ];
                                  
         }
         
         
           /*
         	foreach (App::setting('surveyColor')->survey_color as $key => $sc) {
	 			$household_colors[] = [
	 				'label' => $sc['label'], 
					'total' => Html::number($array_total[$key]), 
					'color' => $sc['color'], 
	 			];
	 		}
	 		*/
    
        // print_r($household_colors);
        // exit;
       
       
      $population = [
			"type" => "FeatureCollection",
			"features" => $features,
			//"output" => $output,
		];
      return $this->asJsonNumeric($population);
        
    }



    public function actionBarangayCoordinates($criteria = '')
    {
    	error_reporting(E_ERROR);
		$survey_color = Specialsurvey::surveyColorReIndex();

		$queryParams = App::queryParams();

		if (isset($queryParams['criteria1_color_id'])) {
			unset($queryParams['criteria1_color_id']);
			$criteria = $criteria ?: 1;
		}
		if (isset($queryParams['criteria2_color_id'])) {
			unset($queryParams['criteria2_color_id']);
			$criteria = $criteria ?: 2;
		}
		if (isset($queryParams['criteria3_color_id'])) {
			unset($queryParams['criteria3_color_id']);
			$criteria = $criteria ?: 3;
		}
		if (isset($queryParams['criteria4_color_id'])) {
			unset($queryParams['criteria4_color_id']);
			$criteria = $criteria ?: 4;
		}
		if (isset($queryParams['criteria5_color_id'])) {
			unset($queryParams['criteria5_color_id']);
			$criteria = $criteria ?: 5;
		}
		$criteria = $criteria ?: 1;
		
		
		if($queryParams['bgygraph']==1){
		  return $this->renderAjax('voter_barangay_graph', [
				'queryParams' => $queryParams,
				'criteria'=>$criteria
			]);
		}
		
		
        $searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => $queryParams]);
        
        $color_survey = $queryParams['color_survey'];
        if($color_survey){
        $color_survey = explode(',', $color_survey);
        $dataProvider->query->andFilterWhere(['t.criteria'.$criteria.'_color_id' => $color_survey]);
        }

        
		$mdata = $dataProvider->getModels();
		$barangay_data = ArrayHelper::index($mdata, 'barangay');
		$address = App::setting('address'); 
		
		$coordinates = BarangayCoordinates::find()
			->select([
				"country",
				"province",
				"municipality",
				'barangay',
				"coordinates",
				"color"
			])
			->where([
				'municipality' => $address->municipalityName, 
				'province' => $address->provinceName,
			])
			->andFilterWhere([
				'barangay' => $searchModel->barangay
			])
			->asArray()
			->all();

		foreach ($coordinates as $key=>$row ) {

			$coordinates = json_decode($row['coordinates'],true);
			$total_black = $barangay_data[$row['barangay']]["criteria{$criteria}_color_black"] ?? 0;
			$total_gray = $barangay_data[$row['barangay']]["criteria{$criteria}_color_gray"] ?? 0;
			$total_green = $barangay_data[$row['barangay']]["criteria{$criteria}_color_green"] ?? 0;
			$total_red = $barangay_data[$row['barangay']]["criteria{$criteria}_color_red"] ?? 0;
	 		
	 		$color_dom = $searchModel->getDominantColor(
	 			$total_black, $total_gray, $total_green, $total_red
	 		);
	 		
	 		$row['color'] =$color_dom['color'];

	 		$household_colors = [];
	 		$array_total = [$total_black, $total_gray, $total_green, $total_red];

	 		foreach (App::setting('surveyColor')->survey_color as $key => $sc) {
	 			$household_colors[] = [
	 				'label' => $sc['label'], 
					'total' => Html::number($array_total[$key]), 
					'color' => $sc['color'], 
	 			];
	 		}
	 		
	 		if(	$row['barangay']=="Poblacion 61 (Barangay 2)"){
	 		     $row['barangay']='Poblacion 61';
	 		}elseif($row['barangay']=="Poblacion I (Barangay 1)"){
	 		    $row['barangay']='Poblacion 1';
	 		}

			$features[] = [
				"type" => "Feature",
				"properties" => [
					"barangay" => $row['barangay'],
					"color" => $row['color'],
					"percentage"=>$color_dom['percent'],
					"rank" => "7",
					"ascii" => $row['id'] ?? 0,// "71",
					"household" => Html::number(array_sum($array_total)),
					"household_colors" => $household_colors,
					"url_link" => Url::to([
						'specialsurvey/report-per-purok', 
						'barangay' => $row['barangay'],
						'groupPurok' => true
					],true),
				],
				"geometry" => [
					"type" => "Polygon",
					// "coordinates" => [$coordinates],
				]
			];
		}

		$data = [];
		foreach ($features as $feature) {
			$prop = $feature['properties'];
			$data[$prop['color']][] = $feature['properties']['barangay'];
			// $data[$feature['properties']['barangay']] = $feature['properties']['color'];
		}

		$output = [];
		$output[] = "match";
	    $output[] = ["get", "barangay"];
		foreach ($data as $color => $barangays) {
		    $output[] = $barangays;
		    $output[] = $color;
		}
		$output[] = "#E4E6EF"; //default
		
		if($queryParams['graph']==1){
		  return $this->renderAjax('_graph', [
				'features' => $features,
				'queryParams' => $queryParams,
			]);
		}

          
     
        $purok=[];
        if($searchModel->barangay){
            $purok = Specialsurvey::find()->select(['purok'])->andWhere("purok is not null and purok not in('','-','0') ")->andFilterWhere(['barangay'=>$searchModel->barangay])->groupBy("purok")->orderby(['purok'=>SORT_ASC])->asArray()->all();
        }



		return $this->asJsonNumeric([
			"type" => "FeatureCollection",
			"features" => $features,
			"output" => $output,
			"queryParams" => $queryParams,
			"purok"=>$purok,
			'preview' => $this->renderPartial('_features', [
				'features' => $features
			])
		]);
    }


	public function actionReportPerBarangay($print=null)
    {
        $searchModel = new SpecialsurveySearch();
		
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => App::queryParams()]);
        $searchModel->searchAction	= ['specialsurvey/report-per-barangay'];	
		
        $rowsummary = $searchModel->getRowSummary($dataProvider);

        if($print) {
			$this->layout = "@app/views/layouts/print";
			return $this->render('report_barangay_print', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
				'rowsummary' => $rowsummary,
	        ]);
		}
		
        return $this->render('report_barangay', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'rowsummary' => $rowsummary,
        ]);
    }
    
    
    
    public function actionVoterAnalysis($criteria=null)
    {
    	$survey_color = Specialsurvey::surveyColorReIndex();
		
        $searchModel = new SpecialsurveySearch();

        
        $queryParams=App::queryParams();
        
         if (isset($queryParams['criteria1_color_id'])) {
			unset($queryParams['criteria1_color_id']);
			$criteria = $criteria ?: 1;
		}
		if (isset($queryParams['criteria2_color_id'])) {
			unset($queryParams['criteria2_color_id']);
			$criteria = $criteria ?: 2;
		}
		if (isset($queryParams['criteria3_color_id'])) {
			unset($queryParams['criteria3_color_id']);
			$criteria = $criteria ?: 3;
		}
		if (isset($queryParams['criteria4_color_id'])) {
			unset($queryParams['criteria4_color_id']);
			$criteria = $criteria ?: 4;
		}
		if (isset($queryParams['criteria5_color_id'])) {
			unset($queryParams['criteria5_color_id']);
			$criteria = $criteria ?: 5;
		}
		$criteria = $criteria ?: 1;
        
        $dataProvider = $searchModel->search(['SpecialsurveySearch' =>  $queryParams]);
        
        
        
    $criteria=2;    
   $dataProvider->query->select(['
        t.*, 
        (t.criteria'.$criteria.'_color_id) as criteria1_color_id
        ']);
        
        $color_survey = $queryParams['color_survey'];
        if($color_survey){
        $color_survey = explode(',', $color_survey);
        $dataProvider->query->andFilterWhere(['t.criteria'.$criteria.'_color_id' => $color_survey]);
        }


       if (Yii::$app->request->isAjax) {
           
        return $this->renderAjax('index_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'survey_color'=>$survey_color
        ]);
           
       }else{

        return $this->render('voter_analysis', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'survey_color'=>$survey_color
        ]);
       }
        
		
        
    }
    

    /////////////////////////   lEO  /////////////////////////// 

    public function actionGrayBarangayCoordinates()
    {
        error_reporting(E_ERROR);
        $survey_color = Specialsurvey::surveyColorReIndex();
        $queryParams = App::queryParams();
        
        $color_survey = 2; // Force only criteria 2 (Gray)
        $criteria = $queryParams['criteria'] ?? 1; // Ensure criteria is properly assigned
    
        $searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => $queryParams]);
    
        if (!empty($queryParams['bgygraph']) && $queryParams['bgygraph'] == 1) {
            return $this->renderAjax('voter_gray_barangay_graph', [
                'queryParams' => $queryParams,
                'criteria' => $criteria,
            ]);
        }
    
        try {
            $criteriaColumn = 'criteria' . (int) $criteria . '_color_id';
            
            // Ensure the column exists before applying the filter
            if (isset($dataProvider->query->modelClass::getTableSchema()->columns[$criteriaColumn])) {
                $dataProvider->query->andFilterWhere([$criteriaColumn => $color_survey]);
            }
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), 'application');
            return $this->asJson(['error' => $e->getMessage()]);
        }
    
        $mdata = $dataProvider->getModels();
        $barangay_data = ArrayHelper::index($mdata, 'barangay');
        $address = App::setting('address');
    
        // Fetch Barangay Coordinates
        $coordinates = BarangayCoordinates::find()
            ->select(["country", "province", "municipality", "barangay", "coordinates", "color"])
            ->where(['municipality' => $address->municipalityName, 'province' => $address->provinceName])
            ->asArray()
            ->all();
    
        $features = [];
        foreach ($coordinates as $row) {
            $coords = json_decode($row['coordinates'], true) ?: []; // Ensure it is an array
    
            $colorKey = "criteria" . (int) $criteria . "_color_gray"; // Dynamic key
            $total_gray = $barangay_data[$row['barangay']][$colorKey] ?? 0;
    
            // Force the color to Gray
            $row['color'] = "#e4e6ef";
    
            $household_colors = [
                [
                    'label' => 'Gray',
                    'total' => Yii::$app->formatter->asInteger($total_gray), // Format numbers properly
                    'color' => '#808080',
                ]
            ];
    
            $features[] = [
                "type" => "Feature",
                "properties" => [
                    "barangay" => $row['barangay'],
                    "color" => "#e4e6ef",
                    "percentage" => 100,
                    "household" => Yii::$app->formatter->asInteger($total_gray),
                    "household_colors" => $household_colors,
                    "url_link" => Url::to(['specialsurvey/report-per-purok', 'barangay' => $row['barangay'], 'groupPurok' => true], true),
                ],
                "geometry" => [
                    "type" => "Polygon",
                    "coordinates" => $coords,
                ]
            ];
        }
    
        // Prepare data output
        $data = [];
        foreach ($features as $feature) {
            $data["#808080"][] = $feature['properties']['barangay'];
        }
    
        $output = ["match", ["get", "barangay"]];
        foreach ($data as $color => $barangays) {
            $output[] = $barangays;
            $output[] = $color;
        }
        $output[] = "#e4e6ef";
    
        if (!empty($queryParams['graph']) && !empty($queryParams['grey']) && $queryParams['graph'] == 1 && $queryParams['grey'] == 1) {
            return $this->renderAjax('_grey_graph', [
                'features' => $features,
                'queryParams' => $queryParams,
            ]);
        }
    
        return $this->asJson([
            "type" => "FeatureCollection",
            "features" => $features,
            "output" => $output,
            "queryParams" => $queryParams,
        ]);
    }
    


    public function actionVoterDistribution($criteria = null)
    {
        $survey_color = Specialsurvey::surveyColorReIndex();
        $searchModel = new SpecialsurveySearch();
        $queryParams = App::queryParams();
    
        // Ensure criteria selection (Default to criteria2)
        $criteria = $criteria ?: 1;

    
        $dataProvider = $searchModel->search(['SpecialsurveySearch' => $queryParams]);
    
        // Select only necessary columns & apply grey color filter
        $dataProvider->query->select([
            't.*',
            "(t.criteria{$criteria}_color_id) as criteria1_color_id"
        ])->andWhere(['t.criteria' . $criteria . '_color_id' => 2]); // 2 = Grey Color
    
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index_list', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'survey_color' => $survey_color
            ]);
        } else {
            return $this->render('voter_distribution', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'survey_color' => $survey_color
            ]);
        }
    }


    
    public function actionConversionRateAnalysis($criteria = 1, $color_survey = 1) {
        $searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        // Get surveys and their creation dates
        $surveys = Specialsurvey::find()
            ->select(['survey_name', 'MIN(created_at) as created_at'])
            ->groupBy(['survey_name'])
            ->orderBy(['survey_name' => SORT_ASC])
            ->asArray()
            ->all();
    
        $colorMapping = [
            1 => '#5096f2', // Blue
            2 => '#e4e6ef', // Gray
            3 => '#000000', // Blackx
            4 => '#404040', // Blacky
            5 => '#808080'  // Blacku
        ];
    
        $surveyLabels = [];
        $periods = [];
        foreach ($surveys as $survey) {
            $surveyLabels[] = $survey['survey_name'];
            $periods[] = date('M d, Y', strtotime($survey['created_at']));
        }
    
        // Prepare data for color charts
        $data = [];
        $grayVoterData = [];
    
        // Collect data for each color (Black, Gray, Green, Red)
        foreach ($colorMapping as $criteriaId => $color) {
            $counts = Specialsurvey::find()
                ->select(['survey_name', 'COUNT(*) as voter_count'])
                ->where(['criteria' . $criteria . '_color_id' => $criteriaId])
                ->groupBy(['survey_name'])
                ->indexBy('survey_name')
                ->asArray()
                ->all();
    
            $seriesData = [];
            foreach ($surveyLabels as $surveyName) {
                $voterCount = $counts[$surveyName]['voter_count'] ?? 0;
                $seriesData[] = $voterCount;
    
                if ($criteriaId == 2) {
                    $grayVoterData[] = $voterCount;
                }
            }
    
            $colorNames = [
                1 => 'Blue voters',
                2 => 'Gray voters',
                3 => 'BlackX voters',
                4 => 'BlackY voters',
                5 => 'BlackU voters'
            ];
    
            // Add color data to display in chart
            $data[] = [
                'id' => $criteriaId,
                'name' => $colorNames[$criteriaId] ?? "Voters (Color ID: $criteriaId)",
                'data' => $seriesData,
                'color' => $color
            ];
        }
    
        // Collect voter history and determine converted voters
        $surveyData = Specialsurvey::find()
            ->select(['id', 'household_no', 'survey_name', 'criteria' . $criteria . '_color_id', 'last_name', 'first_name', 'middle_name', 'barangay'])
            ->orderBy(['survey_name' => SORT_ASC])
            ->all();
    
        $voterHistory = [];
        foreach ($surveyData as $survey) {
            $voterKey = $survey->household_no . '_' . $survey->last_name . '_' . $survey->first_name . '_' . $survey->middle_name;
            if (!isset($voterHistory[$voterKey])) {
                $voterHistory[$voterKey] = [];
            }
            $criteriaString = 'criteria' . $criteria . '_color_id';
            $voterHistory[$voterKey][$survey->survey_name] = $survey->$criteriaString;
        }
    
        // Identifying converted voters based on the color_survey
        $convertedVoters = [];
        foreach ($voterHistory as $voterKey => $surveys) {
            $lastColor = null;
            $lastSurvey = null;
            $wasGray = false;
    
            foreach ($surveys as $surveyName => $colorId) {
                if ($colorId == 2) { // Gray
                    $wasGray = true;
                } else if ($wasGray && $colorId == $color_survey) { // Matching conversion to target color
                    $lastColor = $colorId;
                    $lastSurvey = $surveyName;
                    break;
                }
            }
    
            if ($wasGray && $lastColor == $color_survey) {
                list($householdNo, $lastName, $firstName, $middleName) = explode('_', $voterKey);
                $voterData = Specialsurvey::find()
                    ->select(['id', 'survey_name', 'last_name', 'first_name', 'middle_name', 'household_no', 'barangay'])
                    ->where([
                        'household_no' => $householdNo,
                        'last_name' => $lastName,
                        'first_name' => $firstName,
                        'middle_name' => $middleName,
                        'survey_name' => $lastSurvey
                    ])
                    ->one();
    
                if ($voterData) {
                    $convertedVoters[] = [
                        'voter_id' => $voterData->id,
                        'survey_name' => $voterData->survey_name,
                    ];
                }
            }
        }
    
        // Calculate conversion per survey
        $conversionPerSurvey = [];
        foreach ($convertedVoters as $convertedVoter) {
            $surveyName = $convertedVoter['survey_name'];
            if (!isset($conversionPerSurvey[$surveyName])) {
                $conversionPerSurvey[$surveyName] = 0;
            }
            $conversionPerSurvey[$surveyName]++;
        }
    
        // Calculate the total number of voters for the given color_survey
        $totalVoters = Specialsurvey::find()
            ->select(['COUNT(*) as total_voters'])
            ->where(['criteria' . $criteria . '_color_id' => $color_survey])
            ->scalar();
    
        // Calculate the percentage of converted voters
        $convertedCount = count($convertedVoters);
        $conversionPercentage = ($totalVoters > 0) ? round(($convertedCount / $totalVoters) * 100, 2) : 0;

        // Calculate the total number of records for each survey
        $surveyRecordCounts = [];
        foreach ($surveyLabels as $surveyName) {
            $surveyRecordCounts[$surveyName] = Specialsurvey::find()
                ->where(['survey_name' => $surveyName])
                ->count();
        }

        // Filter the dataProvider based on converted voters
        if (empty($convertedVoters)) {
            $dataProvider->query->andWhere(['t.id' => null]);
        } else {
            $voterIds = array_column($convertedVoters, 'voter_id');
            $dataProvider->query->andWhere(['t.id' => $voterIds]);
        }
    
        // Pass the conversion data per survey to the view
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index_list', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'convertedCount' => $convertedCount,
                'conversionPercentage' => $conversionPercentage,
                'conversionPerSurvey' => $conversionPerSurvey, // Pass the conversion data for each survey
                'totalVoters' => $totalVoters, // Pass the total number of voters for percentage calculation
                'surveyLabels' => $surveyLabels,
                'surveyRecordCounts' => $surveyRecordCounts, // Pass survey record counts to the view

                'cra' => true,
            ]);
        }
    
        return $this->render('conversion_rate_analysis', [
            'labels' => json_encode(array_map(function($survey, $period) {
                return $survey . " (" . $period . ")";
            }, $surveyLabels, $periods)),
            'periods' => json_encode($periods),
            'colorData' => json_encode($data),  // Ensure data for chart is passed
            'grayData' => json_encode($grayVoterData),
            'convertedCount' => $convertedCount,
            'conversionPercentage' => $conversionPercentage,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'conversionPerSurvey' => $conversionPerSurvey, // Pass the conversion data for each survey
            'surveyRecordCounts' => $surveyRecordCounts, // Pass survey record counts to the view
            'totalVoters' => $totalVoters, // Pass the total number of voters for percentage calculation
        ]);
    }
    
    
    
    public function actionPopulationCoordinates1($criteria = '', $brgy = '', $hs = '')
    {
        $queryParams = App::queryParams();
    
        // Determine the criteria based on the incoming query params
        $criteria = $criteria ?: 1; // Default to criteria 1
    
        if ($hs) {
            // If household data is requested, return household voter details
            $householdData = Household::find()->where(['id' => $hs])->one();
            return $this->asJsonNumeric($householdData);
        }
    
        // Handle barangay data
        if ($brgy == 1) {
            $mdata = Barangay::find()->all();
            $features = [];
    
            foreach ($mdata as $row) {
                $features[] = [
                    "id" => $row['id'],
                    "type" => "Feature",
                    "properties" => $row,
                    "geometry" => [
                        "type" => "Point",
                        "coordinates" => [$row['longitude'], $row['latitude']],
                    ]
                ];
            }
    
            return $this->asJsonNumeric([
                "type" => "FeatureCollection",
                "features" => $features,
            ]);
        }
    
        // Handle voter data with color filtering
        $searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->searchvoters(['SpecialsurveySearch' => $queryParams]);
        $dataProvider->query->select([
            't.first_name', 't.middle_name', 't.last_name', 't.household_no',
            '(t.criteria' . $criteria . '_color_id) as criteria_color_id',
            'count(t.id) as total_voters', 'hs.longitude', 'hs.latitude',
            'hs.barangay_id'
        ])->innerJoin('household hs', 't.household_no = hs.household_no');
    
        $color_survey = $queryParams['color_survey'];

        if ($color_survey) {
            $color_survey = explode(',', $color_survey);
            $dataProvider->query->andFilterWhere(['t.criteria'.$criteria.'_color_id' => $color_survey]);
        }

        $mdata = $dataProvider->query->all();

        $survey_color = App::setting('surveyColor')->survey_color;
        $survey_color = App::mapParams($survey_color, $key = 'id', $value = 'label');
    
        $barangayData = [];
        foreach ($mdata as $row) {
            $barangayId = $row['barangay_id'];
            if (!isset($barangayData[$barangayId])) {
                $barangayData[$barangayId] = [
                    'black' => 0, 'gray' => 0, 'green' => 0, 'red' => 0,
                    'households' => []
                ];
            }
    
            // Count voters based on their color
            $barangayData[$barangayId]['black'] += ($row['criteria_color_id'] == 'black') ? $row['total_voters'] : 0;
            $barangayData[$barangayId]['gray'] += ($row['criteria_color_id'] == 'gray') ? $row['total_voters'] : 0;
            $barangayData[$barangayId]['green'] += ($row['criteria_color_id'] == 'green') ? $row['total_voters'] : 0;
            $barangayData[$barangayId]['red'] += ($row['criteria_color_id'] == 'red') ? $row['total_voters'] : 0;
    
            // Store household data
            $barangayData[$barangayId]['households'][] = [
                "id" => $row['household_no'],
                "type" => "Feature",
                "properties" => [
                    "household_no" => $row['household_no'],
                    "color_label" => $survey_color[$row['criteria_color_id']] ?? 'Unknown',
                ],
                "geometry" => [
                    "type" => "Point",
                    "coordinates" => [$row['longitude'], $row['latitude']],
                ]
            ];
        }
    
        // Determine dominant color for each barangay and filter the results
        $dominantBarangays = [];
        foreach ($barangayData as $barangayId => $data) {
            $dominantColor = array_search(max($data), $data); // Find the most dominant color
    
            // Only include barangays matching the selected color filter
            if (in_array($dominantColor, $color_survey)) {
                $barangay = Barangay::findOne($barangayId);
                if ($barangay) {
                    $dominantBarangays[] = [
                        "id" => $barangay->id,
                        "type" => "Feature",
                        "properties" => [
                            "barangay_name" => $barangay->name,
                            "dominant_color" => $dominantColor
                        ],
                        "geometry" => [
                            "type" => "Point",
                            "coordinates" => [$barangay->longitude, $barangay->latitude],
                        ],
                        "households" => $data['households']
                    ];
                }
            }
        }
    
        return $this->asJsonNumeric([
            "type" => "FeatureCollection",
            "features" => $dominantBarangays,
        ]);
    }
    
    
    // FOR CAMPAIGN INTELLIGENCE DASHBOARD
    public function actionBarangayCoordinates1($criteria = '')
    {
        error_reporting(E_ERROR);
        $survey_color = Specialsurvey::surveyColorReIndex();
    
        $queryParams = App::queryParams();
    
        if (isset($queryParams['criteria1_color_id'])) {
            unset($queryParams['criteria1_color_id']);
            $criteria = $criteria ?: 1;
        }
        if (isset($queryParams['criteria2_color_id'])) {
            unset($queryParams['criteria2_color_id']);
            $criteria = $criteria ?: 2;
        }
        if (isset($queryParams['criteria3_color_id'])) {
            unset($queryParams['criteria3_color_id']);
            $criteria = $criteria ?: 3;
        }
        if (isset($queryParams['criteria4_color_id'])) {
            unset($queryParams['criteria4_color_id']);
            $criteria = $criteria ?: 4;
        }
        if (isset($queryParams['criteria5_color_id'])) {
            unset($queryParams['criteria5_color_id']);
            $criteria = $criteria ?: 5;
        }
        $criteria = $criteria ?: 1;
    
        if ($queryParams['bgygraph'] == 1) {
            return $this->renderAjax('voter_barangay_graph', [
                'queryParams' => $queryParams,
                'criteria' => $criteria
            ]);
        }
    
        $searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => $queryParams]);
    
        $color_survey = $queryParams['color_survey'];
        if ($color_survey) {
            $color_survey = explode(',', $color_survey);
        }
    
        $mdata = $dataProvider->getModels();
        $barangay_data = ArrayHelper::index($mdata, 'barangay');
        $address = App::setting('address');
    
        $coordinates = BarangayCoordinates::find()
            ->select([
                "country",
                "province",
                "municipality",
                'barangay',
                "coordinates",
                "color"
            ])
            ->where([
                'municipality' => $address->municipalityName,
                'province' => $address->provinceName,
            ])
            ->andFilterWhere([
                'barangay' => $searchModel->barangay
            ])
            ->asArray()
            ->all();
    
        $features = [];
        $color_map = [
            1 => "#5096f2", // Blue
            2 => "#e4e6ef", // Gray
            3 => "#000000", // Blackx
            4 => "#404040", // Blacky
            5 => "#808080"  // Blacku
        ];

      
    
        foreach ($coordinates as $row) {
            $coordinates = json_decode($row['coordinates'], true);
            $total_black = $barangay_data[$row['barangay']]["criteria{$criteria}_color_black"] ?? 0;
            $total_gray = $barangay_data[$row['barangay']]["criteria{$criteria}_color_gray"] ?? 0;
            $total_green = $barangay_data[$row['barangay']]["criteria{$criteria}_color_green"] ?? 0;
            $total_red = $barangay_data[$row['barangay']]["criteria{$criteria}_color_red"] ?? 0;

            $barangay_colors = [
                1 => $total_black,
                2 => $total_gray,
                3 => $total_green,
                4 => $total_red
            ];

            arsort($barangay_colors);
            $dominant_color_id = key($barangay_colors);
            $dominant_color = $color_map[$dominant_color_id] ?? "#E4E6EF";

            // Calculate dominance percentage
            $total_colors = array_sum($barangay_colors);
            $dominance_percentage = $total_colors > 0 ? round(($barangay_colors[$dominant_color_id] / $total_colors) * 100, 2) : 0;

            // Ensure correct filtering logic
            if (!empty($color_survey) && !in_array($dominant_color_id, $color_survey)) {
                $dominant_color = "#ffffff"; // Default color for excluded barangays
            }

            $household_colors = [];
            foreach (App::setting('surveyColor')->survey_color as $key => $sc) {
            $household_colors[] = [
                'label' => $sc['label'],
                'total' => Html::number($barangay_colors[$key + 1] ?? 0),
                'color' => $sc['color'],
            ];
            }

            if ($row['barangay'] == "Poblacion 61 (Barangay 2)") {
            $row['barangay'] = 'Poblacion 61';
            } elseif ($row['barangay'] == "Poblacion I (Barangay 1)") {
            $row['barangay'] = 'Poblacion 1';
            }

            $features[] = [
            "type" => "Feature",
            "properties" => [
                "barangay" => $row['barangay'],
                "color" => $dominant_color,
                "percentage" => $dominance_percentage,
                "household" => Html::number(array_sum($barangay_colors)),
                "household_colors" => $household_colors,
                "url_link" => Url::to([
                'specialsurvey/report-per-purok',
                'barangay' => $row['barangay'],
                'groupPurok' => true
                ], true),
            ],
            "geometry" => [
                "type" => "Polygon",
            ]
            ];
        }
    
        $data = [];
        foreach ($features as $feature) {
            $prop = $feature['properties'];
            $data[$prop['color']][] = $prop['barangay'];
        }
    
        $output = ["match", ["get", "barangay"]];
        foreach ($data as $color => $barangays) {
            $output[] = $barangays;
            $output[] = $color;
        }

        
        $output[] = "#808080";
    
        if ($queryParams['graph'] == 1) {
            return $this->renderAjax('_graph', [
                'features' => $features,
                'queryParams' => $queryParams,
            ]);
        }
    
        $purok = [];
        if ($searchModel->barangay) {
            $purok = Specialsurvey::find()->select(['purok'])->andWhere("purok is not null and purok not in('','-','0') ")
                ->andFilterWhere(['barangay' => $searchModel->barangay])->groupBy("purok")->orderby(['purok' => SORT_ASC])
                ->asArray()->all();
        }
    
        return $this->asJsonNumeric([
            "type" => "FeatureCollection",
            "features" => $features,
            "output" => $output,
            "queryParams" => $queryParams,
            "purok" => $purok,
            'preview' => $this->renderPartial('_features', [
                'features' => $features
            ])
        ]);
    }
   
    public function actionPopulationCoordinates2($criteria = '', $brgy = '', $hs = '')
    {
        $queryParams = App::queryParams();
        $criteria = $criteria ?: 1;
        
        
    
        // If brgy=1, just return all barangays for map label
        if ($brgy == 1) {
            $mdata = Barangay::find()->all();
            foreach ($mdata as $row) {
                $features[] = [
                    "id" => $row['id'],
                    "type" => "Feature",
                    "properties" => $row,
                    "geometry" => [
                        "type" => "Point",
                        "coordinates" => [$row['longitude'], $row['latitude']]
                    ]
                ];
            }
    
            return $this->asJsonNumeric([
                "type" => "FeatureCollection",
                "features" => $features,
            ]);
        }
    
        // ================== Determine Barangays with Matching Dominant Color ==================
        $barangays = Barangay::find()->all();
        // After getting the color counts
        $validBarangays = [];
        foreach ($barangays as $barangay) {
            $query = Specialsurvey::find()
                ->select(['criteria' . $criteria . '_color_id', 'COUNT(*) as total'])
                ->where(['barangay' => $barangay->name]);

            if (!empty($queryParams['survey_name'])) {
                $query->andWhere(['survey_name' => $queryParams['survey_name']]);
            }

            $colorCounts = $query
                ->groupBy('criteria' . $criteria . '_color_id')
                ->indexBy('criteria' . $criteria . '_color_id')
                ->asArray()
                ->all();

           
            // Find the dominant color
            $dominantColor = null;
            $maxCount = 0;
            foreach ($colorCounts as $color => $data) {
                if ($data['total'] > $maxCount) {
                    $dominantColor = $color;
                    $maxCount = $data['total'];
                }
            }

            $color_survey = $queryParams['color_survey'];
            if ($color_survey) {
                $color_survey = explode(',', $color_survey);
                
            } else {
                $color_survey = [1,2,3,4,5];

            }
            

            // Check if dominant color is in the requested color_survey list
            if ($dominantColor && in_array($dominantColor, $color_survey)) {

                if(	$barangay->name=="Poblacion 61 (Barangay 2)"){
                    $validBarangays[] ='Poblacion 61';
                }elseif($barangay->name=="Poblacion I (Barangay 1)"){
                    $validBarangays[] ='Poblacion 1';
                }
                else{
                    $validBarangays[] = $barangay->name; // Add to valid barangays

                }

            }
        }

        // Ensure there's valid data before proceeding
        if (empty($validBarangays)) {
            return $this->asJsonNumeric([
                "type" => "FeatureCollection",
                "features" => [],
               
            ]);
        }
    
        // Proceed with the data fetching if valid barangays exist
        $searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->searchvoters(['SpecialsurveySearch' => $queryParams]);
        // $dataProvider->query->andWhere(['t.barangay' => $validBarangays]); //Filter only the valid Barangays
    
        $dataProvider->query->select([
            't.id', 't.first_name', 't.middle_name', 't.last_name', 't.household_no', 't.leader',
            '(t.criteria' . $criteria . '_color_id) as criteria1_color_id',
            'count(t.id) as total_voters', 'hs.longitude', 'hs.latitude', 't.barangay'
        ]);
    
        $dataProvider->query->andFilterWhere(['t.criteria'.$criteria.'_color_id' => $color_survey]);

        // $dataProvider->query->andWhere(['t.barangay' => $validBarangays]);
    
        $mdata = $dataProvider->query->all();
        $survey_color = App::setting('surveyColor')->survey_color;
        $survey_color = App::mapParams($survey_color, 'id', 'label');
    
        $features = [];
        foreach ($mdata as $row) {
            $row['color_label'] = $survey_color[$row['criteria1_color_id']];
            
            $features[] = [
                "id" => $row['id'],
                "type" => "Feature",
                "properties" => $row,
                "geometry" => [
                    "type" => "Point",
                    "coordinates" => [$row['longitude'], $row['latitude']]
                ]
            ];
        }
    
        return $this->asJsonNumeric([
            "type" => "FeatureCollection",
            "features" => $features,
        ]);
    }
    //////////////////////////////////////////////
    

    public function actionVoterSegmentationByAgeAndGenders($barangay = null, $purok = null, $criteria = null, $color = null) {
        $searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => App::queryParams()]);

        $criteria = $criteria ?? 1;

        $ageSegmentationData = Specialsurvey::find()
            ->select([
                'age_range' => new \yii\db\Expression("CASE
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 60 THEN '60+'
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 55 AND 59 THEN '55-59'
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 45 AND 54 THEN '45-54'
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 35 AND 44 THEN '35-44'
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 25 AND 34 THEN '25-34'
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 24 THEN '18-24'
                    ELSE '0'
                END"),
                'male_count' => new \yii\db\Expression('SUM(CASE WHEN gender = "Male" THEN 1 ELSE 0 END)'),
                'female_count' => new \yii\db\Expression('SUM(CASE WHEN gender = "Female" THEN 1 ELSE 0 END)')
            ])
            ->groupBy('age_range')
            ->filterWhere([
                'barangay' => $barangay,
                'purok' => $purok,
                'criteria' . $criteria . '_color_id' => $color
            ])
            ->asArray()
            ->all();

        $colorData = [
            1 => 'Blue voters',
            2 => 'Gray voters',
            3 => 'Blackx voters',
            4 => 'Blacky voters',
            5 => 'Blacku voters'
        ];

        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'ageSegmentationData' => $ageSegmentationData,
                'colorData' => $colorData
            ]);
        }
        return $this->render('voter_segmentation_by_age_gender', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'ageSegmentationData' => $ageSegmentationData,
            'colorData' => $colorData
        ]);
    }
    

    public function actionVoterSegmentationBySector($barangay = null, $purok = null, $criteria = null){
        
        $chart_data = [
            ["name" => "Senior", "data" => [0, 3, 2, 4, 6]], 
            ["name" => "PWD", "data" => [1, 2, 3, 1, 2]],
            ["name" => "Youth", "data" => [4, 5, 6, 3, 2]],
            ["name" => "Women", "data" => [2, 3, 4, 5, 0]],
        ];
        
        $survey_colors = Specialsurvey::surveyColorReIndex();
        $barangay_labels = array_column($survey_colors, 'label');
        $chart_data_json = json_encode($chart_data);
        $barangay_labels_json = json_encode($barangay_labels);

        return $this->render('voter_segmentation_by_sector',[
            'chart_data_json' => $chart_data_json,
            'barangay_labels_json' => $barangay_labels_json,
        ]);
    }

    public function actionVoterSocialAssistanceBeneficiaries($criteria= null){
        $survey_color = Specialsurvey::surveyColorReIndex();
		
        $searchModel = new SpecialsurveySearch();

        
        $queryParams=App::queryParams();
        
         if (isset($queryParams['criteria1_color_id'])) {
			unset($queryParams['criteria1_color_id']);
			$criteria = $criteria ?: 1;
		}
		if (isset($queryParams['criteria2_color_id'])) {
			unset($queryParams['criteria2_color_id']);
			$criteria = $criteria ?: 2;
		}
		if (isset($queryParams['criteria3_color_id'])) {
			unset($queryParams['criteria3_color_id']);
			$criteria = $criteria ?: 3;
		}
		if (isset($queryParams['criteria4_color_id'])) {
			unset($queryParams['criteria4_color_id']);
			$criteria = $criteria ?: 4;
		}
		if (isset($queryParams['criteria5_color_id'])) {
			unset($queryParams['criteria5_color_id']);
			$criteria = $criteria ?: 5;
		}
		$criteria = $criteria ?: 1;
        
        $dataProvider = $searchModel->search(['SpecialsurveySearch' =>  $queryParams]);
        
        
        
        $criteria=2;    
        $dataProvider->query->select(['
                t.*, 
                (t.criteria'.$criteria.'_color_id) as criteria1_color_id
                ']);
        
        $color_survey = $queryParams['color_survey'];
        if($color_survey){
        $color_survey = explode(',', $color_survey);
        $dataProvider->query->andFilterWhere(['t.criteria'.$criteria.'_color_id' => $color_survey]);
        }


        return $this->render('voter_social_assistance_beneficiaries', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'survey_color'=>$survey_color
        ]);
        
    }
    
    
    
        


    
    
    

    
    
    
    
    




    // ////////////////////////////////////////////////////
    


    
    public function actionVoterInsights($print=null)
    {
        $searchModel = new SpecialsurveySearch();
		
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => App::queryParams()]);
        $searchModel->searchAction	= ['specialsurvey/report-per-barangay'];	
		
        $rowsummary = $searchModel->getRowSummary($dataProvider);

        if($print) {
			$this->layout = "@app/views/layouts/print";
			return $this->render('report_barangay_print', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
				'rowsummary' => $rowsummary,
	        ]);
		}
		
        return $this->render('voter_insights', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'rowsummary' => $rowsummary,
        ]);
    }
    
    
     public function actionCampaignProgress($print=null)
    {
        $searchModel = new SpecialsurveySearch();
		
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => App::queryParams()]);
        $searchModel->searchAction	= ['specialsurvey/report-per-barangay'];	
		
        $rowsummary = $searchModel->getRowSummary($dataProvider);

        if($print) {
			$this->layout = "@app/views/layouts/print";
			return $this->render('report_barangay_print', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
				'rowsummary' => $rowsummary,
	        ]);
		}
		
        return $this->render('campaign_progress', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'rowsummary' => $rowsummary,
        ]);
    }
    
     public function actionLidersList($print=null)
    {
        $searchModel = new SpecialsurveySearch();
		
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => App::queryParams()]);
        $searchModel->searchAction	= ['specialsurvey/report-per-barangay'];	
		
        $rowsummary = $searchModel->getRowSummary($dataProvider);

        if($print) {
			$this->layout = "@app/views/layouts/print";
			return $this->render('report_barangay_print', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
				'rowsummary' => $rowsummary,
	        ]);
		}
		
        return $this->render('liders_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'rowsummary' => $rowsummary,
        ]);
    }
    
    

    public function actionExportCsvReportPerBarangay()
    {
    	$searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => App::queryParams()]);
        $rowsummary = $searchModel->getRowSummary($dataProvider);

        $model = new ExportCsvForm([
            'content' => $this->renderPartial('report_barangay_print', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
				'rowsummary' => $rowsummary,
	        ]),
            'ini_set' => true
        ]);
        return $model->export();
    }
    public function actionExportXlsxReportPerBarangay()
    {
    	$searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => App::queryParams()]);
        $rowsummary = $searchModel->getRowSummary($dataProvider);

        $model = new ExportExcelForm([
            'content' => $this->renderPartial('report_barangay_print', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
				'rowsummary' => $rowsummary,
	        ]),
            'ini_set' => true,
            'type' => 'xlsx'
        ]);
        return $model->export();
    }
	
	public function actionReportPerPurok($print=null)
    {
        $searchModel = new SpecialsurveySearch();
		
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => App::queryParams()]);
        $searchModel->searchAction	= ['specialsurvey/report-per-purok'];	


        $rowsummary = $searchModel->getRowSummary($dataProvider);

		if($print) {
			$this->layout = "@app/views/layouts/print";
			return $this->render('report_purok_print', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
				'rowsummary' => $rowsummary,
			]);
		}
		
        return $this->render('report_purok', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'rowsummary'=>$rowsummary,
        ]);
    }

    public function actionExportCsvReportPerPurok()
    {
    	$searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => App::queryParams()]);
        $rowsummary = $searchModel->getRowSummary($dataProvider);

        $model = new ExportCsvForm([
            'content' => $this->renderPartial('report_purok_print', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
				'rowsummary' => $rowsummary,
	        ]),
            'ini_set' => true
        ]);
        return $model->export();
    }
    public function actionExportXlsxReportPerPurok()
    {
    	$searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => App::queryParams()]);
        $rowsummary = $searchModel->getRowSummary($dataProvider);

        $model = new ExportExcelForm([
            'content' => $this->renderPartial('report_purok_print', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
				'rowsummary' => $rowsummary,
	        ]),
            'ini_set' => true,
            'type' => 'xlsx'
        ]);
        return $model->export();
    }




    /**
     * Deletes an existing Specialsurvey model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Specialsurvey::controllerFind($id);

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
   /*
    public function actionPrint()
    {
        return $this->exportPrint();
    }
   
    public function _ctionExportPdf()
    {
        return $this->exportPdf();
    }*/

    public function actionExportCsv()
    {
        return $this->exportCsv();
    }

    /*public function _actionExportXls()
    {
        return $this->exportXls();
    }*/

    public function actionExportXlsx()
    {
        return $this->exportXlsx();
    }

    public function actionInActiveData()
    {
        # dont delete; use in condition if user has access to in-active data
    }

    public function actionSettings()
    {
    	$model = new SurveySettingForm();

    	if ($model->load(App::post()) && $model->save()) {
            App::success('Successfully Updated');

            return $this->redirect(['settings']);
        }

        return $this->render('settings', [
            'model' => $model,
        ]);
    }

    public function actionValidateFile($file_token='')
    {
        $model = new SpecialsurveyImportForm([
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