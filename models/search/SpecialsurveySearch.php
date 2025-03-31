<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Specialsurvey;
use app\models\form\setting\AddressSettingForm;
use app\helpers\App;

/**
 * SpecialsurveySearch represents the model behind the search form of `app\models\Specialsurvey`.
 */
class SpecialsurveySearch extends Specialsurvey
{
    public $groupPurok;
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'specialsurvey/_search';
    public $searchAction = ['specialsurvey/index'];
    public $searchLabel = 'Survey';
    
    public $criteria;
    public $color_survey;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'age', 'criteria1_color_id', 'criteria2_color_id', 'criteria3_color_id', 'criteria4_color_id', 'criteria5_color_id', 'created_by', 'updated_by'], 'integer'],
            [['last_name', 'first_name', 'middle_name', 'gender', 'date_of_birth', 'civil_status', 'house_no', 'sitio', 'barangay', 'municipality', 'province', 'religion', 'date_survey', 'remarks', 'status', 'created_at', 'updated_at', 'groupPurok', 'purok','precinct_no'], 'safe'],
            [['keywords', 'pagination', 'date_range', 'record_status', 'survey_name', 'household_no','criteria','color_survey'], 'safe'],
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
        $query = Specialsurvey::find()->alias('t');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['barangay'=> SORT_ASC, 'precinct_no'=> SORT_ASC,  'last_name'=>SORT_ASC,  'created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }   

        // grid filtering conditions
        $query->andFilterWhere([
            't.id' => $this->id,
            't.age' => $this->age,
            't.date_of_birth' => $this->date_of_birth,
            't.criteria1_color_id' => $this->criteria1_color_id,
            't.criteria2_color_id' => $this->criteria2_color_id,
            't.criteria3_color_id' => $this->criteria3_color_id,
            't.criteria4_color_id' => $this->criteria4_color_id,
            't.criteria5_color_id' => $this->criteria5_color_id,
            't.date_survey' => $this->date_survey,
            't.survey_name' => $this->survey_name,
            't.household_no' => $this->household_no,
            't.barangay'=>$this->barangay,  
            't.precinct_no'=>$this->precinct_no,
			't.purok'=>trim($this->purok),  
			't.gender'=>$this->gender,  
            't.record_status' => $this->record_status,
            't.created_by' => $this->created_by,
            't.created_at' => $this->created_at,
            't.updated_by' => $this->updated_by,
            't.updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['like', 't.last_name', $this->last_name])
            ->andFilterWhere(['like', 't.first_name', $this->first_name])
            ->andFilterWhere(['like', 't.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 't.gender', $this->gender])
            ->andFilterWhere(['like', 't.civil_status', $this->civil_status])
            ->andFilterWhere(['like', 't.house_no', $this->house_no])
            ->andFilterWhere(['like', 't.sitio', $this->sitio])
            ->andFilterWhere(['like', 't.barangay', $this->barangay])
            ->andFilterWhere(['like', 't.municipality', $this->municipality])
            ->andFilterWhere(['like', 't.province', $this->province])
            ->andFilterWhere(['like', 't.religion', $this->religion])
            ->andFilterWhere(['like', 't.remarks', $this->remarks]);
        
                
        $query->andFilterWhere(['or', 
            ['like', 't.last_name', $this->keywords],  
            ['like', 't.first_name', $this->keywords],  
            ['like', 't.middle_name', $this->keywords],  
           // ['like', 'gender', $this->keywords],  
            ['like', 't.age', $this->keywords],  
            ['like', 't.date_of_birth', $this->keywords],  
            ['like', 't.civil_status', $this->keywords],  
            ['like', 't.house_no', $this->keywords],  
            ['like', 't.sitio', $this->keywords],  
           // ['like', 'barangay', $this->keywords],  
            ['like', 't.municipality', $this->keywords],  
            ['like', 't.province', $this->keywords],  
            ['like', 't.religion', $this->keywords],  
            ['like', 't.date_survey', $this->keywords],  
            ['like', 't.remarks', $this->keywords],  
            //['like', 'house_no', $this->keywords],  
            ['like', 'hs.no', $this->keywords],  
            ['like', 't.survey_name', $this->keywords],  
            ['like', 'CONCAT(t.first_name, " ", t.last_name)', $this->keywords],  
            ['like', 'CONCAT(t.last_name, " ", t.first_name)', $this->keywords],  
            ['like', 'CONCAT(t.first_name, " ", t.middle_name, " ", t.last_name)', $this->keywords],  
            ['like', 'CONCAT(t.last_name, " ", t.middle_name, " ", t.first_name)', $this->keywords] ,  
        ]);
        
        
       $query->leftJoin('{{%households}} hs', "hs.no=t.household_no");

        $query->daterange($this->date_range);

        return $dataProvider;
    }
    
    
    
    
   public function searchvoters($params)
    {
        $query = Specialsurvey::find()->alias('t');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           // 'sort' => ['defaultOrder' => ['t.barangay'=> SORT_ASC, 't.purok'=> SORT_ASC, 't.created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        
        $query->select(['t.first_name','t.middle_name', 't.last_name', 't.household_no',
        't.criteria1_color_id','t.criteria2_color_id', 't.criteria3_color_id', 't.criteria3_color_id', 't.criteria5_color_id', 
        'count(*) as total_voters', 'hs.longitude','hs.latitude'
        ]);
        
        $query->andFilterWhere([
            't.criteria1_color_id' => $this->criteria1_color_id,
            't.criteria2_color_id' => $this->criteria2_color_id,
            't.criteria3_color_id' => $this->criteria3_color_id,
            't.criteria4_color_id' => $this->criteria4_color_id,
            't.criteria5_color_id' => $this->criteria5_color_id,
            't.date_survey' => $this->date_survey,
            't.survey_name' => $this->survey_name,
            't.household_no' => $this->household_no,
            't.barangay'=>$this->barangay,  
			't.purok'=>trim($this->purok),  

        ]);
        
        
        $query->andFilterWhere(['or', 
            ['like', 't.last_name', $this->keywords],  
            ['like', 't.first_name', $this->keywords],  
            ['like', 't.middle_name', $this->keywords],  
            ['like', 't.religion', $this->keywords], 
            ['like', 'hs.no', $this->keywords],  
            ['like', 'CONCAT(t.first_name, " ", t.last_name)', $this->keywords],  
            ['like', 'CONCAT(t.last_name, " ", t.first_name)', $this->keywords],  
            ['like', 'CONCAT(t.first_name, " ", t.middle_name, " ", t.last_name)', $this->keywords],  
            ['like', 'CONCAT(t.last_name, " ", t.middle_name, " ", t.first_name)', $this->keywords],  
        ]);
        
        $query->leftJoin('{{%households}} hs', "hs.no=t.household_no");
        
        //$query->leftJoin('(select m.*, ts.total_trans from {{%members}} m left join (select member_id, count(*) as total_trans from {{%transactions}} group by member_id ) ts on ts.member_id=m.id  group by m.household_id) m', "m.household_id=hs.id");
       // $query->having("sum(m.total_trans)>1");

        $query->andWhere("hs.longitude>0");
        
        $query->groupBy("t.household_no");

        $query->daterange($this->date_range);
        $query->asArray();

        return $dataProvider;
    }

    public function searchsummary($params)
    {
        $query = Specialsurvey::find()->alias('t');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort' => ['defaultOrder' => ['barangay'=> SORT_ASC, 'purok'=> SORT_ASC, 'created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            't.id' => $this->id,
            't.age' => $this->age,
            't.date_of_birth' => $this->date_of_birth,
            't.criteria1_color_id' => $this->criteria1_color_id,
            't.criteria2_color_id' => $this->criteria2_color_id,
            't.criteria3_color_id' => $this->criteria3_color_id,
            't.criteria4_color_id' => $this->criteria4_color_id,
            't.criteria5_color_id' => $this->criteria5_color_id,
            't.barangay'=>$this->barangay,  
			't.purok'=>trim($this->purok),   
            't.survey_name' => $this->survey_name,
            't.household_no' => $this->household_no,
            't.record_status' => $this->record_status,
            't.created_by' => $this->created_by,
            't.created_at' => $this->created_at,
            't.updated_by' => $this->updated_by,
            't.updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['like', 't.last_name', $this->last_name])
            ->andFilterWhere(['like', 't.first_name', $this->first_name])
            ->andFilterWhere(['like', 't.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 't.gender', $this->gender])
            ->andFilterWhere(['like', 't.civil_status', $this->civil_status])
            ->andFilterWhere(['like', 't.house_no', $this->house_no])
            ->andFilterWhere(['like', 't.sitio', $this->sitio])
            ->andFilterWhere(['like', 't.barangay', $this->barangay])
            ->andFilterWhere(['like', 't.municipality', $this->municipality])
            ->andFilterWhere(['like', 't.province', $this->province])
            ->andFilterWhere(['like', 't.religion', $this->religion])
            ->andFilterWhere(['like', 't.remarks', $this->remarks]);
                
        $query->andFilterWhere(['or', 
            ['like', 't.last_name', $this->keywords],  
            ['like', 't.first_name', $this->keywords],  
            ['like', 't.middle_name', $this->keywords],  
            ['like', 't.gender', $this->keywords],  
            ['like', 't.age', $this->keywords],  
            ['like', 't.date_of_birth', $this->keywords],  
            ['like', 't.civil_status', $this->keywords],  
            ['like', 't.house_no', $this->keywords],  
            ['like', 't.sitio', $this->keywords],  
            ['like', 't.barangay', $this->keywords],  
            ['like', 't.municipality', $this->keywords],  
            ['like', 't.province', $this->keywords],  
            ['like', 't.religion', $this->keywords],   
            ['like', 't.remarks', $this->keywords],  
            ['like', 't.house_no', $this->keywords],  
            ['like', 'survey_name', $this->keywords],  
            ['like', 'CONCAT(t.first_name, " ", t.last_name)', $this->keywords],  
            ['like', 'CONCAT(t.last_name, " ", t.first_name)', $this->keywords],  
            ['like', 'CONCAT(t.first_name, " ", t.middle_name, " ", t.last_name)', $this->keywords],  
            ['like', 'CONCAT(t.last_name, " ", t.middle_name, " ", t.first_name)', $this->keywords]
                ,  
        ]);
		
		$query->select([
    		"t.barangay",
    		"t.purok",
    		"sum(t.criteria1_color_id=1) as criteria1_color_black",
    		"sum(t.criteria1_color_id=2) as criteria1_color_gray",
    		"sum(t.criteria1_color_id=3) as criteria1_color_green",
    		"sum(t.criteria1_color_id=4) as criteria1_color_red",
    		
    		"sum(t.criteria2_color_id=1) as criteria2_color_black",
    		"sum(t.criteria2_color_id=2) as criteria2_color_gray",
    		"sum(t.criteria2_color_id=3) as criteria2_color_green",
    		"sum(t.criteria2_color_id=4) as criteria2_color_red",
    		
    		"sum(t.criteria3_color_id=1) as criteria3_color_black",
    		"sum(t.criteria3_color_id=2) as criteria3_color_gray",
    		"sum(t.criteria3_color_id=3) as criteria3_color_green",
    		"sum(t.criteria3_color_id=4) as criteria3_color_red",
    		
    		"sum(t.criteria4_color_id=1) as criteria4_color_black",
    		"sum(t.criteria4_color_id=2) as criteria4_color_gray",
    		"sum(t.criteria4_color_id=3) as criteria4_color_green",
    		"sum(t.criteria4_color_id=4) as criteria4_color_red",
    		
    		"sum(t.criteria5_color_id=1) as criteria5_color_black",
    		"sum(t.criteria5_color_id=2) as criteria5_color_gray",
    		"sum(t.criteria5_color_id=3) as criteria5_color_green",
    		"sum(t.criteria5_color_id=4) as criteria5_color_red",
		]);

		if(($this->barangay && $this->purok) || $this->groupPurok) {
            $query->groupby(["t.barangay", "t.purok"]);	
		}
        else {
            $query->groupby(["t.barangay"]);
		}



        //$query->leftJoin('{{%households}} hs', "hs.no=t.household_no");

       
        $query->orderBy(['t.barangay'=> SORT_ASC, 't.purok'=> SORT_ASC, 't.created_at' => SORT_DESC]);
        $query->daterange($this->date_range);
		$query->asArray();

        return $dataProvider;
    }
	

    public function getRowSummary($dataProvider)
    {
        $from = $dataProvider->query->createCommand()->rawSql;

        $rowsummary = Yii::$app->db->createCommand(
            "SELECT
                sum(criteria1_color_black) as criteria1_color_black_total,
                sum(criteria1_color_gray) as criteria1_color_gray_total,
                sum(criteria1_color_green) as criteria1_color_green_total,
                sum(criteria1_color_red) as criteria1_color_red_total,
                
                sum(criteria2_color_black) as criteria2_color_black_total,
                sum(criteria2_color_gray) as criteria2_color_gray_total,
                sum(criteria2_color_green) as criteria2_color_green_total,
                sum(criteria2_color_red) as criteria2_color_red_total,
                
                sum(criteria3_color_black) as criteria3_color_black_total,
                sum(criteria3_color_gray) as criteria3_color_gray_total,
                sum(criteria3_color_green) as criteria3_color_green_total,
                sum(criteria3_color_red) as criteria3_color_red_total,
                
                sum(criteria4_color_black) as criteria4_color_black_total,
                sum(criteria4_color_gray) as criteria4_color_gray_total,
                sum(criteria4_color_green) as criteria4_color_green_total,
                sum(criteria4_color_red) as criteria4_color_red_total,
                
                sum(criteria5_color_black) as criteria5_color_black_total,
                sum(criteria5_color_gray) as criteria5_color_gray_total,
                sum(criteria5_color_green) as criteria5_color_green_total,
                sum(criteria5_color_red) as criteria5_color_red_total
            FROM ($from) sc"
        )
        ->queryOne();

        return $rowsummary;
    }
}