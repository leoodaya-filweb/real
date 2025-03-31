<?php

namespace app\models\search;

use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Member;
use app\widgets\Autocomplete;
use yii\data\ActiveDataProvider;

/**
 * MemberSearch represents the model behind the search form of `app\models\Member`.
 */
class MemberSearch extends Member
{
    public $age_from;
    public $age_to;

    public $barangay_ids;
    public $purok_no;

    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'member/_search';
    public $searchAction = ['member/index'];
    public $searchLabel = 'Member';


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'household_id', 'created_by', 'updated_by', 'qr_id'], 'integer'],
            [['last_name', 'middle_name', 'first_name', 'birth_date', 'birth_place', 'email', 'contact_no', 'photo', 'token', 'slug', 'created_at', 'updated_at', 'income', 'source_of_income', 'pensioner', 'pensioner_from', 'pension_amount', 'head', 'solo_parent', 'pwd', 'pwd_type', 'age'], 'safe'],
            [['keywords', 'pagination', 'date_range', 'record_status', 'sex', 'educational_attainment', 'civil_status', 'barangay_ids', 'age_from', 'age_to', 'living_status', 'purok_no', 'social_pension_status', 'voter', 'solo_member'], 'safe'],
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
        $query = Member::find()
            ->select([
                'm.*',
                'h.no AS household_no',
                'h.purok_no AS purok_no',
                'b.name AS barangay_name',
            ])
            ->with('gender', 'civilStatus', 'relationModel')
            ->alias('m')
            ->joinWith(['household h', 'barangay b'])
            ->groupBy('m.id');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        $dataProvider->sort->attributes['householdNo'] = [
            'asc' => ['h.no' => SORT_ASC,'m.head'=>SORT_DESC, 'm.line_number'=>SORT_ASC],
            'desc' => ['h.no' => SORT_DESC, 'm.head'=>SORT_DESC, 'm.line_number'=>SORT_ASC],
        ];

        $dataProvider->sort->attributes['address'] = [
            'asc' => ['b.name' => SORT_ASC],
            'desc' => ['b.name' => SORT_DESC],
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
            'm.id' => $this->id,
            'm.head' => $this->head,
            'm.household_id' => $this->household_id,
            'm.pensioner' => $this->pensioner,
            'm.qr_id' => $this->qr_id,
            'm.sex' => $this->sex,
            'm.birth_date' => $this->birth_date,
            'm.birth_place' => $this->birth_place,
            'm.civil_status' => $this->civil_status,
            'm.record_status' => $this->record_status,
            'm.created_by' => $this->created_by,
            'm.updated_by' => $this->updated_by,
            'm.created_at' => $this->created_at,
            'm.updated_at' => $this->updated_at,
            'm.educational_attainment' => $this->educational_attainment,
            'h.barangay_id' => $this->barangay_ids,
            'h.purok_no' => $this->purok_no,
            'm.living_status' => $this->living_status,
            'm.solo_parent' => $this->solo_parent,
            'm.solo_member' => $this->solo_member,
            'm.pwd' => $this->pwd,
            'm.pwd_type' => $this->pwd_type,
            'm.social_pension_status' => $this->social_pension_status,
            'm.voter' => $this->voter,
            'm.new_cbms'=>1 //(strpos($_SERVER['REQUEST_URI'], "demo") == true?1:null)
        ]);

        $query->andFilterWhere(['or', 
            ['like', 'h.no', $this->keywords], 
            ['like', 'm.qr_id', $this->keywords], 
            ['like', 'CONCAT(m.first_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.first_name)', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.middle_name, " ", m.last_name)', $this->keywords], 
            ['like', 'CONCAT(m.last_name, " ", m.first_name, " ", m.middle_name)', $this->keywords],
            ['like', 'CONCAT(m.last_name, " ", m.middle_name, " ", m.first_name)', $this->keywords],  
            ['like', 'm.last_name', $this->keywords],  
            ['like', 'm.middle_name', $this->keywords],  
            ['like', 'm.first_name', $this->keywords],  
            ['like', 'm.email', $this->keywords],  
            ['like', 'm.contact_no', $this->keywords],
            ['like', 'b.name', $this->keywords],
        ]);

        if ($this->age_from != null && $this->age_to != null) {
            //$query->andFilterWhere(["between", 'age', $this->age_from, $this->age_to]);
            
           $query->andFilterWhere(['and', "(year(curdate())-year(birth_date) - (right(curdate(),5) < right(birth_date,5)))>=$this->age_from", "(year(curdate())-year(birth_date) - (right(curdate(),5) < right(birth_date,5)))<=$this->age_to"]);
            
           // (year(curdate())-year(birth_date) - (right(curdate(),5) < right(birth_date,5)))
            
            
        }
        else {
            if ($this->age_from != null && $this->age_to == null) {
                //$query->andFilterWhere([">=", 'age', $this->age_from]);
                $query->andFilterWhere([">=", '(year(curdate())-year(birth_date) - (right(curdate(),5) < right(birth_date,5)))', $this->age_from]);
            }

            if ($this->age_to != null && $this->age_from == null) {
                //$query->andFilterWhere(["<=", 'age', $this->age_to]);
                $query->andFilterWhere(["<=", '(year(curdate())-year(birth_date) - (right(curdate(),5) < right(birth_date,5)))', $this->age_to]);
            }
        }

        $query->daterange($this->date_range);
        
        if(!Yii::$app->request->get('sort')){
        $query->orderBy(['h.no' => SORT_DESC, 'm.head'=>SORT_DESC, 'm.line_number'=>SORT_ASC, 'm.birth_date'=>SORT_ASC]);
        }

        return $dataProvider;
    }

    public function setAge()
    {
        $this->age_from  = Member::youngestAge();
        $this->age_to  = Member::oldestAge();
    }

    public function getAgeQuery()
    {
        return "TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE())";
    }

    public function getAutocompleteInput($event)
    {
        return Autocomplete::widget([
            'url' => Url::to(['member/find-by-keywords-event', 'event_id' => $event->id]),
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

    public function getPrioritySort()
    {
        return [
            'm.social_pension_status' => SORT_DESC, // 0.6
            'm.pwd' => SORT_ASC, // 0.6
            'm.senior_citizen_id' => SORT_DESC, // 0.6
            'm.solo_member' => SORT_ASC, // 0.25
            'b.priority_score' => SORT_DESC, // 0.15
        ];
    }
}