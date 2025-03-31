<?php

namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Transaction;
use app\helpers\App;

/**
 * TransactionSearch represents the model behind the search form of `app\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    public $keywords;
    public $date_range;
    public $pagination;

    public $searchTemplate = 'transaction/_search';
    public $searchAction = ['transaction/index'];
    public $searchLabel = 'Transaction';

    public $qr_id;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'created_by', 'updated_by'], 'integer'],
            [['remarks', 'files', 'created_at', 'updated_at', 'transaction_type', 'emergency_welfare_program', 'status', 'social_pension_status'], 'safe'],
            [['keywords', 'pagination', 'date_range', 'record_status', 'qr_id'], 'safe'],
            [['keywords'], 'trim'],
            [['amount'], 'integer'],
        ];
    }

    public function init()
    {
        $this->pagination = App::setting('system')->pagination;
    }

    public function getTheDateRange()
    {
        return $this->date_range ?: implode(' - ', [
            date('Y-m-d', strtotime($this->startDate)),
            date('Y-m-d', strtotime($this->endDate)),
        ]);
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
        $query = Transaction::find()
            ->alias('t')
            ->joinWith(['member m', 'createdBy c'])
            ->leftJoin(['h'=>'{{%households}}'],  'm.household_id = h.id')
            ->groupBy('t.id');

        // add conditions that should always apply here
        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => $this->pagination
            ]
        ]);

        $dataProvider->sort->attributes['memberFullname'] = [
            'asc' => ['m.first_name' => SORT_ASC],
            'desc' => ['m.first_name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['createdByEmail'] = [
            'asc' => ['c.email' => SORT_ASC],
            'desc' => ['c.email' => SORT_DESC],
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            't.id' => $this->id,
            't.member_id' => $this->member_id,
            't.transaction_type' => $this->transaction_type,
            't.emergency_welfare_program' => $this->emergency_welfare_program,
            't.status' => $this->status,
            't.record_status' => $this->record_status,
            't.created_by' => $this->created_by,
            't.updated_by' => $this->updated_by,
            't.created_at' => $this->created_at,
            't.updated_at' => $this->updated_at,
            't.amount' => $this->amount,
            'm.qr_id' => $this->qr_id,
        ]);
                
        $query->andFilterWhere(['or', 
            ['like', 'm.qr_id', $this->keywords],
            ['like', 'h.no', $this->keywords], 
            ['like', 'm.first_name', $this->keywords],  
            ['like', 'm.middle_name', $this->keywords],  
            ['like', 'm.last_name', $this->keywords],  

            ['like', 'CONCAT_WS(" ", `m`.`first_name`,  `m`.`last_name`)', $this->keywords],  
            ['like', 'CONCAT_WS(" ", `m`.`last_name`,  `m`.`first_name`)', $this->keywords],  
            ['like', 'CONCAT_WS(" ", `m`.`first_name`, `m`.`middle_name`, `m`.`last_name`)', $this->keywords],  
            ['like', 'CONCAT_WS(" ", `m`.`last_name`, `m`.`middle_name`, `m`.`first_name`)', $this->keywords],  
            
            ['like', 'remarks', $this->keywords], 
            ['like', 't.name_of_deceased', $this->keywords], 
            ['like', 't.patient_name', $this->keywords], 
        ]);

        $query->daterange($this->date_range);
        
        $request_dup = Yii::$app->request->get('duplicate');
        if($request_dup==1){
            $query->rightJoin('(SELECT * FROM {{%transactions}} where status=10 and name_of_deceased is NOT null GROUP by name_of_deceased HAVING count(*)>1 ) tg', 'tg.name_of_deceased=t.name_of_deceased');
            $query->andWhere("t.status=10  and t.name_of_deceased is NOT null");
            $query->orderby(['t.name_of_deceased'=>SORT_ASC]);
        }
        

        return $dataProvider;
    }

    public static function chartData($date_range = '')
    {
        $curYear = App::formatter()->asDateToTimezone('', 'Y');
        
        $date_range = $date_range ?: implode(' - ', [
            date('Y-m-d', strtotime($curYear . '-01-01')),
            date('Y-m-d', strtotime($curYear .'-12-31')),
        ]);

        $gen = App::component('general');

        $dates = explode( ' - ', $date_range);
        $start = date("Y-m-d", strtotime($dates[0]) ); 
        $end = date("Y-m-d", strtotime($dates[1]) ); 

        $day   = $gen->dateDiff($start, $end);
        $month = $gen->dateDiff($start, $end, 'm');
        $year  = $gen->dateDiff($start, $end, 'y');


        $labels = [];
        $records = [];

        if ($year > 1) {
            $year = ceil((float)$year . '.' . $month);
            for ($i=0; $i < ($year + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'years');

                $labels[] = date('Y', $date);
                
                $records[] = Transaction::find()
                    ->where(["DATE_FORMAT(created_at, '%Y')" => date('Y', $date)])
                    ->count();
            }

        }
        elseif ($month > 1) {
            for ($i=0; $i < ($month + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'months');

                $labels[] = date('F', $date);
                // $labels[] = date('M', $date);

                $records[] = Transaction::find()
                    ->where(["DATE_FORMAT(created_at, '%Y-%m')" => date('Y-m', $date)])
                    ->count();
            }
        }
        else {
            for ($i=0; $i < ($day + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'days');

                $labels[] = date('d', $date);
                $records[] = Transaction::find()
                    ->where(["DATE_FORMAT(created_at, '%Y-%m-%d')" => date('Y-m-d', $date)])
                    ->count();
            }
        }

        $series[] = [
            'data' => $records,
            'name' => 'Transaction'
        ];
 
        return [
            'labels' => $labels,
            'series' => array_values($series),
            'colors' => ['#3E97FF']
        ];
    }
}