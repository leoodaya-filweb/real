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
use app\models\Member;
use app\models\search\MemberSearch;
use app\models\search\SpecialsurveySearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\db\Query;
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
        
        
        //  OLD 
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

        //NEW DESIGN
        // if ($hs) {
        //     $household = Household::find()->where(['no' => $hs])->one();
        //     if (!$household) {
        //         return "Household not found.";
        //     }
        
        //     $familyHead = Member::find()->where(['household_id' => $household->id, 'head' => 1])->one();
        //     if (!$familyHead) {
        //         return "Family Head not found.";
        //     }
        
        //     $voters = Specialsurvey::find()->alias('t')
        //         ->select(['t.*, (t.criteria' . $criteria . '_color_id) as criteria1_color_id'])
        //         ->where(['household_no' => $hs])
        //         ->andFilterWhere(['t.survey_name' => $queryParams['survey_name']])
        //         ->all();
        
        //     $survey_color = Specialsurvey::surveyColorReIndex();
        //     $output = null;
        
        //     if ($voters) {
        //         $output .= '<div>';
        //         $output .= 'Family Head: ' . ucfirst(strtolower($familyHead->last_name)) . ", " . ucfirst(strtolower($familyHead->first_name)) . " " . ucfirst(strtolower($familyHead->middle_name)) . '<br/>';
        //         $output .= "Total Voters: " . count($voters);
        
        //         $output .= '<div class="container-fluid row gap-3 justify-content-center mt-1">';
        
        //         $counter = 0;
        //         foreach ($survey_color as $color_id => $color_data) {
        //             $totalVoters = Specialsurvey::find()
        //                 ->where(['household_no' => $hs, 'criteria' . $criteria . '_color_id' => $color_id])
        //                 ->andFilterWhere(['survey_name' => $queryParams['survey_name']])
        //                 ->count();
        
        //             if ($counter % 2 === 0) {
        //                 $output .= '<div class="d-flex gap-3 justify-content-center mb-3" style="width: 100%;">';
        //             }
        
        //             $output .= '<div class="card text-center" title="' . htmlspecialchars($color_data['name']) . '" style="background-color: ' . htmlspecialchars($color_data['color']) . '; color: ' . ($color_data['color'] === '#e4e6ef' ? 'black' : 'white') . '; padding: 5px; flex: 1; height: 65px; max-width: 70px; margin: 0 5px;">';
        //             $output .= '<div class="card-body" style="font-size: 12px; display: flex; align-items: center; justify-content: center; height: 100%;">';
        //             $output .= '<div>' . $totalVoters . '</div>';
        //             $output .= '</div>';
        //             $output .= '</div>';
        
        //             $counter++;
        //             if ($counter % 2 === 0) {
        //                 $output .= '</div>'; // Close row after every 2 cards
        //             }
        //         }
        
        //         if ($counter % 2 !== 0) {
        //             $output .= '</div>'; // Close last row if it has an odd number of cards
        //         }
        
        //         $output .= '</div>'; // Close main container
        
        //         // $output .= 'Total Number of Assistance: ' . $household->totalTransactions . '<br/>';
        //         // $output .= 'Total Amount: ' . $household->totalAmountTransactions . '<br/>';
        //         // $output .= 'Social Pension: ' . $household->social_pension . '<br/>';
        //         $output .= '<div class="d-flex gap-2 justify-content-center mb-1">';

        //         // Household Profile Card
        //         $output .= '<div class="card text-center" style="background-color: #ffffff; color: black; padding: 10px; flex: 1; height: 80px; max-width: 100px; margin: 0 5px; cursor: pointer; border-radius: 10px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1); border: 1px solid #ddd;">';
        //         $output .= '<div class="card-body" style="font-size: 14px; display: flex; align-items: center; justify-content: center; height: 100%;">';
        //         $output .= '<div>Household Profile</div>';
        //         $output .= '</div>';
        //         $output .= '</div>';

        //         // Previous Record Card 
        //         $output .= '<div class="card text-center" style="background-color: #464545; color: white; padding: 10px; flex: 1; height: 80px; max-width: 100px; margin: 0 5px; cursor: pointer; border-radius: 10px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1); border: 1px solid #ddd;">';
        //         $output .= '<div class="card-body" style="font-size: 14px; display: flex; align-items: center; justify-content: center; height: 100%;">';
        //         $output .= '<div>Previous Record</div>';
        //         $output .= '</div>';
        //         $output .= '</div>';

        //         $output .= '</div>'; // Close the main div


        //         $output .= 'Encoder: ' . ($voters->encoder ?? 'N/A') . '<br/>';
        //         $output .= 'Leader: ' . ($voters->leader ?? 'N/A') . '<br/>';
        //         $output .= '</div>';
        //     }
        
        //     return $output;
        // }
        
        
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

        if (!empty($queryParams['graph']) && !empty($queryParams['grey']) && $queryParams['grey'] == 1) {
            return $this->renderAjax('_grey_graph', [
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
            $coords = json_decode($row['coordinates'], true) ?: [];
    
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
        // $output[] = "#e4e6ef";
        $output[] = "#808080";  
        if (!empty($queryParams['graph']) && !empty($queryParams['grey']) && $queryParams['graph'] == 1 && $queryParams['grey'] == 1) {
            return $this->renderAjax('_grey_graph', [
                'features' => $features,
                'queryParams' => $queryParams,
            ]);
        }

        $purok=[];
        if($searchModel->barangay){
            $purok = Specialsurvey::find()->select(['purok'])->andWhere("purok is not null and purok not in('','-','0') ")->andFilterWhere(['barangay'=>$searchModel->barangay])->groupBy("purok")->orderby(['purok'=>SORT_ASC])->asArray()->all();
        }



		
        return $this->asJson([
            "type" => "FeatureCollection",
            "features" => $features,
            "output" => $output,
            "purok"=>$purok,
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
    
    
    // FOR CAMPAIGN INTELLIGENCE DASHBOARD & Voter Analysis
    public function actionBarangayCoordinates1($criteria = '',$unregistered='')
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

        if($unregistered==1){
            $dataProvider = $searchModel->searchSummaryForUnregisteredVoters(['SpecialsurveySearch' => $queryParams]);

        }else{
            $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => $queryParams]);

        }
        // $dataProvider = $searchModel->searchsummary(['SpecialsurveySearch' => $queryParams]);
    
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
            // $total_black = $barangay_data[$row['barangay']]["criteria{$criteria}_color_black"] ?? 0;
            // $total_gray = $barangay_data[$row['barangay']]["criteria{$criteria}_color_gray"] ?? 0;
            // $total_green = $barangay_data[$row['barangay']]["criteria{$criteria}_color_green"] ?? 0;
            // $total_red = $barangay_data[$row['barangay']]["criteria{$criteria}_color_red"] ?? 0;

            // $barangay_colors = [
            //     1 => $total_black,
            //     2 => $total_gray,
            //     3 => $total_green,
            //     4 => $total_red
            // ];

            $total_blue = $barangay_data[$row['barangay']]["criteria{$criteria}_color_blue"] ?? 0;
            $total_gray = $barangay_data[$row['barangay']]["criteria{$criteria}_color_gray"] ?? 0;
            $total_blackx = $barangay_data[$row['barangay']]["criteria{$criteria}_color_blackx"] ?? 0;
            $total_blacky = $barangay_data[$row['barangay']]["criteria{$criteria}_color_blacky"] ?? 0;
            $total_blacku = $barangay_data[$row['barangay']]["criteria{$criteria}_color_blacku"] ?? 0;


            $barangay_colors = [
                1 => $total_blue,
                2 => $total_gray,
                3 => $total_blackx,
                4 => $total_blacky,
                5=> $total_blacku,
            ];

            arsort($barangay_colors);
            $dominant_color_id = key($barangay_colors);
            $dominant_color = $color_map[$dominant_color_id] ?? "#E4E6EF";

            // Calculate dominance percentage
            $total_colors = array_sum($barangay_colors);
            $dominance_percentage = $total_colors > 0 ? round(($barangay_colors[$dominant_color_id] / $total_colors) * 100, 2) : 0;

            // Ensure correct filtering logic
            if (!empty($color_survey) && !in_array($dominant_color_id, $color_survey)) {
                $dominant_color = "#FFFFFF"; // Default color for excluded barangays
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

        
        $output[] = "#FFFFFF";
    
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
   
    public function actionPopulationCoordinates2($criteria = '', $brgy = '', $hs = '',$unregistered='')
    {
        $queryParams = App::queryParams();
        $criteria = $criteria ?: 1;
        
        $color_survey = $queryParams['color_survey'];
        if ($color_survey) {
            $color_survey = explode(',', $color_survey);
            
        } 
        // Filter unregistered voters
        $filteredIds = (new \yii\db\Query())
            ->select('s.id')
            ->from(['s' => 'tbl_specialsurvey'])
            ->innerJoin(['m' => 'tbl_members'],
                'LOWER(TRIM(m.last_name)) = LOWER(TRIM(s.last_name)) AND ' .
                'LOWER(TRIM(m.first_name)) = LOWER(TRIM(s.first_name)) AND ' .
                'LOWER(TRIM(m.middle_name)) = LOWER(TRIM(s.middle_name))'
            )
            ->innerJoin(['hs' => 'tbl_households'], 'm.household_id = hs.id')
            ->innerJoin(['b' => 'tbl_barangays'], 'hs.barangay_id = b.no')
            ->where(['m.voter' => [0, 2]])
            ->andFilterWhere(['s.barangay' => $queryParams['barangay'] ?? null])
            ->andFilterWhere(['s.purok' => $queryParams['purok'] ?? null])
            ->andFilterWhere(['s.household_no' => $queryParams['household_no'] ?? null])
            ->andFilterWhere(['s.criteria'.$criteria.'_color_id' => $color_survey])
            ->andFilterWhere(['s.survey_name' =>$queryParams['survey_name']])
            ->orderBy(['b.name' => SORT_ASC, 's.id' => SORT_ASC])
            ->column();


        /////////////////////////////
        if ($hs) {
            $household = Household::find()->where(['no' => $hs])->one();
            if (!$household) {
                return "Household not found.";
            }
        
            $familyHead = Member::find()->where(['household_id' => $household->id, 'head' => 1])->one();
            if (!$familyHead) {
                return "Family Head not found.";
            }
        
            if($unregistered==1){
                $voters = Specialsurvey::find()->alias('t')
                ->select(['t.*', '(t.criteria' . $criteria . '_color_id) as criteria1_color_id'])
                ->leftJoin(['m' => 'tbl_members'],
                'LOWER(TRIM(m.last_name)) = LOWER(TRIM(t.last_name)) AND ' .
                    'LOWER(TRIM(m.first_name)) = LOWER(TRIM(t.first_name)) AND ' .
                    'LOWER(TRIM(m.middle_name)) = LOWER(TRIM(t.middle_name))'
                )
                ->where(['t.household_no' => $hs])
                ->andFilterWhere(['t.survey_name' => $queryParams['survey_name']])
                ->andWhere(['m.voter' => [0, 2]]) // only unregistered voters
                ->all();

            }else{
                $voters = Specialsurvey::find()->alias('t')
                ->select(['t.*, (t.criteria' . $criteria . '_color_id) as criteria1_color_id'])
                ->where(['household_no' => $hs])
              
                ->andFilterWhere(['t.survey_name' => $queryParams['survey_name']])
                ->all();
            }
           
        
            $survey_color = Specialsurvey::surveyColorReIndex();
            $output = null;
        
            if ($voters) {
                $output .= '<div>';
                $output .= 'Family Head: ' . ucfirst(strtolower($familyHead->last_name)) . ", " . ucfirst(strtolower($familyHead->first_name)) . " " . ucfirst(strtolower($familyHead->middle_name)) . '<br/>';
                // $output .= "Total Voters: " . count($voters);
        


                if($unregistered==1){
                 
                    $output .= "Total Unregistered Voters: " . count($voters);
        
                }else{
                    $output .= "Total Voters: " . count($voters);
        
                }
                $output .= '<div class="container-fluid row gap-3 justify-content-center mt-1">';
        
                $counter = 0;
                foreach ($survey_color as $color_id => $color_data) {

                    if($unregistered==1){
                        $totalVoters = Specialsurvey::find()->alias('t')
                        ->leftJoin(['m' => 'tbl_members'],
                        'LOWER(TRIM(m.last_name)) = LOWER(TRIM(t.last_name)) AND ' .
                            'LOWER(TRIM(m.first_name)) = LOWER(TRIM(t.first_name)) AND ' .
                            'LOWER(TRIM(m.middle_name)) = LOWER(TRIM(t.middle_name))'
                        )
                        ->where([
                            't.household_no' => $hs,
                            't.criteria' . $criteria . '_color_id' => $color_id,
                        ])
                        ->andFilterWhere(['t.survey_name' => $queryParams['survey_name']])
                        ->andWhere(['m.voter' => [0, 2]]) // only unregistered voters
                        ->count();
        
                    }else{
                        $totalVoters = Specialsurvey::find()
                        ->where(['household_no' => $hs, 'criteria' . $criteria . '_color_id' => $color_id])
                        ->andFilterWhere(['survey_name' => $queryParams['survey_name']])
                        ->count();

                    }
                  

                   

        
                    if ($counter % 2 === 0) {
                        $output .= '<div class="d-flex gap-3 justify-content-center mb-3" style="width: 100%;">';
                    }
        
                    $output .= '<div class="card text-center" title="' . htmlspecialchars($color_data['name']) . '" style="background-color: ' . htmlspecialchars($color_data['color']) . '; color: ' . ($color_data['color'] === '#e4e6ef' ? 'black' : 'white') . '; padding: 5px; flex: 1; height: 65px; max-width: 70px; margin: 0 5px;">';
                    $output .= '<div class="card-body" style="font-size: 12px; display: flex; align-items: center; justify-content: center; height: 100%;">';
                    $output .= '<div>' . $totalVoters . '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
        
                    $counter++;
                    if ($counter % 2 === 0) {
                        $output .= '</div>'; // Close row after every 2 cards
                    }
                }
        
                if ($counter % 2 !== 0) {
                    $output .= '</div>'; // Close last row if it has an odd number of cards
                }
        
                $output .= '</div>'; // Close main container
        
                // $output .= 'Total Number of Assistance: ' . $household->totalTransactions . '<br/>';
                // $output .= 'Total Amount: ' . $household->totalAmountTransactions . '<br/>';
                // $output .= 'Social Pension: ' . $household->social_pension . '<br/>';
                $output .= '<div class="d-flex gap-2 justify-content-center mb-2">';
                // Household Profile Card
                $output .= '<div class="card text-center" style="background-color: #ffffff; color: black; padding: 5px; flex: 1; height: 65px; max-width: 85px; margin: 0 2px; cursor: pointer; border-radius: 10px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1); border: 1px solid #ddd;" onclick="window.open(\'' . Yii::$app->urlManager->createAbsoluteUrl(['/household/view', 'no' => $hs]) . '\', \'_blank\')">';
                $output .= '<div class="card-body" style="font-size: 14px; display: flex; align-items: center; justify-content: center; height: 100%;">';
                $output .= '<div>Household Profile</div>';
                $output .= '</div>';
                $output .= '</div>';

                // Previous Record Card 
                $output .= '<div class="card text-center" style="background-color: #464545; color: white; padding: 5px; flex: 1; height: 65px; max-width: 85px; margin: 0 2px; cursor: pointer; border-radius: 10px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1); border: 1px solid #ddd;" onclick="window.open(\'' . Yii::$app->urlManager->createAbsoluteUrl(['/household/previous-record', 'no' => $hs]) . '\', \'_blank\')">';
                $output .= '<div class="card-body" style="font-size: 14px; display: flex; align-items: center; justify-content: center; height: 100%;">';
                $output .= '<div>Previous Record</div>';
                $output .= '</div>';
                $output .= '</div>';

                $output .= '</div>'; // Close the main div


                $output .= 'Encoder: ' . ($voters->encoder ?? 'N/A') . '<br/>';
                $output .= 'Leader: ' . ($voters->leader ?? 'N/A') . '<br/>';
                $output .= '</div>';
            }
        
            return $output;
        }
        
    
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
                
                // $color_survey = [1,2,3,4,5];
                $survey_colors = Specialsurvey::surveyColorReIndex();
                $color_survey = array_keys($survey_colors);

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

        // Ensure there's valid data 
        if (empty($validBarangays)) {
            return $this->asJsonNumeric([
                "type" => "FeatureCollection",
                "features" => [],
               
            ]);
        }
    
        $searchModel = new SpecialsurveySearch();
        $dataProvider = $searchModel->searchvoters(['SpecialsurveySearch' => $queryParams]);
        // $dataProvider->query->andWhere(['t.barangay' => $validBarangays]); //Filter only the valid Barangays
    
        // $dataProvider->query->andWhere(['or', 
        //     ['t.barangay' => $validBarangays], 
        //     ['t.barangay' => ['Poblacion 61 (Barangay 2)', 'Poblacion I (Barangay 1)']]
        // ]); // Filter only the valid Barangays including specific cases


        if($unregistered==1){

            $dataProvider->query->select([
                't.id', 't.first_name', 't.middle_name', 't.last_name', 't.household_no', 't.leader',
                '(t.criteria' . $criteria . '_color_id) as criteria1_color_id',
                'count(t.id) as total_voters', 'hs.longitude', 'hs.latitude', 't.barangay'
            ])->filterWhere(['t.id' => $filteredIds]);

            

        }else{
            $dataProvider->query->select([
                't.id', 't.first_name', 't.middle_name', 't.last_name', 't.household_no', 't.leader',
                '(t.criteria' . $criteria . '_color_id) as criteria1_color_id',
                'count(t.id) as total_voters', 'hs.longitude', 'hs.latitude', 't.barangay'
            ]);
        
            $dataProvider->query->andFilterWhere(['t.criteria'.$criteria.'_color_id' => $color_survey]);
        }

    
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

        $survey_colors = Specialsurvey::surveyColorReIndex();
        $colorData = [];

        foreach ($survey_colors as $key => $color) {
            $colorData[$key] = $color['label'] . ' voters';
        }


        $purok=[];
        if($barangay){
            $purok = Specialsurvey::find()->select(['purok'])->andWhere("purok is not null and purok not in('','-','0') ")->andFilterWhere(['barangay'=>$barangay])->groupBy("purok")->orderby(['purok'=>SORT_ASC])->asArray()->all();
        }

        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'ageSegmentationData' => $ageSegmentationData,
                'purok' =>  $purok,
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
    

    public function actionVoterSegmentationBySector($barangay = null, $purok = null, $criteria = null)
    {
        $criteria = $criteria ?? 1;
    
        $survey_colors = Specialsurvey::surveyColorReIndex();
        $color_count = count($survey_colors); // Get the total number of colors
        $color_labels = array_column($survey_colors, 'label'); // Get the color labels
    
        $query = (new \yii\db\Query())
            ->select([
                's.criteria' . $criteria . '_color_id',
                'SUM(CASE WHEN m.senior_citizen_id = 1 THEN 1 ELSE 0 END) AS Senior',
                'SUM(CASE WHEN m.pwd = 1 THEN 1 ELSE 0 END) AS PWD',
                // 'SUM(CASE WHEN m.sex = 2 THEN 1 ELSE 0 END) AS Women',
                // 'SUM(CASE WHEN TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE()) BETWEEN 15 AND 30 THEN 1 ELSE 0 END) AS Youth'
            ])
            ->from('tbl_specialsurvey s')
            ->leftJoin('tbl_members m', 'm.last_name = s.last_name AND m.first_name = s.first_name AND m.middle_name = s.middle_name')
            ->where(['or',
                ['m.pwd' => 1],
                ['m.senior_citizen_id' => 1],
                ['m.sex' => 2],
                ['between', 'TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE())', 15, 30]
            ])
            ->andFilterWhere([
                's.barangay' => $barangay,
                's.purok' => $purok,
            ])
            ->groupBy('s.criteria' . $criteria . '_color_id')
            ->orderBy('s.criteria' . $criteria . '_color_id')
            ->all();
    
        $chart_data = [
            "Senior" => array_fill(0, $color_count, 0),
            "PWD" => array_fill(0, $color_count, 0),
            // "Women" => array_fill(0, $color_count, 0),
            // "Youth" => array_fill(0, $color_count, 0),
        ];
    
        // Populate chart data from the query result
        foreach ($query as $row) {
            // Get the color index from the criteria color ID
            $color_index = array_search($row['criteria' . $criteria . '_color_id'], array_column($survey_colors, 'id'));
    
            if ($color_index !== false) {
                if ($row['Senior'] > 0) {
                    $chart_data['Senior'][$color_index] = (int)$row['Senior'];
                }
                if ($row['PWD'] > 0) {
                    $chart_data['PWD'][$color_index] = (int)$row['PWD'];
                }
                // if ($row['Women'] > 0) {
                //     $chart_data['Women'][$color_index] = (int)$row['Women'];
                // }
                // if ($row['Youth'] > 0) {
                //     $chart_data['Youth'][$color_index] = (int)$row['Youth'];
                // }
            }
        }
    
        // Prepare final chart data in the required format
        $chart_data_final = [
            ["name" => "Senior", "data" => $chart_data['Senior']],
            ["name" => "PWD", "data" => $chart_data['PWD']],
            // ["name" => "Youth", "data" => $chart_data['Youth']],
            // ["name" => "Women", "data" => $chart_data['Women']],
        ];
    
        // Convert chart data and color labels to JSON
        $chart_data_json = json_encode($chart_data_final);
        $color_labels_json = json_encode($color_labels);
    
        // Fetch the available 'purok' based on the selected 'barangay'
        $purok = [];
        if ($barangay) {
            $purok = Specialsurvey::find()
                ->select(['purok'])
                ->andWhere("purok is not null and purok not in('', '-', '0')")
                ->andFilterWhere(['barangay' => $barangay])
                ->groupBy('purok')
                ->orderby(['purok' => SORT_ASC])
                ->asArray()
                ->all();
        }
    
        // Return JSON response if AJAX request
        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'chart_data_json' => $chart_data_json,
                'color_labels_json' => $color_labels_json,
                'purok' => $purok,
            ]);
        }
    
        // Render the view
        return $this->render('voter_segmentation_by_sector', [
            'chart_data_json' => $chart_data_json,
            'color_labels_json' => $color_labels_json,
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


    public function actionRegisteredVsUnregisteredVoters($list = null,$criteria = null)
    {
        $queryParams = App::queryParams();

        
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
        $color_survey = $queryParams['color_survey'];

        if($color_survey){
            $color_survey = explode(',', $color_survey);
            //  $dataProvider->query->andFilterWhere(['t.criteria'.$criteria.'_color_id' => $color_survey]);
        }

        // $barangay = trim($queryParams['barangay'] ?? '');
        // $purok = trim($queryParams['purok'] ?? '');
        // $surveyName = trim($queryParams['survey_name'] ?? '');
        // $colorSurvey = !empty($queryParams['color_survey']) ? explode(',', $queryParams['color_survey']) : [];

        $query = Specialsurvey::find()
            ->alias('s')
            ->innerJoin(['m' => 'tbl_members'],
                'LOWER(TRIM(m.last_name)) = LOWER(TRIM(s.last_name)) AND ' .
                'LOWER(TRIM(m.first_name)) = LOWER(TRIM(s.first_name)) AND ' .
                'LOWER(TRIM(m.middle_name)) = LOWER(TRIM(s.middle_name))'
            )
            ->innerJoin(['hs' => 'tbl_households'], 'm.household_id = hs.id')
            ->innerJoin(['b' => 'tbl_barangays'], 'hs.barangay_id = b.no')
            ->where(['m.voter' => [0, 2]])
            // ->andFilterWhere(['b.name' => $queryParams['barangay']])
            // ->andFilterWhere(['hs.purok_no' => $queryParams['purok']])
            ->andFilterWhere(['s.household_no' => $queryParams['household_no']])
            ->andFilterWhere(['s.barangay' => $queryParams['barangay']])
            ->andFilterWhere(['s.purok' => $queryParams['purok']])
            ->andFilterWhere(['s.survey_name' => $queryParams['survey_name']])
            ->andFilterWhere(['s.criteria' . $criteria . '_color_id' => $color_survey]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC],
            ],
        ]);
        

       
        if (Yii::$app->request->isAjax) {
          
            return $this->renderAjax('index_list', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'survey_color' => $survey_color
            ]);
        }
    
        return $this->render('registered_vs_unregistered_voters', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            // 'barangayLabels' => json_encode($barangayLabels),
            // 'chartData' => json_encode($chartData),
        ]);
    }
    

    public function actionUnregisteredVotersPopulation( $criteria =null)
    {
        $queryParams = App::queryParams();

    
        return $this->renderAjax('_registered_unregistered_chart',
        [
                'queryParams' =>$queryParams,
                'criteria' => $criteria,
            ]
        );

       
    }

   

    public function actionCanvassingCoverageProgress($list= null)
    {
        // Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $searchModel = new SpecialsurveySearch();
        $queryParams = App::queryParams();

        $selectedSurvey = $queryParams['survey_name'] ?? "Survey 5";
        $selectedCriteria = $queryParams['criteria'] ?? 1;
        $color_survey = $queryParams['color']??null;

        $dataProvider = $searchModel->search(['SpecialsurveySearch' => $queryParams]);
    
        // Select only necessary columns & apply grey color filter
        $dataProvider->query->select([
            't.*',
            "(t.criteria{$selectedCriteria}_color_id) as criteria1_color_id"
        ])->andFilterWhere(['t.survey_name' => $selectedSurvey])
        ->andFilterWhere(['t.criteria'.$selectedCriteria.'_color_id' => $color_survey]);

        // Construct the base query
        $query = (new \yii\db\Query())
            ->select([
                'b.name AS barangay_name',
                's.survey_name',
                'COUNT(DISTINCT s.household_no) AS surveyed_households',
                'COUNT(DISTINCT h.no) AS total_households',
                'ROUND(COUNT(DISTINCT s.household_no) / COUNT(DISTINCT h.no) * 100, 2) AS survey_coverage_percent',
            ])
            ->from('tbl_barangays b')
            ->leftJoin('tbl_households h', 'b.no = h.barangay_id');
           
            
        if($color_survey){
            $query->leftJoin(
                'tbl_specialsurvey s',
                
                's.barangay = b.name AND s.household_no = h.no AND s.survey_name = :survey_name AND s.criteria'.$selectedCriteria.'_color_id =:color_survey'
                // 's.barangay = b.name AND s.household_no = h.no AND s.survey_name = :survey_name'
            )
            ->addParams([':color_survey' => $color_survey])
            ->addParams([':survey_name' => $selectedSurvey]);
        }else{
            $query->leftJoin(
                'tbl_specialsurvey s',
                
                's.barangay = b.name AND s.household_no = h.no AND s.survey_name = :survey_name'
            )
            ->addParams([':survey_name' => $selectedSurvey]);
        }
        // Group and order the results
        $query->groupBy('b.name')
            ->orderBy('b.name');

        // Fetch the results
        $results  = $query->all();

        
        
        // // Prepare data for the chart
        // $chartData = [];
        // $barangayNames = [];
        // foreach ($results as $row) {
        //     $chartData[] = (float)$row['survey_coverage_percent'];
        //     $barangayNames[] = $row['barangay_name'];
        // }

        if($list == 1){
            $list=0;
            return $this->renderAjax('index_list',[
                'dataProvider'=> $dataProvider,
                'searchModel' => $searchModel
            ]);
        }

       

        if(Yii::$app->request->isAjax) {
            return $this->asJson([
                'success' => true,
                'filters' => [
                    'survey_name' => $selectedSurvey,
                    'criteria' => $selectedCriteria,
                    'color' => $color_survey,
                ],
                'chartData' => json_encode($results),  // Encode to JSON for JS
                
            ]);
    
        }

        $survey_colors = Specialsurvey::surveyColorReIndex();
        $colorData = [];

        foreach ($survey_colors as $key => $color) {
            $colorData[$key] = $color['label'] . ' voters';
        }

        return $this->render('canvassing_coverage_progress', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'chartData' => json_encode($results),  // Encode to JSON for JS
            'colorData' => $colorData,
        ]);

        
    }


    public function actionProjectTurnout()
    {
        $queryParams = Yii::$app->request->queryParams;
        $colorId = $queryParams['color'] ?? 1;
        $surveyName = $queryParams['survey_name'] ?? 'Survey 5';
        $turnoutRate = 0.8;
    
        $sql = "
            SELECT 
                tv.barangay,
                tv.total_voters,
                tv.total_registered,
                sv.support_voters,
                sv.total_canvassed,
                sv.support_rate,
                ROUND(sv.support_rate * tv.total_registered * :turnout_rate, 2) AS projected_votes
            FROM (
                SELECT 
                    b.name AS barangay,
                    COUNT(DISTINCT m.id) AS total_voters,
                    SUM(CASE WHEN m.voter = 1 THEN 1 ELSE 0 END) AS total_registered
                FROM tbl_members m
                INNER JOIN tbl_households hs ON m.household_id = hs.id
                INNER JOIN tbl_barangays b ON hs.barangay_id = b.no
                GROUP BY b.name
            ) AS tv
            LEFT JOIN (
                SELECT 
                    b.name AS barangay,
                    SUM(CASE WHEN s.criteria1_color_id = :color_id THEN 1 ELSE 0 END) AS support_voters,
                    COUNT(DISTINCT s.id) AS total_canvassed,
                    ROUND(
                        CASE 
                            WHEN COUNT(DISTINCT s.id) = 0 THEN 0
                            ELSE SUM(CASE WHEN s.criteria1_color_id = :color_id THEN 1 ELSE 0 END) * 1.0 / COUNT(DISTINCT s.id)
                        END,
                        2
                    ) AS support_rate
                FROM tbl_specialsurvey s
                INNER JOIN tbl_members m 
                    ON LOWER(TRIM(m.last_name)) = LOWER(TRIM(s.last_name)) 
                    AND LOWER(TRIM(m.first_name)) = LOWER(TRIM(s.first_name)) 
                    AND LOWER(TRIM(m.middle_name)) = LOWER(TRIM(s.middle_name))
                INNER JOIN tbl_households hs ON m.household_id = hs.id
                INNER JOIN tbl_barangays b ON hs.barangay_id = b.no
                WHERE s.survey_name = :survey_name AND m.voter = 1
                GROUP BY b.name
            ) AS sv ON sv.barangay = tv.barangay
            ORDER BY tv.barangay
        ";
    
        $result = Yii::$app->db->createCommand($sql)
            ->bindValues([
                ':color_id' => $colorId,
                ':survey_name' => $surveyName,
                ':turnout_rate' => $turnoutRate,
            ])
            ->queryAll();
    
        // Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // return $result;

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'chart_data_json' => array_column($result, 'projected_votes'),
                'color_labels_json' => array_column($result, 'barangay'),
                'table_html' => $this->renderPartial('_turnout_table', ['datas' => $result]),
            ];
        }
        
        $survey_colors = Specialsurvey::surveyColorReIndex();
        $colorData = [];

        foreach ($survey_colors as $key => $color) {
            $colorData[$key] = $color['label'] . ' voters';
        }
        return $this->render('project_turnout',[
            'datas' => $result,
            'chart_data_json' => array_column($result, 'projected_votes'),
            'color_labels_json' => array_column($result, 'barangay'),
            'colorData' => $colorData,
        ]);
    }


     // public function actionUnregisteredVotersPopulation( $brgy = '', $hs = '')
    // {
    //     $queryParams = App::queryParams();

    //     // ———— 1) Single-household popup ————
    //     if ($hs) {
    //         $household = Household::find()->where(['no' => $hs])->one();
    //         if (!$household) {
    //             return "Household not found.";
    //         }

    //         $familyHead = Member::find()
    //             ->where(['household_id' => $household->id, 'head' => 1])
    //             ->one();
    //         if (!$familyHead) {
    //             return "Family Head not found.";
    //         }

    //         // Count unregistered voters in this household
    //         $count = (new \yii\db\Query())
    //             ->from('tbl_members')
    //             ->where(['household_id' => $household->id])
    //             ->andWhere(['voter' => [0, 2]])
    //             ->count();

    //         return "
    //             <div>
    //                 Family Head: " . 
    //                     ucfirst(strtolower($familyHead->last_name)) . ", " .
    //                     ucfirst(strtolower($familyHead->first_name)) . " " .
    //                     ucfirst(strtolower($familyHead->middle_name)) . "<br/>
    //                 Unregistered Voters: {$count}
    //             </div>
    //         ";
    //     }

    //     // ———— 2) brgy=1: return all barangays as before ————
    //     if ($brgy == 1) {
    //         $features = [];
    //         foreach (Barangay::find()->all() as $row) {
    //             $features[] = [
    //                 "type" => "Feature",
    //                 "properties" => [
    //                     "id" => $row->id,
    //                     "name" => $row->name,
    //                 ],
    //                 "geometry" => [
    //                     "type" => "Point",
    //                     "coordinates" => [(float)$row->longitude, (float)$row->latitude]
    //                 ]
    //             ];
    //         }

    //         return $this->asJsonNumeric([
    //             "type" => "FeatureCollection",
    //             "features" => $features,
    //         ]);
    //     }

    //     // ———— 3) Main map: unregistered voters only ————
    //     $rows  = (new \yii\db\Query())
    //         ->select([
    //             'm.id AS member_id',
    //             'm.first_name', 'm.middle_name', 'm.last_name',
    //             'h.latitude', 'h.longitude',
    //             'h.no as household_no'
    //         ])
    //         ->from('tbl_members m')
    //         ->leftJoin('tbl_households h', 'm.household_id = h.id')
    //         ->leftJoin('tbl_barangays b', 'h.barangay_id = b.id')
    //         ->where(['m.voter' => [0, 2]])                        // only unregistered
    //         // ->andWhere(['IS NOT', 'h.latitude', null])           // must have coords
    //         // ->andWhere(['IS NOT', 'h.longitude', null])
    //         ->andFilterWhere([
    //             'b.name' => $queryParams['barangay'],            // filter by barangay name
    //             'h.purok_no' => $queryParams['purok'],           // filter by purok noa
    //         ])
    //         ->all();
    
    //     $features = [];
    //     foreach ($rows as $r) {
    //         $features[] = [
    //             "type" => "Feature",
    //             "properties" => $r,
    //             "geometry" => [
    //                 "type" => "Point",
    //                 "coordinates" => [$r['longitude'], $r['latitude']]
    //             ]
    //         ];
    //     }

    //     return $this->asJsonNumeric([
    //         "type" => "FeatureCollection",
    //         "features" => $features,
    //     ]);
    // }

    
    


    
    
    
        


    

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