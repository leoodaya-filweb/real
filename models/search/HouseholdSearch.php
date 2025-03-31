<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Household;
use app\helpers\App;

/**
 * HouseholdSearch represents the model behind the search form of `app\models\Household`.
 */
class HouseholdSearch extends Household
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'household/_search';
    public $searchAction = ['household/index'];
    public $searchLabel = 'Household';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'no', 'region_id', 'province_id', 'municipality_id', 'zone_no', 'purok_no', 'created_by', 'updated_by'], 'integer'],
            [['transfer_date', 'longitude', 'latitude', 'altitude', 'street', 'created_at', 'updated_at', 'blk_no'], 'safe'],
            [['keywords', 'pagination', 'date_range', 'record_status', 'barangay_id'], 'safe'],
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
        $query = Household::find()
            ->alias('h')
            ->joinWith('barangay b')
            ->joinWith('members m')
            ->groupBy('h.id');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           // 'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        $dataProvider->sort->attributes['headerName'] = [
            'asc' => ['m.first_name' => SORT_ASC],
            'desc' => ['m.first_name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['totalMembers'] = [
            'asc' => ['COUNT("m.*")' => SORT_ASC],
            'desc' => ['COUNT("m.*")' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['barangayName'] = [
            'asc' => ['b.name' => SORT_ASC],
            'desc' => ['b.name' => SORT_DESC],
        ];


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'h.id' => $this->id,
            'h.no' => $this->no,
            'h.region_id' => $this->region_id,
            'h.province_id' => $this->province_id,
            'h.municipality_id' => $this->municipality_id,
            'h.zone_no' => $this->zone_no,
            'h.barangay_id' => $this->barangay_id,
            'h.purok_no' => $this->purok_no,
            'h.record_status' => $this->record_status,
            'h.created_by' => $this->created_by,
            'h.updated_by' => $this->updated_by,
            'h.created_at' => $this->created_at,
            'h.updated_at' => $this->updated_at,
            'h.new_cbms'=>1  //(strpos($_SERVER['REQUEST_URI'], "demo") == true?1:null)
        ]);
                
        $query->andFilterWhere(['or', 
            ['like', 'b.name', $this->keywords],  
            ['like', 'h.no', $this->keywords],  
            // ['like', 'h.transfer_date', $this->keywords],  
            // ['like', 'h.longitude', $this->keywords],  
            // ['like', 'h.latitude', $this->keywords],  
            // ['like', 'h.altitude', $this->keywords],  
            // ['like', 'h.region_id', $this->keywords],  
            // ['like', 'h.province_id', $this->keywords],  
            // ['like', 'h.municipality_id', $this->keywords],  
            ['like', 'h.zone_no', $this->keywords],  
            // ['like', 'h.barangay_id', $this->keywords],  
            ['like', 'h.purok_no', $this->keywords],  
            ['like', 'h.blk_no', $this->keywords],  
            ['like', 'h.lot_no', $this->keywords],  
            ['like', 'h.street', $this->keywords],
            
            ['like', 'CONCAT(m.first_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.first_name)', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.middle_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.middle_name, " ", m.first_name)', $this->keywords]
        ]);

        $query->daterange($this->date_range);
        
        if(!Yii::$app->request->get('sort')){
         $query->orderBy(['h.no' => SORT_DESC]);
        }

        return $dataProvider;
    }
}