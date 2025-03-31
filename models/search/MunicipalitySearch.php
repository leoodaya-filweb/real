<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Municipality;
use app\helpers\App;

/**
 * MunicipalitySearch represents the model behind the search form of `app\models\Municipality`.
 */
class MunicipalitySearch extends Municipality
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'municipality/_search';
    public $searchAction = ['municipality/index'];
    public $searchLabel = 'Municipality';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'province_id', 'no', 'created_by', 'updated_by'], 'integer'],
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
        $query = Municipality::find()
            ->alias('m')
            ->joinWith(['region r', 'province p'])
            ->groupBy('m.id');

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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'm.id' => $this->id,
            'm.province_id' => $this->province_id,
            'm.no' => $this->no,
            'm.record_status' => $this->record_status,
            'm.created_by' => $this->created_by,
            'm.updated_by' => $this->updated_by,
            'm.created_at' => $this->created_at,
            'm.updated_at' => $this->updated_at,
        ]);
                
        $query->andFilterWhere(['or', 
            ['like', 'p.name', $this->keywords],  
            ['like', 'r.name', $this->keywords],  
            ['like', 'm.name', $this->keywords],  
            ['like', 'm.no', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}