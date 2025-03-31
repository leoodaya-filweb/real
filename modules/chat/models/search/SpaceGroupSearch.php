<?php

namespace app\modules\chat\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\chat\models\SpaceGroup;
use app\modules\chat\helpers\App;

/**
 * SpaceGroupSearch represents the model behind the search form of `app\modules\chat\models\SpaceGroup`.
 */
class SpaceGroupSearch extends SpaceGroup
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'space-group/_search';
    public $searchAction = ['space-group/index'];
    public $searchLabel = 'SpaceGroup';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'space_id', 'user_id', 'created_by', 'updated_by'], 'integer'],
            [['token', 'created_at', 'updated_at'], 'safe'],
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
        $query = SpaceGroup::find();

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
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
            'id' => $this->id,
            'space_id' => $this->space_id,
            'user_id' => $this->user_id,
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['like', 'token', $this->token]);
        
                
        $query->andFilterWhere(['or', 
            ['like', 'space_id', $this->keywords],  
            ['like', 'user_id', $this->keywords],  
            ['like', 'token', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}