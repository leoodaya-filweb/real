<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Budget;
use app\helpers\App;

/**
 * BudgetSearch represents the model behind the search form of `app\models\Budget`.
 */
class BudgetSearch extends Budget
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'budget/_search';
    public $searchAction = ['budget/index'];
    public $searchLabel = 'Budget';


    public $createdByEmail;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'created_by', 'updated_by'], 'integer'],
            [['year', 'created_at', 'updated_at', 'action', 'specific_to', 'model_id'], 'safe'],
            [['budget'], 'number'],
            [['keywords', 'pagination', 'date_range', 'record_status', 'createdByEmail'], 'safe'],
            [['keywords'], 'trim'],
        ];
    }

    public function init()
    {
        $this->pagination = App::setting('system')->pagination;
    }

    public function setToCurrentYear()
    {
        $this->year = $this->currentYear;
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
        $query = Budget::find()
            ->alias('b')
            ->joinWith('createdBy u');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        $dataProvider->sort->attributes['createdByEmail'] = [
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
            'b.id' => $this->id,
            'b.action' => $this->action,
            'b.type' => $this->type,
            'b.budget' => $this->budget,
            'b.record_status' => $this->record_status,
            'b.created_by' => $this->created_by,
            'b.updated_by' => $this->updated_by,
            'b.created_at' => $this->created_at,
            'b.updated_at' => $this->updated_at,
            'b.year' => $this->year,
        ]);
        
        $query->andFilterWhere(['or', 
            ['like', 'b.type', $this->keywords],  
            ['like', 'b.budget', $this->keywords],  
            ['like', 'u.email', $this->createdByEmail],  

        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}