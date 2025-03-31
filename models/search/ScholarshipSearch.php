<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Scholarship;
use app\helpers\App;

/**
 * ScholarshipSearch represents the model behind the search form of `app\models\Scholarship`.
 */
class ScholarshipSearch extends Scholarship
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'scholarship/_search';
    public $searchAction = ['scholarship/index'];
    public $searchLabel = 'Scholarship';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'age', 'barangay_id', 'first_enrollment', 'expected_graduation', 'current_year_level', 'created_by', 'updated_by'], 'integer'],
            [['first_name', 'middle_name', 'last_name', 'name_suffix', 'birth_date', 'course', 'street_address', 'email', 'alternate_email', 'contact_no', 'alternate_contact_no', 'house_no', 'guardian', 'school_name', 'subjects', 'units', 'documents', 'photo', 'created_at', 'updated_at'], 'safe'],
            [['keywords', 'pagination', 'date_range', 'record_status', 'status'], 'safe'],
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
        $query = Scholarship::find()
            ->alias('s')
            ->joinWith('barangay b')
            ->groupBy('s.id');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        $dataProvider->sort->attributes['barangayName'] = [
            'asc' => ['b.name' => SORT_ASC],
            'desc' => ['b.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['fullname'] = [
            'asc' => ['CONCAT(s.first_name, " ", s.middle_name, " ", s.last_name, " ", s.name_suffix)' => SORT_ASC],
            'desc' => ['CONCAT(s.first_name, " ", s.middle_name, " ", s.last_name, " ", s.name_suffix)' => SORT_DESC],
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            's.id' => $this->id,
            's.birth_date' => $this->birth_date,
            // 's.age' => $this->age,
            's.barangay_id' => $this->barangay_id,
            's.first_enrollment' => $this->first_enrollment,
            's.expected_graduation' => $this->expected_graduation,
            's.current_year_level' => $this->current_year_level,
            's.record_status' => $this->record_status,
            's.created_by' => $this->created_by,
            's.updated_by' => $this->updated_by,
            's.created_at' => $this->created_at,
            's.updated_at' => $this->updated_at,
            's.status' => $this->status,
        ]);
                
        $query->andFilterWhere(['or', 
            ['like', 's.first_name', $this->keywords],  
            ['like', 's.middle_name', $this->keywords],  
            ['like', 's.last_name', $this->keywords],  
            ['like', 's.name_suffix', $this->keywords],  
            ['like', 's.course', $this->keywords],  
            // ['like', 's.name', $this->keywords],  
            ['like', 's.street_address', $this->keywords],  
            ['like', 's.email', $this->keywords],  
            ['like', 's.alternate_email', $this->keywords],  
            ['like', 's.contact_no', $this->keywords],  
            ['like', 's.alternate_contact_no', $this->keywords],  
            ['like', 's.house_no', $this->keywords],  
            ['like', 's.guardian', $this->keywords],  
            ['like', 's.school_name', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}