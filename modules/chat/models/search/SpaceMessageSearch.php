<?php

namespace app\modules\chat\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\chat\models\SpaceMessage;
use app\helpers\App;

/**
 * SpaceMessageSearch represents the model behind the search form of `app\modules\chat\models\SpaceMessage`.
 */
class SpaceMessageSearch extends SpaceMessage
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'space-message/_search';
    public $searchAction = ['space-message/index'];
    public $searchLabel = 'SpaceMessage';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'reply_id', 'space_id', 'type', 'created_by', 'updated_by'], 'integer'],
            [['content', 'attachments', 'token', 'created_at', 'updated_at'], 'safe'],
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
        $query = SpaceMessage::find()
            ->with('user');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
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
            'reply_id' => $this->reply_id,
            'space_id' => $this->space_id,
            'type' => $this->type,
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'attachments', $this->attachments])
            ->andFilterWhere(['like', 'token', $this->token]);
        
                
        $query->andFilterWhere(['or', 
            ['like', 'reply_id', $this->keywords],  
            ['like', 'space_id', $this->keywords],  
            ['like', 'content', $this->keywords],  
            ['like', 'attachments', $this->keywords],  
            ['like', 'type', $this->keywords],  
            ['like', 'token', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}