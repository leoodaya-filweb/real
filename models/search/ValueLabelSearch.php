<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\ValueLabel;
use app\helpers\App;

/**
 * ValueLabelSearch represents the model behind the search form of `app\models\ValueLabel`.
 */
class ValueLabelSearch extends ValueLabel
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'value-label/_search';
    public $searchAction = ['value-label/index'];
    public $searchLabel = 'ValueLabel';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'value', 'elementID', 'created_by', 'updated_by'], 'integer'],
            [['var', 'label', 'created_at', 'updated_at'], 'safe'],
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
        $query = ValueLabel::find();

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
            'value' => $this->value,
            'elementID' => $this->elementID,
            'record_status' => $this->record_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['like', 'var', $this->var])
            ->andFilterWhere(['like', 'label', $this->label]);
        
                
        $query->andFilterWhere(['or', 
            ['like', 'var', $this->keywords],  
            ['like', 'value', $this->keywords],  
            ['like', 'elementID', $this->keywords],  
            ['like', 'label', $this->keywords],  
        ]);

        $query->daterange($this->date_range);

        return $dataProvider;
    }
}