<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\PostActivityReport;
use app\helpers\App;

/**
 * PostActivityReportSearch represents the model behind the search form of `app\models\PostActivityReport`.
 */
class PostActivityReportSearch extends PostActivityReport
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'post-activity-report/_search';
    public $searchAction = ['post-activity-report/index'];
    public $searchLabel = 'PostActivityReport';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by'], 'integer'],
            [['date', 'for', 'subject', 'title', 'location', 'date_of_activity', 'concerned_office', 'highlights_of_activity', 'description', 'photos', 'prepared_by', 'noted_by', 'token', 'created_at', 'updated_at'], 'safe'],
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
        $query = PostActivityReport::find();

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
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
            'date' => $this->date,
            'date_of_activity' => $this->date_of_activity,
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['like', 'for', $this->for])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'concerned_office', $this->concerned_office])
            ->andFilterWhere(['like', 'highlights_of_activity', $this->highlights_of_activity])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'photos', $this->photos])
            ->andFilterWhere(['like', 'prepared_by', $this->prepared_by])
            ->andFilterWhere(['like', 'noted_by', $this->noted_by])
            ->andFilterWhere(['like', 'token', $this->token]);
        
                
        $query->andFilterWhere(['or', 
            ['like', 'date', $this->keywords],  
            ['like', 'for', $this->keywords],  
            ['like', 'subject', $this->keywords],  
            ['like', 'title', $this->keywords],  
            ['like', 'location', $this->keywords],  
            ['like', 'date_of_activity', $this->keywords],  
            ['like', 'concerned_office', $this->keywords],  
            ['like', 'highlights_of_activity', $this->keywords],  
            ['like', 'description', $this->keywords],  
            ['like', 'prepared_by', $this->keywords],  
            ['like', 'noted_by', $this->keywords],  
            ['like', 'token', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}