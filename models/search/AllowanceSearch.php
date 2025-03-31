<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Allowance;
use app\helpers\App;

/**
 * AllowanceSearch represents the model behind the search form of `app\models\Allowance`.
 */
class AllowanceSearch extends Allowance
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'allowance/_search';
    public $searchAction = ['allowance/index'];
    public $searchLabel = 'Allowance';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'scholarship_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['semester', 'documents', 'remarks', 'token', 'date', 'created_at', 'updated_at'], 'safe'],
            [['amount'], 'number'],
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
        $query = Allowance::find();

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
            'scholarship_id' => $this->scholarship_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'date' => $this->date,
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['like', 'semester', $this->semester])
            ->andFilterWhere(['like', 'documents', $this->documents])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'token', $this->token]);
        
                
        $query->andFilterWhere(['or', 
            ['like', 'scholarship_id', $this->keywords],  
            ['like', 'semester', $this->keywords],  
            ['like', 'amount', $this->keywords],  
            ['like', 'documents', $this->keywords],  
            ['like', 'remarks', $this->keywords],  
            ['like', 'token', $this->keywords],  
            ['like', 'date', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}