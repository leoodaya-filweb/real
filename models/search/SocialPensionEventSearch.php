<?php

namespace app\models\search;

use Yii;
use app\helpers\App;
use app\models\SocialPensionEvent;
use app\models\EventCategory;
use app\models\EventMember;
use yii\data\ActiveDataProvider;

/**
 * EventSearch represents the model behind the search form of `app\models\Event`.
 */
class SocialPensionEventSearch extends SocialPensionEvent
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'social-pension-event/_search';
    public $searchAction = ['social-pension-event/index'];
    public $searchLabel = 'Social Pension Event';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by'], 'integer'],
            [['name', 'description', 'barangay_ids', 'token', 'photo', 'created_at', 'updated_at'], 'safe'],
            [['keywords', 'pagination', 'date_range', 'record_status', 'status', 'category_type'], 'safe'],
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
        $query = SocialPensionEvent::find()
            ->select(['e.*'])
            ->with('eventCategory')
            ->alias('e')
            ->groupBy('e.id');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        $tblName = EventMember::tableName();
        $subQuery = "(SELECT COUNT('em.*') FROM {$tblName} AS em WHERE em.event_id = e.id)";
        
        $dataProvider->sort->attributes['totalBeneficiaryMember'] = [
            'asc' => [$subQuery => SORT_ASC],
            'desc' => [$subQuery => SORT_DESC],
        ];

        $tblName = EventCategory::tableName();
        $subQuery = "(SELECT name AS categoryName FROM {$tblName} AS ec WHERE ec.id = e.category_id)";

        $dataProvider->sort->attributes['categoryLabel'] = [
            'asc' => [$subQuery => SORT_ASC],
            'desc' => [$subQuery => SORT_DESC],
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'e.id' => $this->id,
            'e.status' => $this->status,
            'e.record_status' => $this->record_status,
            'e.created_by' => $this->created_by,
            'e.updated_by' => $this->updated_by,
            'e.created_at' => $this->created_at,
            'e.updated_at' => $this->updated_at,
            // 'e.category_type' => parent::DEFAULT_CATEGORY,
        ]);
                
        $query->andFilterWhere(['or', 
            ['like', 'e.name', $this->keywords],  
            ['like', 'e.description', $this->keywords],
        ]);

        $query->daterange($this->date_range);

        // dd($query->createCommand()->rawSql);

        return $dataProvider;
    }
}