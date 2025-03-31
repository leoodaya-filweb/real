<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Email;
use app\helpers\App;

/**
 * EmailSearch represents the model behind the search form of `app\models\Email`.
 */
class EmailSearch extends Email
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'email/_search';
    public $searchAction = ['email/index'];
    public $searchLabel = 'Email';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by'], 'integer'],
            [['to', 'from_email', 'from_name', 'subject', 'body', 'created_at', 'updated_at'], 'safe'],
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
        $query = Email::find();

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
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['like', 'to', $this->to])
            ->andFilterWhere(['like', 'from_email', $this->from_email])
            ->andFilterWhere(['like', 'from_name', $this->from_name])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'body', $this->body]);
        
                
        $query->andFilterWhere(['or', 
            ['like', 'to', $this->keywords],  
            ['like', 'from_email', $this->keywords],  
            ['like', 'from_name', $this->keywords],  
            ['like', 'subject', $this->keywords],  
            ['like', 'body', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}