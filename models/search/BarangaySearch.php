<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Barangay;
use app\helpers\App;

/**
 * BarangaySearch represents the model behind the search form of `app\models\Barangay`.
 */
class BarangaySearch extends Barangay
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'barangay/_search';
    public $searchAction = ['barangay/index'];
    public $searchLabel = 'Barangay';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'municipality_id', 'no', 'created_by', 'updated_by'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'safe'],
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
        $query = Barangay::find()
            ->alias('b')
            ->joinWith(['region r', 'province p', 'municipality m'])
            ->groupBy('b.id');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        $dataProvider->sort->attributes['regionName'] = [
            'asc' => ['r.name' => SORT_ASC],
            'desc' => ['r.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['provinceName'] = [
            'asc' => ['p.name' => SORT_ASC],
            'desc' => ['p.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['municipalityName'] = [
            'asc' => ['m.name' => SORT_ASC],
            'desc' => ['m.name' => SORT_DESC],
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'no' => $this->no,
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['or', 
            ['like', 'p.name', $this->keywords],  
            ['like', 'r.name', $this->keywords],  
            ['like', 'm.name', $this->keywords],  
            ['like', 'b.name', $this->keywords],  
            ['like', 'b.no', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}