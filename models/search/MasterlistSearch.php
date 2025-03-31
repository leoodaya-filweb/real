<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Masterlist;
use app\helpers\App;

/**
 * MasterlistSearch represents the model behind the search form of `app\models\Masterlist`.
 */
class MasterlistSearch extends Masterlist
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'masterlist/_search';
    public $searchAction = ['masterlist/index'];
    public $searchLabel = 'Masterlist';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sex', 'age', 'civil_status', 'created_by', 'updated_by'], 'integer'],
            [['qr_id', 'last_name', 'middle_name', 'first_name', 'name_suffix', 'birth_date', 'birth_place', 'email', 'contact_no', 'other_contact_no', 'house_no', 'street', 'barangay', 'sitio', 'purok', 'educational_attainment', 'occupation', 'source_of_income', 'date_registered', 'photo', 'documents', 'created_at', 'updated_at'], 'safe'],
            [['income', 'pwd_score', 'senior_score', 'solo_parent_score', 'solo_member_score', 'accessibility_score'], 'number'],
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
        $query = Masterlist::find()
            ->select(['*', '(pwd_score + senior_score + solo_parent_score + solo_member_score + accessibility_score) as priority_score']);

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'sort' => [
            //     'defaultOrder' => [
            //         'priority_score' => SORT_DESC,
            //         'created_at' => SORT_DESC
            //     ]
            // ],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        $dataProvider->sort->attributes['priority_score'] = [
            'asc' => ['priority_score' => SORT_ASC],
            'desc' => ['priority_score' => SORT_DESC],
        ];


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'sex' => $this->sex,
            'age' => $this->age,
            'birth_date' => $this->birth_date,
            'civil_status' => $this->civil_status,
            'income' => $this->income,
            'date_registered' => $this->date_registered,
            'pwd_score' => $this->pwd_score,
            'senior_score' => $this->senior_score,
            'solo_parent_score' => $this->solo_parent_score,
            'solo_member_score' => $this->solo_member_score,
            'accessibility_score' => $this->accessibility_score,
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'qr_id', $this->qr_id])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'name_suffix', $this->name_suffix])
            ->andFilterWhere(['like', 'birth_place', $this->birth_place])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'contact_no', $this->contact_no])
            ->andFilterWhere(['like', 'other_contact_no', $this->contact_no])
            ->andFilterWhere(['like', 'house_no', $this->house_no])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'barangay', $this->barangay])
            ->andFilterWhere(['like', 'sitio', $this->sitio])
            ->andFilterWhere(['like', 'purok', $this->purok])
            ->andFilterWhere(['like', 'educational_attainment', $this->educational_attainment])
            ->andFilterWhere(['like', 'occupation', $this->occupation])
            ->andFilterWhere(['like', 'source_of_income', $this->source_of_income])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'documents', $this->documents]);


        
                
        $query->andFilterWhere(['or', 
            ['like', 'qr_id', $this->keywords],  
            ['like', 'last_name', $this->keywords],  
            ['like', 'middle_name', $this->keywords],  
            ['like', 'first_name', $this->keywords],  
            ['like', 'name_suffix', $this->keywords],  
            ['like', 'email', $this->keywords],  
            ['like', 'contact_no', $this->keywords], 
            ['like', 'other_contact_no', $this->keywords], 
            ['like', 'house_no', $this->keywords],  
            ['like', 'street', $this->keywords],  
            ['like', 'barangay', $this->keywords],  
            ['like', 'sitio', $this->keywords],  
            ['like', 'purok', $this->keywords],  
            ['like', 'educational_attainment', $this->keywords],  
            ['like', 'occupation', $this->keywords],  
            ['like', 'source_of_income', $this->keywords],  

            ['like', 'CONCAT(first_name, " ", last_name)', $this->keywords],  
            ['like', 'CONCAT(last_name, " ", first_name)', $this->keywords],  
            ['like', 'CONCAT(first_name, " ", middle_name, " ", last_name)', $this->keywords],  
            ['like', 'CONCAT(last_name, " ", middle_name, " ", first_name)', $this->keywords]
                ,  
        ]);

        $query->daterange($this->date_range);
        $query->orderBy(['priority_score' => SORT_DESC]);

        return $dataProvider;
    }
}