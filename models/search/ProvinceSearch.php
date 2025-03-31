<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Province;
use app\helpers\App;

/**
 * ProvinceSearch represents the model behind the search form of `app\models\Province`.
 */
class ProvinceSearch extends Province
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'province/_search';
    public $searchAction = ['province/index'];
    public $searchLabel = 'Province';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'region_id', 'no', 'created_by', 'updated_by'], 'integer'],
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
        $query = Province::find()
            ->alias('p')
            ->joinWith('region r')
            ->groupBy('p.id');

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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'p.id' => $this->id,
            'p.region_id' => $this->region_id,
            'p.no' => $this->no,
            'p.record_status' => $this->record_status,
            'p.created_by' => $this->created_by,
            'p.updated_by' => $this->updated_by,
            'p.created_at' => $this->created_at,
            'p.updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['or', 
            ['like', 'r.name', $this->keywords],  
            ['like', 'p.name', $this->keywords],  
            ['like', 'p.no', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}