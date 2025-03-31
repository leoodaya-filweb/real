<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\TechIssue;
use app\helpers\App;

/**
 * TechIssueSearch represents the model behind the search form of `app\models\TechIssue`.
 */
class TechIssueSearch extends TechIssue
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'tech-issue/_search';
    public $searchAction = ['tech-issue/index'];
    public $searchLabel = 'Technical Issue';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_by', 'updated_by'], 'integer'],
            [['steps', 'description', 'photos', 'ip', 'browser', 'os', 'device', 'token', 'created_at', 'updated_at', 'type', 'status', ], 'safe'],
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
        $query = TechIssue::find()
            ->alias('t')
            ->joinWith('user u')
            ->groupBy('t.id');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        $dataProvider->sort->attributes['userEmail'] = [
            'asc' => ['u.email' => SORT_ASC],
            'desc' => ['u.email' => SORT_DESC],
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            't.id' => $this->id,
            't.user_id' => $this->user_id,
            't.type' => $this->type,
            't.status' => $this->status,
            't.record_status' => $this->record_status,
            't.created_by' => $this->created_by,
            't.updated_by' => $this->updated_by,
            't.created_at' => $this->created_at,
            't.updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['or', 
            ['like', 'u.email', $this->keywords],  
            ['like', 't.description', $this->keywords],  
            ['like', 't.ip', $this->keywords],  
            ['like', 't.browser', $this->keywords],  
            ['like', 't.os', $this->keywords],  
            ['like', 't.device', $this->keywords],  
            ['like', 't.token', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}