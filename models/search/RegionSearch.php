<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Region;
use app\helpers\App;

/**
 * RegionSearch represents the model behind the search form of `app\models\Region`.
 */
class RegionSearch extends Region
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'region/_search';
    public $searchAction = ['region/index'];
    public $searchLabel = 'Region';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'country_id', 'no', 'created_by', 'updated_by'], 'integer'],
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
        $query = Region::find()
            ->alias('r')
            ->joinWith('country c')
            ->groupBy('r.id');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        $dataProvider->sort->attributes['countryName'] = [
            'asc' => ['c.name' => SORT_ASC],
            'desc' => ['c.name' => SORT_DESC],
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'r.id' => $this->id,
            'r.country_id' => $this->country_id,
            'r.no' => $this->no,
            'r.record_status' => $this->record_status,
            'r.created_by' => $this->created_by,
            'r.updated_by' => $this->updated_by,
            'r.created_at' => $this->created_at,
            'r.updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['or', 
            ['like', 'c.name', $this->keywords],
            ['like', 'r.name', $this->keywords],  
            ['like', 'r.no', $this->keywords],
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}