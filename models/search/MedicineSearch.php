<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Medicine;
use app\helpers\App;

/**
 * MedicineSearch represents the model behind the search form of `app\models\Medicine`.
 */
class MedicineSearch extends Medicine
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'medicine/_search';
    public $searchAction = ['medicine/index'];
    public $searchLabel = 'Medicine';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'transaction_id', 'created_by', 'updated_by'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'safe'],
            [['price'], 'number'],
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
        $query = Medicine::find();

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
            'transaction_id' => $this->transaction_id,
            'price' => $this->price,
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['like', 'name', $this->name]);
        
                
        $query->andFilterWhere(['or', 
            ['like', 'transaction_id', $this->keywords],  
            ['like', 'name', $this->keywords],  
            ['like', 'price', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}