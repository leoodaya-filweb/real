<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Database;
use app\helpers\App;
use yii\helpers\ArrayHelper;

/**
 * DatabaseSearch represents the model behind the search form of `app\models\Database`.
 */
class DatabaseSearch extends Database
{
    public $withBaktom = true;

    public $keywords;
    public $date_range;
    public $pagination;
	public $load_params=true;

    public $searchTemplate = 'database/_search';
    public $searchAction = ['database/member'];
    public $searchLabel = 'Database';
	

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'system_id', 'age', 'created_by', 'updated_by'], 'integer'],
            [['priority_sector', 'sector_id', 'last_name', 'first_name', 'middle_name', 'gender', 'date_of_birth', 'civil_status', 'educ_attainment', 'occupation', 'other_source_income', 'house_no', 'street', 'barangay', 'municipality', 'date_registered', 'contact_no', 'pensioner', 'relation_where', 'living_with_whom', 'relation', 'relation_occupation', 'status', 'pic_path', 'shared_pic_path', 'created_at', 'encoded_by', 'edited_by', 'updated_at', 'skills', 'client_category', 'reason1', 'reason2', 'reason3', 'date_of_application', 'birth_place', 'birth_certificate', 'ethnicity', 'source_of_income', 'slp_beneficiary', 'religion', 'mcct_beneficiary', 'remarks', 'type_of_disability'], 'safe'],
            [['monthly_income', 'amount_of_pension', 'relation_income'], 'number'],
            [['keywords', 'pagination', 'date_range', 'record_status'], 'safe'],
            [['keywords'], 'trim'],
        ];
    }

    public function init()
    {
        $this->pagination = App::setting('system')->pagination;
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return \yii\base\Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Database::find();

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['priority_sector'=>SORT_ASC,'created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        
        
        
        $priority_sector = Database::priorityReIndex();
        $sector = $priority_sector[$this->priority_sector];
        if($sector['user_access']){
			   $user_access= json_decode($sector['user_access']);
			   if(is_array($user_access) && !in_array(Yii::$app->user->identity->username,$user_access)){
			     $query->andWhere(['NOT',['priority_sector'=>$this->priority_sector]]);
			   }
			
			   
            }
        
        
        

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'system_id' => $this->system_id,
			'priority_sector' => $this->priority_sector,
			
			'gender' => $this->gender,
			'status'=>($this->keywords?['Active', 'Inactive']:$this->status),
            'age' => $this->age,
            'date_of_birth' => $this->date_of_birth,
            'monthly_income' => $this->monthly_income,
            'date_registered' => $this->date_registered,
            'amount_of_pension' => $this->amount_of_pension,
            'relation_income' => $this->relation_income,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'date_of_application' => $this->date_of_application,
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);
		
		if($this->barangay=='none'){
			$query->andWhere("barangay is null or barangay='' ");
		}else{
			$query->andFilterWhere(['barangay'=>$this->barangay]);
		}
        
        $query
            ->andFilterWhere(['like', 'sector_id', $this->sector_id])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'civil_status', $this->civil_status])
            ->andFilterWhere(['like', 'educ_attainment', $this->educ_attainment])
            ->andFilterWhere(['like', 'occupation', $this->occupation])
            ->andFilterWhere(['like', 'other_source_income', $this->other_source_income])
            ->andFilterWhere(['like', 'house_no', $this->house_no])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'municipality', $this->municipality])
            ->andFilterWhere(['like', 'contact_no', $this->contact_no])
            ->andFilterWhere(['like', 'pensioner', $this->pensioner])
            ->andFilterWhere(['like', 'relation_where', $this->relation_where])
            ->andFilterWhere(['like', 'living_with_whom', $this->living_with_whom])
            ->andFilterWhere(['like', 'relation', $this->relation])
            ->andFilterWhere(['like', 'relation_occupation', $this->relation_occupation])
           // ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'pic_path', $this->pic_path])
            ->andFilterWhere(['like', 'shared_pic_path', $this->shared_pic_path])
            ->andFilterWhere(['like', 'encoded_by', $this->encoded_by])
            ->andFilterWhere(['like', 'edited_by', $this->edited_by])
            ->andFilterWhere(['like', 'skills', $this->skills])
            ->andFilterWhere(['like', 'client_category', $this->client_category])
            ->andFilterWhere(['like', 'reason1', $this->reason1])
            ->andFilterWhere(['like', 'reason2', $this->reason2])
            ->andFilterWhere(['like', 'reason3', $this->reason3])
            ->andFilterWhere(['like', 'birth_place', $this->birth_place])
            ->andFilterWhere(['like', 'birth_certificate', $this->birth_certificate])
            ->andFilterWhere(['like', 'ethnicity', $this->ethnicity])
            ->andFilterWhere(['like', 'source_of_income', $this->source_of_income])
            ->andFilterWhere(['like', 'slp_beneficiary', $this->slp_beneficiary])
            ->andFilterWhere(['like', 'religion', $this->religion])
            ->andFilterWhere(['like', 'mcct_beneficiary', $this->mcct_beneficiary])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'type_of_disability', $this->type_of_disability]);
        
             $this->keywords=trim($this->keywords);   
        $query->andFilterWhere(['or', 
            ['like', 'system_id', $this->keywords],  
            ['like', 'priority_sector', $this->keywords],  
            ['like', 'sector_id', $this->keywords],  
            ['like', 'last_name', $this->keywords],  
            ['like', 'first_name', $this->keywords],  
            ['like', 'middle_name', $this->keywords], 
			
			['like', 'trim(concat(last_name," ",first_name))', $this->keywords],  
            ['like', 'trim(concat(first_name," ",last_name))', $this->keywords],  
            ['like', 'trim(concat(first_name," ",middle_name," ",last_name))', $this->keywords],  
			['like', 'trim(concat(last_name," ",first_name," ",middle_name))', $this->keywords],  
			
            ['like', 'gender', $this->keywords],  
            ['like', 'age', $this->keywords],  
            ['like', 'date_of_birth', $this->keywords],  
            ['like', 'civil_status', $this->keywords],  
            ['like', 'educ_attainment', $this->keywords],  
            ['like', 'occupation', $this->keywords],  
            ['like', 'monthly_income', $this->keywords],  
            ['like', 'other_source_income', $this->keywords],  
            ['like', 'house_no', $this->keywords],  
            ['like', 'street', $this->keywords],  
            ['like', 'barangay', $this->keywords],  
            ['like', 'municipality', $this->keywords],  
            ['like', 'date_registered', $this->keywords],  
            ['like', 'contact_no', $this->keywords],  
            ['like', 'pensioner', $this->keywords],  
            ['like', 'relation_where', $this->keywords],  
            ['like', 'amount_of_pension', $this->keywords],  
            ['like', 'living_with_whom', $this->keywords],  
            ['like', 'relation', $this->keywords],  
            ['like', 'relation_occupation', $this->keywords],  
            ['like', 'relation_income', $this->keywords],  
            ['like', 'pic_path', $this->keywords],  
            ['like', 'shared_pic_path', $this->keywords],  
            ['like', 'encoded_by', $this->keywords],  
            ['like', 'edited_by', $this->keywords],  
            ['like', 'skills', $this->keywords],  
            ['like', 'client_category', $this->keywords],  
            ['like', 'reason1', $this->keywords],  
            ['like', 'reason2', $this->keywords],  
            ['like', 'reason3', $this->keywords],  
            ['like', 'date_of_application', $this->keywords],  
            ['like', 'birth_place', $this->keywords],  
            ['like', 'birth_certificate', $this->keywords],  
            ['like', 'ethnicity', $this->keywords],  
            ['like', 'source_of_income', $this->keywords],  
            ['like', 'slp_beneficiary', $this->keywords],  
            ['like', 'religion', $this->keywords],  
            ['like', 'mcct_beneficiary', $this->keywords],  
            ['like', 'remarks', $this->keywords],  
            ['like', 'type_of_disability', $this->keywords],  
        ]);


    if(!$this->date_range && $this->priority_sector==11){
      $this->date_range = date('Y-m-d', strtotime('first day of january this year')).' - '.date('Y-m-d', strtotime('last day of december this year'));
    }

  


   if ($this->date_range){
		
		 $dates=explode( ' - ', $this->date_range);
		  $start= date("Y-m-d", strtotime($dates[0]) ); 
		  $end=date("Y-m-d", strtotime($dates[1]) ); 
		 if ((int)$dates[0] && $dates[0]!='1970-01-01'){
			 /*
		 $query ->andFilterWhere([
		 'between',
		 "date(date_of_application)", 
		 $start, $end
		 ]);
		 */
		 
		  $query ->andFilterWhere(['or', 
		   [ 'between', 'date(date_of_application)',  $start, $end], 
		   [ 'between', 'date(date_registered)',  $start, $end], 
		   ]);
		  }

	  }


       // $query->daterange($this->date_range);

        return $dataProvider;
    }
	
	
	
	
	
	
	
	
	
	
	 public function searchreport($params)
    {
        $query = Database::find();

        // add conditions that should always apply here
		
		if($this->load_params){
         $this->load($params);
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['priority_sector'=>SORT_ASC, 'created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
           // $query->where('0=1');
            return $dataProvider;
        }



       $dataProvider->sort->attributes['gender'] = [
            'asc' => ['male_active' => SORT_ASC],
            'desc' => ['male_active' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['age'] = [
            'asc' => ['female_active' => SORT_ASC],
            'desc' => ['female_active' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['status'] = [
            'asc' => ['active' => SORT_ASC],
            'desc' => ['active' => SORT_DESC],
        ];


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'system_id' => $this->system_id,
			'priority_sector' => $this->priority_sector,
			'gender' => $this->gender,
            'age' => $this->age,
            'date_of_birth' => $this->date_of_birth,
            'monthly_income' => $this->monthly_income,
            'date_registered' => $this->date_registered,
            'amount_of_pension' => $this->amount_of_pension,
            'relation_income' => $this->relation_income,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'date_of_application' => $this->date_of_application,
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);
        
		if($this->barangay=='none'){
			$query->andWhere("barangay is null or barangay='' ");
		}else{
			$query->andFilterWhere(['barangay'=>$this->barangay]);
		}
		
        $query->andFilterWhere(['like', 'sector_id', $this->sector_id])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'civil_status', $this->civil_status])
            ->andFilterWhere(['like', 'educ_attainment', $this->educ_attainment])
            ->andFilterWhere(['like', 'occupation', $this->occupation])
            ->andFilterWhere(['like', 'other_source_income', $this->other_source_income])
            ->andFilterWhere(['like', 'house_no', $this->house_no])
            ->andFilterWhere(['like', 'street', $this->street])
          
            ->andFilterWhere(['like', 'municipality', $this->municipality])
            ->andFilterWhere(['like', 'contact_no', $this->contact_no])
            ->andFilterWhere(['like', 'pensioner', $this->pensioner])
            ->andFilterWhere(['like', 'relation_where', $this->relation_where])
            ->andFilterWhere(['like', 'living_with_whom', $this->living_with_whom])
            ->andFilterWhere(['like', 'relation', $this->relation])
            ->andFilterWhere(['like', 'relation_occupation', $this->relation_occupation])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'pic_path', $this->pic_path])
            ->andFilterWhere(['like', 'shared_pic_path', $this->shared_pic_path])
            ->andFilterWhere(['like', 'encoded_by', $this->encoded_by])
            ->andFilterWhere(['like', 'edited_by', $this->edited_by])
            ->andFilterWhere(['like', 'skills', $this->skills])
            ->andFilterWhere(['like', 'client_category', $this->client_category])
            ->andFilterWhere(['like', 'reason1', $this->reason1])
            ->andFilterWhere(['like', 'reason2', $this->reason2])
            ->andFilterWhere(['like', 'reason3', $this->reason3])
            ->andFilterWhere(['like', 'birth_place', $this->birth_place])
            ->andFilterWhere(['like', 'birth_certificate', $this->birth_certificate])
            ->andFilterWhere(['like', 'ethnicity', $this->ethnicity])
            ->andFilterWhere(['like', 'source_of_income', $this->source_of_income])
            ->andFilterWhere(['like', 'slp_beneficiary', $this->slp_beneficiary])
            ->andFilterWhere(['like', 'religion', $this->religion])
            ->andFilterWhere(['like', 'mcct_beneficiary', $this->mcct_beneficiary])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'type_of_disability', $this->type_of_disability]);
        
                
        $query->andFilterWhere(['or', 
            ['like', 'system_id', $this->keywords],  
            ['like', 'priority_sector', $this->keywords],  
            ['like', 'sector_id', $this->keywords],  
            ['like', 'last_name', $this->keywords],  
            ['like', 'first_name', $this->keywords],  
            ['like', 'middle_name', $this->keywords],  
			
			['like', 'concat(last_name," ",first_name)', $this->keywords],  
            ['like', 'concat(first_name," ",last_name)', $this->keywords],  
            ['like', 'concat(first_name," ",middle_name," ",last_name)', $this->keywords],  
			
            ['like', 'gender', $this->keywords],  
            ['like', 'age', $this->keywords],  
            ['like', 'date_of_birth', $this->keywords],  
            ['like', 'civil_status', $this->keywords],  
            ['like', 'educ_attainment', $this->keywords],  
            ['like', 'occupation', $this->keywords],  
            ['like', 'monthly_income', $this->keywords],  
            ['like', 'other_source_income', $this->keywords],  
            ['like', 'house_no', $this->keywords],  
            ['like', 'street', $this->keywords],  
            ['like', 'barangay', $this->keywords],  
            ['like', 'municipality', $this->keywords],  
            ['like', 'date_registered', $this->keywords],  
            ['like', 'contact_no', $this->keywords],  
            ['like', 'pensioner', $this->keywords],  
            ['like', 'relation_where', $this->keywords],  
            ['like', 'amount_of_pension', $this->keywords],  
            ['like', 'living_with_whom', $this->keywords],  
            ['like', 'relation', $this->keywords],  
            ['like', 'relation_occupation', $this->keywords],  
            ['like', 'relation_income', $this->keywords],  
            ['like', 'pic_path', $this->keywords],  
            ['like', 'shared_pic_path', $this->keywords],  
            ['like', 'encoded_by', $this->keywords],  
            ['like', 'edited_by', $this->keywords],  
            ['like', 'skills', $this->keywords],  
            ['like', 'client_category', $this->keywords],  
            ['like', 'reason1', $this->keywords],  
            ['like', 'reason2', $this->keywords],  
            ['like', 'reason3', $this->keywords],  
            ['like', 'date_of_application', $this->keywords],  
            ['like', 'birth_place', $this->keywords],  
            ['like', 'birth_certificate', $this->keywords],  
            ['like', 'ethnicity', $this->keywords],  
            ['like', 'source_of_income', $this->keywords],  
            ['like', 'slp_beneficiary', $this->keywords],  
            ['like', 'religion', $this->keywords],  
            ['like', 'mcct_beneficiary', $this->keywords],  
            ['like', 'remarks', $this->keywords],  
            ['like', 'type_of_disability', $this->keywords],  
        ]);


        if ($this->date_range){
            $dates = explode( ' - ', $this->date_range);
            $start = date("Y-m-d", strtotime($dates[0]) ); 
            $end = date("Y-m-d", strtotime($dates[1]) ); 

            if ((int)$dates[0] && $dates[0]!='1970-01-01'){
                $query->andFilterWhere(['or', 
                    ['between', 'date(date_of_application)',  $start, $end], 
                    ['between', 'date(date_registered)',  $start, $end], 
                ]);
            }
        }
    		
		$query->select([
    		"priority_sector",
    		"count(*) as total_count",
    		"sum(status='Active') as active",
    		"sum(status='Inactive') as inactive",
    		"sum(gender='Male') as male",
    		"sum(gender='Male' and status='Active') as male_active",
    		"sum(gender='Male' and status='Inactive') as male_inactive",
    		"sum(gender='Female') as female",
    		"sum(gender='Female' and status='Active') as female_active",
    		"sum(gender='Female' and status='Inactive') as female_inactive",
		]);
		
        if ($this->withBaktom == false) {
            $query->andWhere(['<>', 'priority_sector', Database::baktomId()]);
        }

        $query->groupBy(['priority_sector']);

        if(!isset($_GET['sort'])) {
            $query->orderBy(['active'=>SORT_DESC]);
        }

        return $dataProvider;
    }
	
	
	
	
	
	 public function searchreportbarangay($params, $priority_sector)
    {
        $query = Database::find();

        // add conditions that should always apply here
		
		if($this->load_params){
         $this->load($params);
		}

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['priority_sector'=>SORT_ASC, 'created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
           // $query->where('0=1');
            return $dataProvider;
        }



       $dataProvider->sort->attributes['gender'] = [
            'asc' => ['male_active' => SORT_ASC],
            'desc' => ['male_active' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['age'] = [
            'asc' => ['female_active' => SORT_ASC],
            'desc' => ['female_active' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['status'] = [
            'asc' => ['active' => SORT_ASC],
            'desc' => ['active' => SORT_DESC],
        ];


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'system_id' => $this->system_id,
			'priority_sector' => $this->priority_sector,
			'gender' => $this->gender,
            'age' => $this->age,
            'date_of_birth' => $this->date_of_birth,
            'monthly_income' => $this->monthly_income,
            'date_registered' => $this->date_registered,
            'amount_of_pension' => $this->amount_of_pension,
            'relation_income' => $this->relation_income,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'date_of_application' => $this->date_of_application,
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);
        
        $query->andFilterWhere(['like', 'sector_id', $this->sector_id])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'civil_status', $this->civil_status])
            ->andFilterWhere(['like', 'educ_attainment', $this->educ_attainment])
            ->andFilterWhere(['like', 'occupation', $this->occupation])
            ->andFilterWhere(['like', 'other_source_income', $this->other_source_income])
            ->andFilterWhere(['like', 'house_no', $this->house_no])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'barangay', $this->barangay])
            ->andFilterWhere(['like', 'municipality', $this->municipality])
            ->andFilterWhere(['like', 'contact_no', $this->contact_no])
            ->andFilterWhere(['like', 'pensioner', $this->pensioner])
            ->andFilterWhere(['like', 'relation_where', $this->relation_where])
            ->andFilterWhere(['like', 'living_with_whom', $this->living_with_whom])
            ->andFilterWhere(['like', 'relation', $this->relation])
            ->andFilterWhere(['like', 'relation_occupation', $this->relation_occupation])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'pic_path', $this->pic_path])
            ->andFilterWhere(['like', 'shared_pic_path', $this->shared_pic_path])
            ->andFilterWhere(['like', 'encoded_by', $this->encoded_by])
            ->andFilterWhere(['like', 'edited_by', $this->edited_by])
            ->andFilterWhere(['like', 'skills', $this->skills])
            ->andFilterWhere(['like', 'client_category', $this->client_category])
            ->andFilterWhere(['like', 'reason1', $this->reason1])
            ->andFilterWhere(['like', 'reason2', $this->reason2])
            ->andFilterWhere(['like', 'reason3', $this->reason3])
            ->andFilterWhere(['like', 'birth_place', $this->birth_place])
            ->andFilterWhere(['like', 'birth_certificate', $this->birth_certificate])
            ->andFilterWhere(['like', 'ethnicity', $this->ethnicity])
            ->andFilterWhere(['like', 'source_of_income', $this->source_of_income])
            ->andFilterWhere(['like', 'slp_beneficiary', $this->slp_beneficiary])
            ->andFilterWhere(['like', 'religion', $this->religion])
            ->andFilterWhere(['like', 'mcct_beneficiary', $this->mcct_beneficiary])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'type_of_disability', $this->type_of_disability]);
        
                
        $query->andFilterWhere(['or', 
            ['like', 'system_id', $this->keywords],  
            ['like', 'priority_sector', $this->keywords],  
            ['like', 'sector_id', $this->keywords],  
            ['like', 'last_name', $this->keywords],  
            ['like', 'first_name', $this->keywords],  
            ['like', 'middle_name', $this->keywords],  
			
			['like', 'concat(last_name," ",first_name)', $this->keywords],  
            ['like', 'concat(first_name," ",last_name)', $this->keywords],  
            ['like', 'concat(first_name," ",middle_name," ",last_name)', $this->keywords],  
			
            ['like', 'gender', $this->keywords],  
            ['like', 'age', $this->keywords],  
            ['like', 'date_of_birth', $this->keywords],  
            ['like', 'civil_status', $this->keywords],  
            ['like', 'educ_attainment', $this->keywords],  
            ['like', 'occupation', $this->keywords],  
            ['like', 'monthly_income', $this->keywords],  
            ['like', 'other_source_income', $this->keywords],  
            ['like', 'house_no', $this->keywords],  
            ['like', 'street', $this->keywords],  
            ['like', 'barangay', $this->keywords],  
            ['like', 'municipality', $this->keywords],  
            ['like', 'date_registered', $this->keywords],  
            ['like', 'contact_no', $this->keywords],  
            ['like', 'pensioner', $this->keywords],  
            ['like', 'relation_where', $this->keywords],  
            ['like', 'amount_of_pension', $this->keywords],  
            ['like', 'living_with_whom', $this->keywords],  
            ['like', 'relation', $this->keywords],  
            ['like', 'relation_occupation', $this->keywords],  
            ['like', 'relation_income', $this->keywords],  
            ['like', 'pic_path', $this->keywords],  
            ['like', 'shared_pic_path', $this->keywords],  
            ['like', 'encoded_by', $this->keywords],  
            ['like', 'edited_by', $this->keywords],  
            ['like', 'skills', $this->keywords],  
            ['like', 'client_category', $this->keywords],  
            ['like', 'reason1', $this->keywords],  
            ['like', 'reason2', $this->keywords],  
            ['like', 'reason3', $this->keywords],  
            ['like', 'date_of_application', $this->keywords],  
            ['like', 'birth_place', $this->keywords],  
            ['like', 'birth_certificate', $this->keywords],  
            ['like', 'ethnicity', $this->keywords],  
            ['like', 'source_of_income', $this->keywords],  
            ['like', 'slp_beneficiary', $this->keywords],  
            ['like', 'religion', $this->keywords],  
            ['like', 'mcct_beneficiary', $this->keywords],  
            ['like', 'remarks', $this->keywords],  
            ['like', 'type_of_disability', $this->keywords],  
        ]);


     if ($this->date_range){
		
		 $dates=explode( ' - ', $this->date_range);
		 $start= date("Y-m-d", strtotime($dates[0]) ); 
		 $end=date("Y-m-d", strtotime($dates[1]) ); 
		 if ((int)$dates[0] && $dates[0]!='1970-01-01'){
			 /*
		 $query ->andFilterWhere([
		 'between',
		 "date(date_of_application)", 
		 $start, $end
		 ]);
		 */
		 
		  $query ->andFilterWhere(['or', 
		  [ 'between', 'date(date_of_application)',  $start, $end], 
		  [ 'between', 'date(date_registered)',  $start, $end], 
		  ]);
		 }

	   }



       // $query->daterange($this->date_range);
	  // $priority_sector = Database::priorityReIndex();
	   $priority=[];
	    foreach($priority_sector as $key=>$row){
			array_push($priority,
	       "sum(status='Active' and priority_sector=".$row['id'].") as ".$row['id']."_active",
		   "sum(status='Active' and gender='Male' and priority_sector=".$row['id'].") as ".$row['id']."_active_male",
		   "sum(status='Active' and gender='Female' and priority_sector=".$row['id'].") as ".$row['id']."_active_female",
		  );
		
		}
		
		//print_r($priority);
		//exit;
		
		
		$query->select(array_merge([
		"barangay",
		"count(*) as total_count",
		"sum(status='Active') as active",
		"sum(gender='Male' and status='Active') as active_male",
		"sum(gender='Female' and status='Active') as active_female",

		], $priority) );
		
		$query->groupBy(['barangay']);
		$query->having(" active>0");
		
		
		
		if(!isset($_GET['sort'])){
		 $query->orderBy(['active'=>SORT_DESC]);
		}

        return $dataProvider;
    }
	
	
	public function getDataReport($dataProvider)
    {
        $data_report = $dataProvider->getModels();
        return ($data_report)? ArrayHelper::index($data_report, 'priority_sector'): [];
    }
	
}