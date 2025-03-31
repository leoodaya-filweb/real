<?php

namespace app\modules\chat\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\chat\models\SpaceNotification;
use app\helpers\App;

/**
 * SpaceNotificationSearch represents the model behind the search form of `app\modules\chat\models\SpaceNotification`.
 */
class SpaceNotificationSearch extends SpaceNotification
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'space-notification/_search';
    public $searchAction = ['space-notification/index'];
    public $searchLabel = 'SpaceNotification';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'space_id', 'space_message_id', 'state', 'created_by', 'updated_by'], 'integer'],
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
        $query = SpaceNotification::find();

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
            'user_id' => $this->user_id,
            'space_id' => $this->space_id,
            'space_message_id' => $this->space_message_id,
            'state' => $this->state,
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['like', 'token', $this->token]);
        
                
        $query->andFilterWhere(['or', 
            ['like', 'user_id', $this->keywords],  
            ['like', 'space_id', $this->keywords],  
            ['like', 'space_message_id', $this->keywords],  
            ['like', 'state', $this->keywords],  
            ['like', 'token', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}