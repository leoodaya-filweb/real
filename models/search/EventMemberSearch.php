<?php

namespace app\models\search;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\EventMember;
use app\widgets\Autocomplete;
use yii\data\ActiveDataProvider;

/**
 * EventMemberSearch represents the model behind the search form of `app\models\EventMember`.
 */
class EventMemberSearch extends EventMember
{
    public $received = false;

    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'event-member/_search';
    public $searchAction = ['event-member/index'];
    public $searchLabel = 'EventMember';

    public $age_from;
    public $age_to;

    public $_advancedFilterData;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'event_id', 'member_id', 'created_by', 'updated_by'], 'integer'],
            [['photo', 'created_at', 'updated_at'], 'safe'],
            [['keywords', 'pagination', 'date_range', 'record_status'], 'safe'],
            [['keywords'], 'trim'],
            [['family_head', 'gender', 'name', 'civil_status', 'educational_attainment', 'age_from', 'age_to', 'barangay', 'age', 'status', 'received', 'solo_parent', 'pwd', 'pwd_type', 'purok_no', 'qr_id', 'household_no', 'solo_member'], 'safe']
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

    public function setAge($condition=[])
    {
        $this->age_from = EventMember::youngestAge($condition);
        $this->age_to = EventMember::oldestAge($condition);
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
        $query = EventMember::find()
            ->alias('em')
            ->select(['em.*', 'e.status AS eventStatus'])
            ->joinWith('event e')
            ->groupBy('em.id');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC]],
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
            'em.id' => $this->id,
            'em.event_id' => $this->event_id,
            'em.member_id' => $this->member_id,
            'em.status' => $this->status,
            'em.record_status' => $this->record_status,
            'em.created_by' => $this->created_by,
            'em.updated_by' => $this->updated_by,
            'em.created_at' => $this->created_at,
            'em.updated_at' => $this->updated_at,

            'em.name' => $this->name,
            'em.qr_id' => $this->qr_id,
            'em.household_no' => $this->household_no,
            'em.family_head' => $this->family_head,
            'em.solo_parent' => $this->solo_parent,
            'em.solo_member' => $this->solo_member,
            'em.gender' => $this->gender,
            'em.civil_status' => $this->civil_status,
            'em.educational_attainment' => $this->educational_attainment,
            'em.pwd' => $this->pwd,
            'em.pwd_type' => $this->pwd_type,
            'em.barangay' => $this->barangay,
            'em.purok_no' => $this->purok_no,
            'em.age' => $this->age,

        ]);

        $query->andFilterWhere(['or', 
            ['like', 'em.name', $this->keywords],  
            ['like', 'em.qr_id', $this->keywords],  
            ['like', 'em.household_no', $this->keywords],  
            ['like', 'em.gender', $this->keywords],  
            ['like', 'em.civil_status', $this->keywords],  
            ['like', 'em.educational_attainment', $this->keywords],  
            ['like', 'em.pwd_type', $this->keywords],  
            ['like', 'em.barangay', $this->keywords],  
            ['like', 'em.purok_no', $this->keywords],  
            ['like', 'em.age', $this->keywords],  
        ]);

        if ($this->age_from != null && $this->age_to != null) {
            $query->andFilterWhere(["between", 'em.age', $this->age_from, $this->age_to]);
        }
        else {
            if ($this->age_from != null && $this->age_to == null) {
                $query->andFilterWhere([">=", 'em.age', $this->age_from]);
            }

            if ($this->age_to != null && $this->age_from == null) {
                $query->andFilterWhere(["<=", 'em.age', $this->age_to]);
            }
        }

        if ($this->received) {
            $query->received();
        }

        $query->daterange($this->date_range);

        return $dataProvider;
    }

    public function getAgeQuery()
    {
        return "TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE())";
    }

    public function getAutocompleteInput($model, $type='pending')
    {
        return Autocomplete::widget([
            'url' => Url::to(['event-member/find-by-keywords', 'event_id' => $model->id, 'type' => $type]),
            'input' => Html::activeInput('search', $this, 'keywords', [
                'placeholder' => 'Search',
                'name' => 'keywords',
                'class' => 'form-control'
            ])
        ]);
    }

    public function totalFilterTag($attribute)
    {
        if ($this->{$attribute} && is_countable($this->{$attribute})) {
            return implode('', [
                '(',
                App::formatter('asNumber', count($this->{$attribute})),
                ')'
            ]);
        }
    }

    public function filterKeyMapParams($field, $paramName='', $condition=[])
    {
        $paramName = $paramName ?: $field;

        $data = [];
        $rows = parent::filter($field, $condition);
        $params = App::keyMapParams($paramName);

        foreach ($rows as $key => $value) {
            $data[$key] = $params[$key] ?? '';
        }

        return $data;
    }

    public function getAdvancedFilterData($condition=[])
    {
        if ($this->_advancedFilterData === null) {
            $this->_advancedFilterData = [
                'family_head' => [
                    'icon' => '<i class="fas fa-house-user"></i>',
                    'title' => 'Family Head',
                    'data' => $this->filterKeyMapParams('family_head', '', $condition)
                ],
                'solo_parent' => [
                    'icon' => '<i class="fas fa-user-tag"></i>',
                    'title' => 'Solo Parent',
                    'data' => $this->filterKeyMapParams('solo_parent', '', $condition)
                ],
                'solo_member' => [
                    'icon' => '<i class="fas fa-user-tag"></i>',
                    'title' => 'Solo Member',
                    'data' => $this->filterKeyMapParams('solo_member', '', $condition)
                ],
                'gender' => [
                    'icon' => '<i class="fas fa-user-cog"></i>',
                    'title' => 'Gender',
                    'data' => parent::filter('gender', $condition)
                ],
                'civil_status' => [
                    'icon' => '<i class="fas fa-user-tie"></i>',
                    'title' => 'Civil Status',
                    'data' => parent::filter('civil_status', $condition)
                ],
                'educational_attainment' => [
                    'icon' => '<i class="fas fa-school"></i>',
                    'title' => 'Educational Attainment',
                    'data' => parent::filter('educational_attainment', $condition)
                ],
                'pwd' => [
                    'icon' => '<i class="fas fa-user"></i>',
                    'title' => 'PWD',
                    'data' => $this->filterKeyMapParams('pwd', '', $condition)
                ],
                'pwd_type' => [
                    'icon' => '<i class="fas fa-user"></i>',
                    'title' => 'PWD Type',
                    'data' => parent::filter('pwd_type', $condition)
                ],
                'barangay' => [
                    'icon' => '<i class="fas fa-shield-alt"></i>',
                    'title' => 'Barangay',
                    'data' => parent::filter('barangay', $condition)
                ],
                'purok_no' => [
                    'icon' => '<i class="fas fa-road"></i>',
                    'title' => 'Purok',
                    'data' => parent::filter('purok_no', $condition)
                ],
            ];
        }
        return $this->_advancedFilterData;
    }
}