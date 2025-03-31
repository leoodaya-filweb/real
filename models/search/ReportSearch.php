<?php

namespace app\models\search;

use Yii;
use app\helpers\App;
use app\models\Transaction;
use app\models\User;
use app\widgets\ExportButton;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class ReportSearch extends \yii\base\Model
{
    public $keywords;
    public $pagination;
    public $date_range;
    public $record_status;
    public $current_date;

    public $status;
    public $emergency_welfare_program;
    public $transaction_type;
    public $staff;

    public $searchTemplate = 'report/_search';
    public $searchAction = ['report/emergency-welfare-program'];
    public $searchLabel = 'Transaction Items';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keywords', 'pagination', 'date_range', 'record_status', 'status', 'emergency_welfare_program', 'transaction_type','current_date','staff'], 'safe'],
            [['keywords'], 'trim'],
        ];
    }

    public function init()
    {
        parent::init();

        $this->pagination = App::setting('system')->pagination;
        $this->date_range = $this->date_range ?: implode(' - ', [
            $this->startDate,
            (Yii::$app->request->get('current_date')?:$this->endDate),
        ]);
    }

    public function emergency_welfare_program_data()
    {
        $emergency_welfare_programs = [
            'medical' => [
                Transaction::AICS_MEDICAL,
                Transaction::AICS_MEDICAL_MEDICINE
            ],
            'laboratory_request' => Transaction::AICS_LABORATORY_REQUEST,
            'balik_probinsya' => Transaction::BALIK_PROBINSYA_PROGRAM,
            'educational_assistance' => Transaction::EDUCATIONAL_ASSISTANCE,
            'food_assistance' => Transaction::FOOD_ASSISTANCE,
            'finacial_and_other_assistance' => Transaction::FINANCIAL_AND_OTHER_ASSISTANCE,
        ];

        $transaction_types = [
            'social_pension' => Transaction::SOCIAL_PENSION,
            'death_assistance' => Transaction::DEATH_ASSISTANCE,
        ];

        $ranges = [
            'current_week' => $this->currentWeek(),
            'current_month' => $this->currentMonth(),
            'current_quarter' => $this->currentQuarter(),
            'current_year' => $this->currentYear(),
        ];

        $data = [];
        $total_female = 0;
        $total_male = 0;

        foreach ($emergency_welfare_programs as $ewp => $emergency_welfare_program) {
            $models = [];
            
            // asDateToTimezone($date='', $format='F d, Y h:i A', $timezone="")
           $hours=App::formatter()->asDateToTimezone(date("Y-m-d H:i:s"),"P");
	        $datesearch=" DATE_ADD(t.created_at,INTERVAL '$hours' HOUR_MINUTE) ";
	     //echo $datesearch;
            
            foreach ($ranges as $key => $range) {
                
                $dates=explode( ' - ', $range);
	      	    $start= date("Y-m-d", strtotime($dates[0]) ); 
	      	    $end=date("Y-m-d", strtotime($dates[1]) ); 
	      	    
                $query = Transaction::find()
                    ->alias('t')
                    ->joinWith(['member m', 'member.gender s'])
                    ->select([
                        's.label',
                        'm.sex',
                        'COUNT("*") AS total',
                        'sum(if(t.status<>28,t.amount,0)) AS t_amount'
                    ])
                    ->emergency_welfare_program()
                   // ->daterange($range)
                    ->andWhere(['t.status' => 10])
                    ->andFilterWhere(['between',"date($datesearch)", $start, $end ])
                    ->groupBy('m.sex');

                if (is_array($emergency_welfare_program)) {
                    $query->andWhere([
                        't.emergency_welfare_program' => $emergency_welfare_program
                    ]);
                }
                elseif ($emergency_welfare_program == Transaction::AICS_MEDICAL) {
                    $query->medical();
                }
                elseif ($emergency_welfare_program == Transaction::AICS_LABORATORY_REQUEST) {
                    $query->laboratory_request();
                }
                elseif ($emergency_welfare_program == Transaction::BALIK_PROBINSYA_PROGRAM) {
                    $query->balik_probinsya();
                }elseif ($emergency_welfare_program == Transaction::EDUCATIONAL_ASSISTANCE) {
                    $query->andWhere(['emergency_welfare_program' => Transaction::EDUCATIONAL_ASSISTANCE ]);
                }elseif ($emergency_welfare_program == Transaction::FOOD_ASSISTANCE) {
                    $query->andWhere(['emergency_welfare_program' => Transaction::FOOD_ASSISTANCE ]);
                }elseif ($emergency_welfare_program == Transaction::FINANCIAL_AND_OTHER_ASSISTANCE) {
                    $query->andWhere(['emergency_welfare_program' => Transaction::FINANCIAL_AND_OTHER_ASSISTANCE ]);
                }
                
                
                $records = $query->asArray()
                    ->all();
                $models[$key] = ArrayHelper::map($records, 'label', 'total');
                
                $models_amount[$key] = ArrayHelper::map($records, 'label', 't_amount');
            }
            foreach ($models as $key => &$model) {
                $model['Male'] = (int)($model['Male'] ?? 0);
                $model['Female'] = (int)($model['Female'] ?? 0);
                $model['Total'] = (int)$model['Male'] + (int)$model['Female'];
                $model['Total_Amount'] = (int)$models_amount[$key]['Male'] + (int)$models_amount[$key]['Female'];
            }
            $total_female += $models['current_year']['Female'];
            $total_male += $models['current_year']['Male'];
            $data[$ewp] = $models;
        }

        foreach ($transaction_types as $tt => $transaction_type) {
            $models = [];
       

            foreach ($ranges as $key => $range) {
               // echo  $range;
               
                $dates=explode( ' - ', $range);
	      	    $start= date("Y-m-d", strtotime($dates[0]) ); 
	      	    $end=date("Y-m-d", strtotime($dates[1]) ); 
               
                $records = Transaction::find()
                    ->alias('t')
                    ->joinWith(['member m', 'member.gender s'])
                    ->select([
                        's.label',
                        'm.sex',
                        'COUNT("*") AS total',
                        'sum(if(t.status<>28,t.amount,0)) AS t_amount'
                    ])
                   ->andWhere(['t.transaction_type' => $transaction_type])
                   ->andWhere(['t.status' => 10])
                    ->andFilterWhere(['between',"date($datesearch)", $start, $end ])
                     //->daterange($range)
                    ->groupBy('m.sex')
                    ->asArray()
                    ->all();
                $models[$key] = ArrayHelper::map($records, 'label', 'total');
                $models_amount[$key] = ArrayHelper::map($records, 'label', 't_amount');
            }
            foreach ($models as $key => &$model) {
                $model['Male'] = (int)($model['Male'] ?? 0);
                $model['Female'] = (int)($model['Female'] ?? 0);
                $model['Total'] = (int)$model['Male'] + (int)$model['Female'];
                $model['Total_Amount'] = (int)$models_amount[$key]['Male'] + (int)$models_amount[$key]['Female'];
            }
            $total_female += $models['current_year']['Female'];
            $total_male += $models['current_year']['Male'];
            $data[$tt] = $models;
        }

        $data['total_female'] = $total_female;
        $data['total_male'] = $total_male;
        $data['total_medical'] = $data['medical']['current_year']['Total'];
        $data['total_laboratory_request'] = $data['laboratory_request']['current_year']['Total'];
        $data['total_balik_probinsya'] = $data['balik_probinsya']['current_year']['Total'];
        $data['total_death_assistance'] = $data['death_assistance']['current_year']['Total'];
        $data['total_social_pension'] = $data['social_pension']['current_year']['Total'];
        
        $data['total_educational_assistance'] = $data['educational_assistance']['current_year']['Total'];
        $data['total_food_assistance'] = $data['food_assistance']['current_year']['Total'];
        $data['total_finacial_and_other_assistance'] = $data['finacial_and_other_assistance']['current_year']['Total'];
        
        
            
        return $data;
    }

    public function getEwpExportColumns()
    {
        return [];
    }

    public function emergency_welfare_program_search($params=[])
    {
        $query = Transaction::find()
            ->alias('t')
            ->joinWith(['member m', 'createdBy'])
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
                
        $query->andFilterWhere(['or', 
            ['like', 'm.qr_id', $this->keywords],  
            ['like', 'm.first_name', $this->keywords],  
            ['like', 'm.middle_name', $this->keywords],  
            ['like', 'm.last_name', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.first_name)', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.middle_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.middle_name, " ", m.first_name)', $this->keywords]
                ,  
            ['like', 'remarks', $this->keywords],  
        ]);

        $query->andFilterWhere([
            't.status' => $this->status,
        ]);
        

        $query->andFilterWhere(['or',
            ['t.emergency_welfare_program' => $this->emergency_welfare_program],
            ['t.transaction_type' => $this->transaction_type],
        ]);

        $query->andWhere(['or', 
            [
                't.emergency_welfare_program' => [
                    Transaction::AICS_MEDICAL,
                    Transaction::AICS_MEDICAL_MEDICINE,
                    Transaction::AICS_LABORATORY_REQUEST,
                    Transaction::BALIK_PROBINSYA_PROGRAM,
                ]
            ],
            [
                't.transaction_type' => [
                    Transaction::DEATH_ASSISTANCE,
                    Transaction::SOCIAL_PENSION,
                ]
            ]
        ]);
        
        $query->andWhere([
            't.status' =>10,
        ]);

        // dd($query->createCommand()->rawSql);

        $query->daterange($this->date_range);
        return $dataProvider;
    }
    
    public function getEwpExcelIgnoreAttributes()
    {
        return [];
    }

    public function getEwpColumns($params=[])
    {
        $transaction = new Transaction();
        $columns = $transaction->gridColumns;

        $status = $columns['status'];
        $created_at = $columns['created_at'];
        $last_updated = $columns['last_updated'];
        unset(
            $columns['active'],
            $columns['status'],
            $columns['created_at'],
            $columns['last_updated']
        );

        $columns['transaction_type'] = [
            'label' => 'Transaction Type',
            'attribute' => 'transaction_type',
            'format' => 'raw',
            'value' => 'transactionTypeName'
        ];

        $columns['assistance_type'] = [
            'label' => 'assistance Type',
            'attribute' => 'emergency_welfare_program',
            'format' => 'raw',
            'value' => 'assistanceTypeName'
        ];

        $columns['status'] = $status;
        $columns['created_at'] = $created_at;
        $columns['last_updated'] = $last_updated;

        return $columns;
    }


    public function getAicsColumns($params=[])
    {
        $transaction = new Transaction();
        $columns = $transaction->gridColumns;
        unset($columns['active']);

        $columns['transaction_type'] = [
            'label' => 'assistance Type',
            'attribute' => 'emergency_welfare_program',
            'format' => 'raw',
            'value' => 'assistanceTypeName'
        ];

        $columns = $transaction->change_key($columns, 'transaction_type', 'assistanceType');
        return $columns;
    }

    public function currentWeek($format='Y-m-d', $data='all', $current_date='')
    {
        
        $current_date = $this->current_date;
        $D = App::formatter()->asDateToTimezone($current_date, 'D');

        if($D != 'Mon') {
            //take the last monday
            $staticstart = $current_date?date($format,strtotime('last Monday', strtotime($current_date))):date($format,strtotime('last Monday'));
        }
        else {
            $staticstart = App::formatter()->asDateToTimezone($current_date, $format);  
        }

        //always next saturday
        if($D != 'Sun') {
            $staticfinish = $current_date?date($format,strtotime($current_date)):date($format,strtotime('next Sunday'));
        }
        else{
            $staticfinish = $current_date?date($format,strtotime($current_date)):App::formatter()->asDateToTimezone($current_date, $format);  
        }

        if ($data == 'start') {
            return $staticstart;
        }

        if ($data == 'end') {
            return $staticfinish;
        }
        return implode(' - ', [
            $staticstart,
            $staticfinish,
        ]);
    }

    public function currentMonth($format='Y-m-d',$current_date='') 
    {
        $current_date = $this->current_date;
        $date = App::formatter()->asDateToTimezone($current_date, 'Y-m-d');;   
        $dt = strtotime($date);

        return implode(' - ', [
            date ($format, strtotime ('first day of this month', $dt)),
            date ($format,  $current_date?strtotime($current_date):strtotime('last day of this month', $dt)   )
        ]);
    }

    public function currentQuarter($format='Y-m-d',$current_date='')
    {
        $current_date = $this->current_date;
         
        $curYear = App::formatter()->asDateToTimezone($current_date, 'Y');
        $curMonth = App::formatter()->asDateToTimezone($current_date, 'm');
        $curQuarter = ceil($curMonth/3);

        switch ($curQuarter) {
            case 1:
                $start = date ("{$curYear}-01-01");
                $end = date ("{$curYear}-03-31");
                break;
            case 2:
                $start = date ("{$curYear}-04-01");
                $end = date ("{$curYear}-06-30");
                break;
            case 3:
                $start = date ("{$curYear}-07-01");
                $end = date ("{$curYear}-09-30");
                break;
            case 4:
                $start = date ("{$curYear}-10-01");
                $end = date ("{$curYear}-12-31");
                break;
            default:
                $start = date ("{$curYear}-01-01");
                $end = date ("{$curYear}-03-31");
                break;
        }

        return implode(' - ', [
            date ($format, strtotime($start)),
            date ($format, strtotime( ($current_date?$current_date:$end)   )),
        ]);
    }

    public function currentYear($format = 'Y-m-d',$current_date='')
    {
        $current_date = $this->current_date;
        $curYear = App::formatter()->asDateToTimezone($current_date, 'Y');
        return implode(' - ', [
            date ($format, strtotime("{$curYear}-01-01")),
            date ($format, strtotime( ($current_date?$current_date:"{$curYear}-12-31")   )),
        ]); 
    }

    public function getStartDate($from_database = false)
    {
        if ($this->date_range && $from_database == false) {
            $date = App::formatter()->asDaterangeToSingle($this->date_range, 'start');
            return date('F d, Y', strtotime($date));
        }

        $curYear = App::formatter()->asDateToTimezone('', 'Y');
        return "{$curYear}-01-01";
    }

    public function getEndDate($from_database = false)
    {
        if ($this->date_range && $from_database == false) {
            $date = App::formatter()->asDaterangeToSingle($this->date_range, 'end');
            return date('F d, Y', strtotime($date));
        }

        $curYear = App::formatter()->asDateToTimezone('', 'Y');
        return "{$curYear}-12-31";
    }

    public function startDate()
    {
        if ($this->date_range) {
            return date('F d, Y', strtotime(
                App::formatter()->asDaterangeToSingle($this->date_range, 'start')
            ));
        }

        return date('F d, Y', strtotime($this->startDate));
    }

    public function endDate()
    {
        if ($this->date_range) {
            return date('F d, Y', strtotime(
                App::formatter()->asDaterangeToSingle($this->date_range, 'end')
            ));
        }

        return date('F d, Y', strtotime($this->endDate));
    }

    public function controllerID()
    {
        return 'report';
    }

    public function getEwpExportSummaryBtn()
    {
        return ExportButton::widget([
            'filename' => $this->reportName('Emergency Welfare Program Summary Report'),
            'controller' => 'report',
            'anchorOptions' =>  [
            'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
                'data-toggle' => 'dropdown',
                'aria-haspopup' => true,
                'aria-expanded' => false
            ],
            'printUrl' => ['report/print', 'type' => 'summary'],
            'pdfUrl' => ['report/export-pdf', 'type' => 'summary'],
            'csvUrl' => ['report/export-csv', 'type' => 'summary'],
            'xlsUrl' => ['report/export-xls', 'type' => 'summary'],
            'xlsxUrl' => ['report/export-xlsx', 'type' => 'summary'],
        ]);
    }

    public function getEwpExportTransactionBtn()
    {
        return  ExportButton::widget([
            'filename' => $this->reportName('Emergency Welfare Program Transaction Report'),
            'controller' => 'report',
            'anchorOptions' =>  [
            'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
                'data-toggle' => 'dropdown',
                'aria-haspopup' => true,
                'aria-expanded' => false
            ],
            'printUrl' => [
                'report/print', 
                'emergency_welfare_program' => [
                    Transaction::AICS_MEDICAL,
                    Transaction::AICS_LABORATORY_REQUEST,
                    Transaction::BALIK_PROBINSYA_PROGRAM,
                ],
                'transaction_type' => [
                    Transaction::DEATH_ASSISTANCE,
                    Transaction::SOCIAL_PENSION,
                ],
                'date_range' => $this->date_range,
            ],
            'pdfUrl' => [
                'report/export-pdf', 
                'emergency_welfare_program' => [
                    Transaction::AICS_MEDICAL,
                    Transaction::AICS_LABORATORY_REQUEST,
                    Transaction::BALIK_PROBINSYA_PROGRAM,
                ],
                'transaction_type' => [
                    Transaction::DEATH_ASSISTANCE,
                    Transaction::SOCIAL_PENSION,
                ],
                'date_range' => $this->date_range,
            ],
            'csvUrl' => [
                'report/export-csv', 
                'emergency_welfare_program' => [
                    Transaction::AICS_MEDICAL,
                    Transaction::AICS_LABORATORY_REQUEST,
                    Transaction::BALIK_PROBINSYA_PROGRAM,
                ],
                'transaction_type' => [
                    Transaction::DEATH_ASSISTANCE,
                    Transaction::SOCIAL_PENSION,
                ],
                'date_range' => $this->date_range,
            ],
            'xlsUrl' => [
                'report/export-xls', 
                'emergency_welfare_program' => [
                    Transaction::AICS_MEDICAL,
                    Transaction::AICS_LABORATORY_REQUEST,
                    Transaction::BALIK_PROBINSYA_PROGRAM,
                ],
                'transaction_type' => [
                    Transaction::DEATH_ASSISTANCE,
                    Transaction::SOCIAL_PENSION,
                ],
                'date_range' => $this->date_range,
            ],
            'xlsxUrl' => [
                'report/export-xlsx', 
                'emergency_welfare_program' => [
                    Transaction::AICS_MEDICAL,
                    Transaction::AICS_LABORATORY_REQUEST,
                    Transaction::BALIK_PROBINSYA_PROGRAM,
                ],
                'transaction_type' => [
                    Transaction::DEATH_ASSISTANCE,
                    Transaction::SOCIAL_PENSION,
                ],
                'date_range' => $this->date_range,
            ],
        ]);
    }

    public function transaction_type_search($params=[])
    {
        $query = Transaction::find()
            ->alias('t')
            ->joinWith(['member m', 'createdBy'])
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
                
        $query->andFilterWhere(['or', 
            ['like', 'm.qr_id', $this->keywords],  
            ['like', 'm.first_name', $this->keywords],  
            ['like', 'm.middle_name', $this->keywords],  
            ['like', 'm.last_name', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.first_name)', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.middle_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.middle_name, " ", m.first_name)', $this->keywords]
                ,  
            ['like', 'remarks', $this->keywords],  
        ]);

        $query->andFilterWhere([
            't.status' => $this->status,
            't.transaction_type' => $this->transaction_type,
        ]);
        
         $query->andFilterWhere([
            't.status' => 10,
        ]);


        $query->daterange($this->date_range);
        return $dataProvider;
    }

    public function transaction_type_data()
    {

        $transaction_types = App::params('transaction_types');

        $ranges = [
            'current_week' => $this->currentWeek(),
            'current_month' => $this->currentMonth(),
            'current_quarter' => $this->currentQuarter(),
            'current_year' => $this->currentYear(),
        ];

        $data = [];
        $total_female = 0;
        $total_male = 0;

        foreach ($transaction_types as $id => $transaction_type) {

            $models = [];

            foreach ($ranges as $key => $range) {
                $records = Transaction::find()
                    ->alias('t')
                    ->joinWith(['member m', 'member.gender s'])
                    ->select([
                        's.label',
                        'm.sex',
                        'COUNT("*") AS total'
                    ])
                    ->daterange($range)
                    ->andWhere(['transaction_type' => $id])
                    ->andWhere(['t.status' => 10])
                    ->groupBy('m.sex')
                    ->asArray()
                    ->all();

                $models[$key] = ArrayHelper::map($records, 'label', 'total');
            }

            foreach ($models as $key => &$model) {
                $model['Male'] = (int)($model['Male'] ?? 0);
                $model['Female'] = (int)($model['Female'] ?? 0);
                $model['Total'] = (int)$model['Male'] + (int)$model['Female'];
            }

            $total_female += $models['current_year']['Female'];
            $total_male += $models['current_year']['Male'];
            $data[str_replace('-', '_', $transaction_type['slug'])] = $models;
        }

        $data['total_female'] = $total_female;
        $data['total_male'] = $total_male;

        $data['total_emergency_welfare_program'] = $data['emergency_welfare_program']['current_year']['Total'];
        $data['total_senior_citizen_id_application'] = $data['senior_citizen_id_application']['current_year']['Total'];
        $data['total_social_pension'] = $data['social_pension']['current_year']['Total'];
        $data['total_death_assistance'] = $data['death_assistance']['current_year']['Total'];
        $data['total_certificate_of_indigency'] = $data['certificate_of_indigency']['current_year']['Total'];
        $data['total_financial_certification'] = $data['financial_certification']['current_year']['Total'];
        $data['total_social_case_study_report'] = $data['social_case_study_report']['current_year']['Total'];
        $data['total_certificate_of_marriage_counseling'] = $data['certificate_of_marriage_counseling']['current_year']['Total'];
        $data['total_certificate_of_compliance'] = $data['certificate_of_compliance']['current_year']['Total'];
        $data['total_certificate_of_apparent_disability'] = $data['certificate_of_apparent_disability']['current_year']['Total'];
  
        return $data;
    }


    public function getTransactionTypeExportSummaryBtn()
    {
        return ExportButton::widget([
            'filename' => $this->reportName('Transaction Type Summary Report'),
            'controller' => 'report',
            'anchorOptions' =>  [
            'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
                'data-toggle' => 'dropdown',
                'aria-haspopup' => true,
                'aria-expanded' => false
            ],
            'printUrl' => ['report/print', 'type' => 'summary', 'report' => 'transaction-type'],
            'pdfUrl' => ['report/export-pdf', 'type' => 'summary', 'report' => 'transaction-type'],
            'csvUrl' => ['report/export-csv', 'type' => 'summary', 'report' => 'transaction-type'],
            'xlsUrl' => ['report/export-xls', 'type' => 'summary', 'report' => 'transaction-type'],
            'xlsxUrl' => ['report/export-xlsx', 'type' => 'summary', 'report' => 'transaction-type'],
        ]);
    }

    public function getCertificationExportSummaryBtn()
    {
        return ExportButton::widget([
            'filename' => $this->reportName('Certification Summary Report'),
            'controller' => 'report',
            'anchorOptions' =>  [
            'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
                'data-toggle' => 'dropdown',
                'aria-haspopup' => true,
                'aria-expanded' => false
            ],
            'printUrl' => ['report/print', 'type' => 'summary', 'report' => 'certification'],
            'pdfUrl' => ['report/export-pdf', 'type' => 'summary', 'report' => 'certification'],
            'csvUrl' => ['report/export-csv', 'type' => 'summary', 'report' => 'certification'],
            'xlsUrl' => ['report/export-xls', 'type' => 'summary', 'report' => 'certification'],
            'xlsxUrl' => ['report/export-xlsx', 'type' => 'summary', 'report' => 'certification'],
        ]);
    }

    protected function reportName($name)
    {
        $curYear = App::formatter()->asDateToTimezone('', 'Y');

        return "{$name} ($curYear)";
    }

    public function getAicsExportSummaryBtn()
    {
        return ExportButton::widget([
            'filename' => $this->reportName('AICS Summary Report'),
            'controller' => 'report',
            'anchorOptions' =>  [
            'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
                'data-toggle' => 'dropdown',
                'aria-haspopup' => true,
                'aria-expanded' => false
            ],
            'printUrl' => ['report/print', 'type' => 'summary', 'report' => 'aics'],
            'pdfUrl' => ['report/export-pdf', 'type' => 'summary', 'report' => 'aics'],
            'csvUrl' => ['report/export-csv', 'type' => 'summary', 'report' => 'aics'],
            'xlsUrl' => ['report/export-xls', 'type' => 'summary', 'report' => 'aics'],
            'xlsxUrl' => ['report/export-xlsx', 'type' => 'summary', 'report' => 'aics'],
        ]);
    }
    
    
     public function getStaffExportSummaryBtn()
    {
        return ExportButton::widget([
            'filename' => $this->reportName('Staff Summary Report'),
            'controller' => 'report',
            'anchorOptions' =>  [
            'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
                'data-toggle' => 'dropdown',
                'aria-haspopup' => true,
                'aria-expanded' => false
            ],
            'printUrl' => ['report/print', 'type' => 'summary', 'report' => 'staff'],
            'pdfUrl' => ['report/export-pdf', 'type' => 'summary', 'report' => 'staff'],
            'csvUrl' => ['report/export-csv', 'type' => 'summary', 'report' => 'staff'],
            'xlsUrl' => ['report/export-xls', 'type' => 'summary', 'report' => 'staff'],
            'xlsxUrl' => ['report/export-xlsx', 'type' => 'summary', 'report' => 'staff'],
        ]);
    }
    
     public function getClientCategoryExportSummaryBtn()
    {
        return ExportButton::widget([
            'filename' => $this->reportName('Client Category Summary Report'),
            'controller' => 'report',
            'anchorOptions' =>  [
            'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
                'data-toggle' => 'dropdown',
                'aria-haspopup' => true,
                'aria-expanded' => false
            ],
            'printUrl' => ['report/print', 'type' => 'summary', 'report' => 'client-category'],
            'pdfUrl' => ['report/export-pdf', 'type' => 'summary', 'report' => 'client-category'],
            'csvUrl' => ['report/export-csv', 'type' => 'summary', 'report' => 'client-category'],
            'xlsUrl' => ['report/export-xls', 'type' => 'summary', 'report' => 'client-category'],
            'xlsxUrl' => ['report/export-xlsx', 'type' => 'summary', 'report' => 'client-category'],
        ]);
    }

    public function getAicsExportTransactionBtn()
    {
        return  ExportButton::widget([
            'filename' => $this->reportName('AICS Transaction Report'),
            'controller' => 'report',
            'anchorOptions' =>  [
            'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
                'data-toggle' => 'dropdown',
                'aria-haspopup' => true,
                'aria-expanded' => false
            ],
            'printUrl' => [
                'report/print', 
                'report' => 'aics',
                'emergency_welfare_program' => [
                    Transaction::AICS_MEDICAL,
                    Transaction::AICS_FINANCIAL,
                    Transaction::AICS_LABORATORY_REQUEST,
                ],
                'date_range' => $this->date_range,
            ],
            'pdfUrl' => [
                'report/export-pdf',  
                'report' => 'aics',
                'emergency_welfare_program' => [
                    Transaction::AICS_MEDICAL,
                    Transaction::AICS_FINANCIAL,
                    Transaction::AICS_LABORATORY_REQUEST,
                ],
                'date_range' => $this->date_range,
            ],
            'csvUrl' => [
                'report/export-csv',  
                'report' => 'aics',
                'emergency_welfare_program' => [
                    Transaction::AICS_MEDICAL,
                    Transaction::AICS_FINANCIAL,
                    Transaction::AICS_LABORATORY_REQUEST,
                ],
                'date_range' => $this->date_range,
            ],
            'xlsUrl' => [
                'report/export-xls',  
                'report' => 'aics',
                'emergency_welfare_program' => [
                    Transaction::AICS_MEDICAL,
                    Transaction::AICS_FINANCIAL,
                    Transaction::AICS_LABORATORY_REQUEST,
                ],
                'date_range' => $this->date_range,
            ],
            'xlsxUrl' => [
                'report/export-xlsx',  
                'report' => 'aics',
                'emergency_welfare_program' => [
                    Transaction::AICS_MEDICAL,
                    Transaction::AICS_FINANCIAL,
                    Transaction::AICS_LABORATORY_REQUEST,
                ],
                'date_range' => $this->date_range,
            ],
        ]);
    }

    public function getTransactionTypeExportTransactionBtn()
    {
        return  ExportButton::widget([
            'filename' => $this->reportName('Transaction Type Report'),
            'controller' => 'report',
            'anchorOptions' =>  [
            'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
                'data-toggle' => 'dropdown',
                'aria-haspopup' => true,
                'aria-expanded' => false
            ],
            'printUrl' => [
                'report/print', 
                'report' => 'transaction-type',
                'date_range' => $this->date_range,
            ],
            'pdfUrl' => [
                'report/export-pdf', 
                'report' => 'transaction-type',
                'date_range' => $this->date_range,
            ],
            'csvUrl' => [
                'report/export-csv', 
                'report' => 'transaction-type',
                'date_range' => $this->date_range,
            ],
            'xlsUrl' => [
                'report/export-xls', 
                'report' => 'transaction-type',
                'date_range' => $this->date_range,
            ],
            'xlsxUrl' => [
                'report/export-xlsx', 
                'report' => 'transaction-type',
                'date_range' => $this->date_range,
            ],
        ]);
    }

    public function getCertificationExportTransactionBtn()
    {
        return  ExportButton::widget([
            'filename' => $this->reportName('Certification Transaction Report'),
            'controller' => 'report',
            'anchorOptions' =>  [
            'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
                'data-toggle' => 'dropdown',
                'aria-haspopup' => true,
                'aria-expanded' => false
            ],
            'printUrl' => [
                'report/print', 
                'report' => 'certification',
                'transaction_type' => [
                    Transaction::SENIOR_CITIZEN_ID_APPLICATION,
                    Transaction::CERTIFICATE_OF_INDIGENCY,
                    Transaction::FINANCIAL_CERTIFICATION,
                    Transaction::SOCIAL_CASE_STUDY_REPORT,
                ],
                'date_range' => $this->date_range,
            ],
            'pdfUrl' => [
                'report/export-pdf',  
                'report' => 'certification',
                'transaction_type' => [
                    Transaction::SENIOR_CITIZEN_ID_APPLICATION,
                    Transaction::CERTIFICATE_OF_INDIGENCY,
                    Transaction::FINANCIAL_CERTIFICATION,
                    Transaction::SOCIAL_CASE_STUDY_REPORT,
                ],
                'date_range' => $this->date_range,
            ],
            'csvUrl' => [
                'report/export-csv',  
                'report' => 'certification',
                'transaction_type' => [
                    Transaction::SENIOR_CITIZEN_ID_APPLICATION,
                    Transaction::CERTIFICATE_OF_INDIGENCY,
                    Transaction::FINANCIAL_CERTIFICATION,
                    Transaction::SOCIAL_CASE_STUDY_REPORT,
                ],
                'date_range' => $this->date_range,
            ],
            'xlsUrl' => [
                'report/export-xls',  
                'report' => 'certification',
                'transaction_type' => [
                    Transaction::SENIOR_CITIZEN_ID_APPLICATION,
                    Transaction::CERTIFICATE_OF_INDIGENCY,
                    Transaction::FINANCIAL_CERTIFICATION,
                    Transaction::SOCIAL_CASE_STUDY_REPORT,
                ],
                'date_range' => $this->date_range,
            ],
            'xlsxUrl' => [
                'report/export-xlsx',  
                'report' => 'certification',
                'transaction_type' => [
                    Transaction::SENIOR_CITIZEN_ID_APPLICATION,
                    Transaction::CERTIFICATE_OF_INDIGENCY,
                    Transaction::FINANCIAL_CERTIFICATION,
                    Transaction::SOCIAL_CASE_STUDY_REPORT,
                ],
                'date_range' => $this->date_range,
            ],
        ]);
    }

    public function getTransactionTypeExportColumns()
    {
        return [];
    }

    public function getCertificationExportColumns()
    {
        return [];
    }

    public function getAicsExportColumns()
    {
        return [];
    }

    public function getTransactionTypeExcelIgnoreAttributes()
    {
        return [];
    }

    public function getAicsExcelIgnoreAttributes()
    {
        return [];
    }

    public function getCertificationExcelIgnoreAttributes()
    {
        return [];
    }

    public function getTransactionTypeColumns($params=[])
    {
        $transaction = new Transaction();
        $columns = $transaction->gridColumns;
        unset($columns['active']);

        return $columns;
    }

    public function getCertificationColumns($params=[])
    {
        $transaction = new Transaction();
        $columns = $transaction->gridColumns;
        unset($columns['active']);

        return $columns;
    }

    public function aics_transaction_data($date_range='')
    {
        $date_range = $date_range ?: $this->date_range;
        $current_date = $this->current_date;
        $curYear = App::formatter()->asDateToTimezone('', 'Y');

        $emergency_welfare_programs = [
            Transaction::AICS_MEDICAL,
            Transaction::AICS_FINANCIAL,
            Transaction::AICS_LABORATORY_REQUEST,
            Transaction::EDUCATIONAL_ASSISTANCE,
            Transaction::FOOD_ASSISTANCE,
            Transaction::FINANCIAL_AND_OTHER_ASSISTANCE,
        ];

        $date_range = $date_range ?: implode(' - ', [
            date('Y-m-d', strtotime($curYear . '-01-01')),
            date('Y-m-d', strtotime($curYear .'-12-31')),
        ]);

        $gen = App::component('general');

        $dates = explode( ' - ', $date_range);
        $start = date("Y-m-d", strtotime($dates[0]) ); 
        $end = date("Y-m-d",  $current_date?strtotime($current_date):strtotime($dates[1])   ); 


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
                    ->select(['COUNT("*") as total', 'emergency_welfare_program'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y')" => date('Y', $date),
                        'emergency_welfare_program' => $emergency_welfare_programs
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('emergency_welfare_program')
                    ->asArray()
                    ->all();
            }

        }
        elseif ($month > 1) {
            for ($i=0; $i < ($month + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'months');

                $labels[] = date('F', $date);

                $records[] = Transaction::find()
                    ->select(['COUNT("*") as total', 'emergency_welfare_program'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y-%m')" => date('Y-m', $date),
                        'emergency_welfare_program' => $emergency_welfare_programs
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('emergency_welfare_program')
                    ->asArray()
                    ->all();
            }
        }
        else {
            for ($i=0; $i < ($day + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'days');

                $labels[] = date('d', $date);

                $records[] = Transaction::find()
                    ->select(['COUNT("*") as total', 'emergency_welfare_program'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y-%m-%d')" => date('Y-m-d', $date),
                        'emergency_welfare_program' => $emergency_welfare_programs
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('emergency_welfare_program')
                    ->asArray()
                    ->all();
            }
        }

        $arr = [];

        foreach ($records as $key => $record) {
            $arr[] = ArrayHelper::map($record, 'emergency_welfare_program', 'total');
        }

        $data = [];
        foreach ($emergency_welfare_programs as $ewp) {
            foreach ($arr as $key => $s) {
                $data[App::keyMapParams('emergency_welfare_programs')[$ewp]][] = (int) ($s[$ewp] ?? 0);
            }
        }

        $series = [];
        foreach ($data as $ewp => $d) {
            $series[] = [
                'name' => $ewp,
                'data' => $d
            ];
        }

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    public function transaction_type_transaction_data($date_range='')
    {
        $date_range = $date_range ?: $this->date_range;
        $curYear = App::formatter()->asDateToTimezone('', 'Y');

        $transaction_types = [
            Transaction::EMERGENCY_WELFARE_PROGRAM,
            Transaction::SENIOR_CITIZEN_ID_APPLICATION,
            Transaction::SOCIAL_PENSION,
            Transaction::DEATH_ASSISTANCE,
            Transaction::CERTIFICATE_OF_INDIGENCY,
            Transaction::FINANCIAL_CERTIFICATION,
            Transaction::SOCIAL_CASE_STUDY_REPORT,
            Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING,
            Transaction::CERTIFICATE_OF_COMPLIANCE,
            Transaction::CERTIFICATE_OF_APPARENT_DISABILITY,
        ];

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
                    ->select(['COUNT("*") as total', 'transaction_type'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y')" => date('Y', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('transaction_type')
                    ->asArray()
                    ->all();
            }

        }
        elseif ($month > 1) {
            for ($i=0; $i < ($month + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'months');

                $labels[] = date('F', $date);

                $records[] = Transaction::find()
                    ->select(['COUNT("*") as total', 'transaction_type'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y-%m')" => date('Y-m', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('transaction_type')
                    ->asArray()
                    ->all();
            }
        }
        else {
            for ($i=0; $i < ($day + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'days');

                $labels[] = date('d', $date);

                $records[] = Transaction::find()
                    ->select(['COUNT("*") as total', 'transaction_type'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y-%m-%d')" => date('Y-m-d', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('transaction_type')
                    ->asArray()
                    ->all();
            }
        }

        $arr = [];

        foreach ($records as $key => $record) {
            $arr[] = ArrayHelper::map($record, 'transaction_type', 'total');
        }

        $data = [];
        foreach ($transaction_types as $ewp) {
            foreach ($arr as $key => $s) {
                $data[App::keyMapParams('transaction_types')[$ewp]][] = (int) ($s[$ewp] ?? 0);
            }
        }

        $series = [];
        foreach ($data as $ewp => $d) {
            $series[] = [
                'name' => $ewp,
                'data' => $d
            ];
        }

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    public function certification_transaction_data($date_range='')
    {
        $date_range = $date_range ?: $this->date_range;
        $curYear = App::formatter()->asDateToTimezone('', 'Y');
        $current_date = $this->current_date;
        $transaction_types = [
            Transaction::CERTIFICATE_OF_INDIGENCY,
            Transaction::FINANCIAL_CERTIFICATION,
            Transaction::SOCIAL_CASE_STUDY_REPORT,
            Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING,
            Transaction::CERTIFICATE_OF_COMPLIANCE,
            Transaction::CERTIFICATE_OF_APPARENT_DISABILITY,
        ];

        $date_range = $date_range ?: implode(' - ', [
            date('Y-m-d', strtotime($curYear . '-01-01')),
            date('Y-m-d', strtotime($curYear .'-12-31')),
        ]);

        $gen = App::component('general');

        $dates = explode( ' - ', $date_range);
        $start = date("Y-m-d", strtotime($dates[0]) ); 
        //$end = date("Y-m-d", strtotime($dates[1]) ); 
        $end = date("Y-m-d",  $current_date?strtotime($current_date):strtotime($dates[1])   ); 

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
                
                $records[] = Transaction::find()->alias('t')
                    ->select(['COUNT("*") as total', 'transaction_type'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y')" => date('Y', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['t.status' => 10])
                    ->groupBy('transaction_type')
                    ->asArray()
                    ->all();
            }

        }
        elseif ($month > 1) {
            for ($i=0; $i < ($month + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'months');

                $labels[] = date('F', $date);

                $records[] = Transaction::find()->alias('t')
                    ->select(['COUNT("*") as total', 'transaction_type'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y-%m')" => date('Y-m', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('transaction_type')
                    ->asArray()
                    ->all();
            }
        }
        else {
            for ($i=0; $i < ($day + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'days');

                $labels[] = date('d', $date);

                $records[] = Transaction::find()->alias('t')
                    ->select(['COUNT("*") as total', 'transaction_type'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y-%m-%d')" => date('Y-m-d', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['t.status' => 10])
                    ->groupBy('transaction_type')
                    ->asArray()
                    ->all();
            }
        }

        $arr = [];

        foreach ($records as $key => $record) {
            $arr[] = ArrayHelper::map($record, 'transaction_type', 'total');
        }

        $data = [];
        foreach ($transaction_types as $ewp) {
            foreach ($arr as $key => $s) {
                $data[App::keyMapParams('transaction_types')[$ewp]][] = (int) ($s[$ewp] ?? 0);
            }
        }

        $series = [];
        foreach ($data as $ewp => $d) {
            $series[] = [
                'name' => $ewp,
                'data' => $d
            ];
        }

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    public function emergency_welfare_program_transaction_data($date_range='')
    {
        $date_range = $date_range ?: $this->date_range;
        $current_date = $this->current_date;
        $curYear = App::formatter()->asDateToTimezone('', 'Y');

        $transaction_types = [
            Transaction::SOCIAL_PENSION,
            Transaction::DEATH_ASSISTANCE,
        ];

        $emergency_welfare_programs = [
            Transaction::AICS_MEDICAL,
            Transaction::AICS_MEDICAL_MEDICINE,
            Transaction::AICS_LABORATORY_REQUEST,
            Transaction::BALIK_PROBINSYA_PROGRAM,
            Transaction::EDUCATIONAL_ASSISTANCE,
            Transaction::FOOD_ASSISTANCE,
            Transaction::FINANCIAL_AND_OTHER_ASSISTANCE,
        ];

        $date_range = $date_range ?: implode(' - ', [
            date('Y-m-d', strtotime($curYear . '-01-01')),
            date('Y-m-d', strtotime($curYear .'-12-31')),
        ]);

        $gen = App::component('general');

        $dates = explode( ' - ', $date_range);
        $start = date("Y-m-d", strtotime($dates[0]) ); 
       // $end = date("Y-m-d", strtotime($dates[1]) ); 
        $end = date("Y-m-d",  $current_date?strtotime($current_date):strtotime($dates[1])   ); 

        $day   = $gen->dateDiff($start, $end);
        $month = $gen->dateDiff($start, $end, 'm');
        $year  = $gen->dateDiff($start, $end, 'y');

        $labels = [];
        $records = [];
        $records2 = [];
        
       // echo $date_range;
      // echo $month;

        if ($year > 1) {
            $year = ceil((float)$year . '.' . $month);
            for ($i=0; $i < ($year + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'years');

                $labels[] = date('Y', $date);
                
                $records[] = Transaction::find()
                    ->select(['COUNT("*") as total', 'emergency_welfare_program'])
                    ->andWhere([
                        "DATE_FORMAT(created_at, '%Y')" => date('Y', $date),
                        'emergency_welfare_program' => $emergency_welfare_programs,
                        'transaction_type' => Transaction::EMERGENCY_WELFARE_PROGRAM
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('emergency_welfare_program')
                    ->asArray()
                    ->all();

                $records2[] = Transaction::find()
                    ->select(['COUNT("*") as total', 'transaction_type'])
                    ->andWhere([
                        "DATE_FORMAT(created_at, '%Y')" => date('Y', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('transaction_type')
                    ->asArray()
                    ->all();
            }

        }
        elseif ($month > 1) {
            for ($i=0; $i < ($month + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'months');

                $labels[] = date('F', $date);
                
              //  echo date('F', $date).' ';

                $records[] = Transaction::find()
                    ->select(['COUNT("*") as total', 'emergency_welfare_program'])
                    ->andWhere([
                        "DATE_FORMAT(created_at, '%Y-%m')" => date('Y-m', $date),
                        'emergency_welfare_program' => $emergency_welfare_programs,
                        'transaction_type' => Transaction::EMERGENCY_WELFARE_PROGRAM
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('emergency_welfare_program')
                    ->asArray()
                    ->all();

              
                $records2[] = Transaction::find()
                    ->select(['COUNT("*") as total', 'transaction_type'])
                    ->andWhere([
                        "DATE_FORMAT(created_at, '%Y-%m')" => date('Y-m', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('transaction_type')
                    ->asArray()
                    ->all();
                   
            }
        }
        else {
            for ($i=0; $i < ($day + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'days');

                $labels[] = date('d', $date);

                $records[] = Transaction::find()
                    ->select(['COUNT("*") as total', 'emergency_welfare_program'])
                    ->andWhere([
                        "DATE_FORMAT(created_at, '%Y-%m-%d')" => date('Y-m-d', $date),
                        'emergency_welfare_program' => $emergency_welfare_programs,
                        'transaction_type' => Transaction::EMERGENCY_WELFARE_PROGRAM
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('emergency_welfare_program')
                    ->asArray()
                    ->all();

                $records2[] = Transaction::find()
                    ->select(['COUNT("*") as total', 'transaction_type'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y-%m-%d')" => date('Y-m-d', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('transaction_type')
                    ->asArray()
                    ->all();
            }
        }

        $arr = [];
        
        //print_r($records2);

        foreach ($records as $key => $record) {
          $arr[] = ArrayHelper::map($record, 'emergency_welfare_program', 'total');
        }

        foreach ($records2 as $key => $record) {
          $arr2[] = ArrayHelper::map($record, 'transaction_type', 'total');
        }

        //print_r($arr);

        $data = [];
        foreach ($emergency_welfare_programs as $ewp) {
            foreach ($arr as $key => $s) {
                $data[App::keyMapParams('emergency_welfare_programs')[$ewp]][] = (int) ($s[$ewp] ?? 0);
            }
        }

        foreach ($transaction_types as $ewp) {
            foreach ($arr2 as $key => $s) {
                $data[App::keyMapParams('transaction_types')[$ewp]][] = (int) ($s[$ewp] ?? 0);
            }
        }

        $series = [];
        foreach ($data as $ewp => $d) {
            $series[] = [
                'name' => $ewp,
                'data' => $d
            ];
        }

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }





   public function client_category_transaction_data($date_range='')
    {
        $date_range = $date_range ?: $this->date_range;
        $curYear = App::formatter()->asDateToTimezone('', 'Y');

       //$users = User::find()->alias('t')->andWhere(" t.id in (select created_by from {{%transactions}} where Year(created_at)=Year(Now()) ) ")
        //->asArray()
        //->all();
        
        $client_categories = App::params('client_categories');

        $transaction_types = [
            Transaction::EMERGENCY_WELFARE_PROGRAM,
            Transaction::SENIOR_CITIZEN_ID_APPLICATION,
            Transaction::SOCIAL_PENSION,
            Transaction::DEATH_ASSISTANCE,
            Transaction::CERTIFICATE_OF_INDIGENCY,
            Transaction::FINANCIAL_CERTIFICATION,
            Transaction::SOCIAL_CASE_STUDY_REPORT,
            Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING,
            Transaction::CERTIFICATE_OF_COMPLIANCE,
            Transaction::CERTIFICATE_OF_APPARENT_DISABILITY,
        ];
        
        
        $transaction_type = Yii::$app->request->get('transaction_type'); // $this->transaction_type;
        
        if($transaction_type==1){
           $transaction_types=[1,4]; 
        }elseif($transaction_type==6){
            $transaction_types=[5,6,7,8,9,10]; 
        }

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

          $client_cat=[];
          
          $staff_id = Yii::$app->request->get('staff');
        
          for ($i=0; $i < ($month + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'months');

                $labels[] = date('F', $date);
                
                foreach ($client_categories as $id => $row) {
                    
                  $trans= Transaction::find()
                    ->select(['COUNT("*") as total', 'created_by'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y-%m')" => date('Y-m', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['status' => 10])
                    ->andWhere("JSON_CONTAINS(`client_category`, '[\"".$row['label']."\"]', '$')")  //
                    ->andFilterWhere(['created_by' => $staff_id])
                    //->groupBy('created_by')
                    ->asArray()
                    ->one();
                    
                    $client_cat[$row['id']][$i] =$trans['total']?:0 ;
                    
                }
               
               
                    
           }
           
           
        
        
          foreach ($client_categories as $id => $row) {
                    $series[] = ['name' => $row['label'],'data' => $client_cat[$row['id']] ];
                
          }
         
         

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }



   public function staff_transaction_data($date_range='')
    {
        $date_range = $date_range ?: $this->date_range;
        $curYear = App::formatter()->asDateToTimezone('', 'Y');

       $users = User::find()->alias('t')->andWhere(" t.id in (select created_by from {{%transactions}} where Year(created_at)=Year(Now()) ) ")
        //->asArray()
        ->all();

        $transaction_types = [
            Transaction::EMERGENCY_WELFARE_PROGRAM,
            Transaction::SENIOR_CITIZEN_ID_APPLICATION,
            Transaction::SOCIAL_PENSION,
            Transaction::DEATH_ASSISTANCE,
            Transaction::CERTIFICATE_OF_INDIGENCY,
            Transaction::FINANCIAL_CERTIFICATION,
            Transaction::SOCIAL_CASE_STUDY_REPORT,
            Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING,
            Transaction::CERTIFICATE_OF_COMPLIANCE,
            Transaction::CERTIFICATE_OF_APPARENT_DISABILITY,
        ];
        
        
        $transaction_type = Yii::$app->request->get('transaction_type'); // $this->transaction_type;
        
        if($transaction_type==1){
           $transaction_types=[1,4]; 
        }elseif($transaction_type==6){
            $transaction_types=[5,6,7,8,9,10]; 
        }

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
                    ->select(['COUNT("*") as total', 'created_by'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y')" => date('Y', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('created_by')
                    ->asArray()
                    ->all();
            }

        }
        elseif ($month > 1) {
            for ($i=0; $i < ($month + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'months');

                $labels[] = date('F', $date);

                $records[] = Transaction::find()
                    ->select(['COUNT("*") as total', 'created_by'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y-%m')" => date('Y-m', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('created_by')
                    ->asArray()
                    ->all();
            }
        }
        else {
            for ($i=0; $i < ($day + 1); $i++) { 
                $date = strtotime($start . '+' . $i . 'days');

                $labels[] = date('d', $date);

                $records[] = Transaction::find()
                    ->select(['COUNT("*") as total', 'created_by'])
                    ->where([
                        "DATE_FORMAT(created_at, '%Y-%m-%d')" => date('Y-m-d', $date),
                        'transaction_type' => $transaction_types
                    ])
                    ->andWhere(['status' => 10])
                    ->groupBy('created_by')
                    ->asArray()
                    ->all();
            }
        }

        $arr = [];

        foreach ($records as $key => $record) {
            $arr[] = ArrayHelper::map($record, 'created_by', 'total');
        }
        
       
         // print_r($arr);

        $data = [];
      
        foreach ($users as $id => $user) {
            foreach ($arr as $key => $s) {
                $data[$user->fullname][] = $s[$user->id]?:0;
            }
            
        }
        
   

        $series = [];
       
        foreach ($data as $ewp => $d) {
            $series[] = [
                'name' => $ewp,
                'data' => $d
            ];
        }
       
      
        
       

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }






    public function aics_data()
    {
        $emergency_welfare_programs = [
            'medical' => Transaction::AICS_MEDICAL,
            'financial' => Transaction::AICS_FINANCIAL,
            'laboratory_request' => Transaction::AICS_LABORATORY_REQUEST,
            'educational_assistance' => Transaction::EDUCATIONAL_ASSISTANCE,
            'food_assistance' => Transaction::FOOD_ASSISTANCE,
            'finacial_and_other_assistance' => Transaction::FINANCIAL_AND_OTHER_ASSISTANCE,
        ];
       //date('Y-m-d h:i:s A')
       // $this->current_date='2024-09-10';
       // $current_date='2024-09-10'; //$this->current_date;
        //$this->current_date='2024-08-02';
        $ranges = [
            'current_week' => $this->currentWeek(),
            'current_month' => $this->currentMonth(),
            'current_quarter' => $this->currentQuarter(),
            'current_year' => $this->currentYear(),
        ];

        $data = [];
        $total_female = 0;
        $total_male = 0;

        foreach ($emergency_welfare_programs as $ewp => $emergency_welfare_program) {
            $models = [];
            foreach ($ranges as $key => $range) {
                $query = Transaction::find()
                    ->alias('t')
                    ->joinWith(['member m', 'member.gender s'])
                    ->select([
                        's.label',
                        'm.sex',
                        'COUNT("*") AS total',
                        'sum(if(t.status<>28,t.amount,0)) AS t_amount'
                    ])
                    ->emergency_welfare_program()
                    ->andWhere(['t.status' => 10])
                    ->daterange($range)
                    ->groupBy('m.sex');

                if ($emergency_welfare_program == Transaction::AICS_MEDICAL) {
                    $query->medical();
                }
                elseif ($emergency_welfare_program == Transaction::AICS_FINANCIAL) {
                    $query->financial();
                }
                elseif ($emergency_welfare_program == Transaction::AICS_LABORATORY_REQUEST) {
                    $query->laboratory_request();
                }elseif ($emergency_welfare_program == Transaction::EDUCATIONAL_ASSISTANCE) {
                    $query->andWhere(['emergency_welfare_program' => Transaction::EDUCATIONAL_ASSISTANCE ]);
                }elseif ($emergency_welfare_program == Transaction::FOOD_ASSISTANCE) {
                    $query->andWhere(['emergency_welfare_program' => Transaction::FOOD_ASSISTANCE ]);
                }elseif ($emergency_welfare_program == Transaction::FINANCIAL_AND_OTHER_ASSISTANCE) {
                    $query->andWhere(['emergency_welfare_program' => Transaction::FINANCIAL_AND_OTHER_ASSISTANCE ]);
                }


                $records = $query->asArray()
                    ->all();
                $models[$key] = ArrayHelper::map($records, 'label', 'total');
                $models_amount[$key] = ArrayHelper::map($records, 'label', 't_amount');
            }
            foreach ($models as $key => &$model) {
                $model['Male'] = (int)($model['Male'] ?? 0);
                $model['Female'] = (int)($model['Female'] ?? 0);
                $model['Total'] = (int)$model['Male'] + (int)$model['Female'];
                $model['Total_Amount'] = (int)$models_amount[$key]['Male'] + (int)$models_amount[$key]['Female'];
            }
            $total_female += $models['current_year']['Female'];
            $total_male += $models['current_year']['Male'];
            $data[$ewp] = $models;
        }


        $data['total_female'] = $total_female;
        $data['total_male'] = $total_male;
        $data['total_medical'] = $data['medical']['current_year']['Total'];
        $data['total_financial'] = $data['financial']['current_year']['Total'];
        $data['total_laboratory_request'] = $data['laboratory_request']['current_year']['Total'];
        $data['total_educational_assistance'] = $data['educational_assistance']['current_year']['Total'];
        $data['total_food_assistance'] = $data['food_assistance']['current_year']['Total'];
        $data['total_finacial_and_other_assistance'] = $data['finacial_and_other_assistance']['current_year']['Total'];
            
        return $data;
    }
    
    
    
    
    
    
    
     public function staff_data()
    {
        $transaction_types = App::params('transaction_types');
        
        $users = User::find()->alias('t')->andWhere(" t.id in (select created_by from {{%transactions}} where Year(created_at)=Year(Now()) ) ")
        //->asArray()
        ->all();

        $ranges = [
            'current_week' => $this->currentWeek(),
            'current_month' => $this->currentMonth(),
            'current_quarter' => $this->currentQuarter(),
            'current_year' => $this->currentYear(),
        ];

        $data = [];
        $total_female = 0;
        $total_male = 0;
        
        $transaction_type = Yii::$app->request->get('transaction_type'); // $this->transaction_type;
        
        if($transaction_type==1){
           $transaction_type=[1,4]; 
        }elseif($transaction_type==6){
            $transaction_type=[5,6,7,8,9,10]; 
        }

        foreach ($users as $id => $user) {

            $models = [];

            foreach ($ranges as $key => $range) {
                $records = Transaction::find()
                    ->alias('t')
                    ->joinWith(['member m', 'member.gender s'])
                    ->select([
                        's.label',
                        'm.sex',
                        'COUNT("*") AS total',
                        'sum(if(t.status<>28,t.amount,0)) AS t_amount'
                    ])
                    ->daterange($range)
                    ->andWhere(['t.status' => 10])
                    //->andWhere(['transaction_type' => $id])  // created_by
                     ->andWhere(['t.created_by' => $user['id'] ])  //
                     ->andFilterWhere([
                         'transaction_type' =>$transaction_type
                     ]) 
                    ->groupBy('m.sex')
                    ->asArray()
                    ->all();

                $models[$key] = ArrayHelper::map($records, 'label', 'total');
                $models_amount[$key] = ArrayHelper::map($records, 'label', 't_amount');
            }
            
            
           // print_r($models);

            foreach ($models as $key => &$model) {
                $model['Male'] = (int)($model['Male'] ?? 0);
                $model['Female'] = (int)($model['Female'] ?? 0);
                $model['Total'] = (int)$model['Male'] + (int)$model['Female'];
                $model['Total_Amount'] = (int)$models_amount[$key]['Male'] + (int)$models_amount[$key]['Female'];
            }
            
            
         


            $total_female += $models['current_year']['Female'];
            $total_male += $models['current_year']['Male'];
            $users_slug=  $user->fullname;  //$user['username']; //str_replace('-', '_', $user['slug']);
            $data[$users_slug] = $models;
            $data['total_'.$users_slug]=$data[$users_slug]['current_year']['Total'];
            
            //echo $data[$users_slug]['current_year']['Total_Amount'].', ';
           
            
        }

        $data['total_female'] = $total_female;
        $data['total_male'] = $total_male;
        
        
        $data['users'] = $users;
        

        /*
        $data['total_emergency_welfare_program'] = $data['emergency_welfare_program']['current_year']['Total'];
        $data['total_senior_citizen_id_application'] = $data['senior_citizen_id_application']['current_year']['Total'];
        $data['total_social_pension'] = $data['social_pension']['current_year']['Total'];
        $data['total_death_assistance'] = $data['death_assistance']['current_year']['Total'];
        $data['total_certificate_of_indigency'] = $data['certificate_of_indigency']['current_year']['Total'];
        $data['total_financial_certification'] = $data['financial_certification']['current_year']['Total'];
        $data['total_social_case_study_report'] = $data['social_case_study_report']['current_year']['Total'];
        $data['total_certificate_of_marriage_counseling'] = $data['certificate_of_marriage_counseling']['current_year']['Total'];
        $data['total_certificate_of_compliance'] = $data['certificate_of_compliance']['current_year']['Total'];
        $data['total_certificate_of_apparent_disability'] = $data['certificate_of_apparent_disability']['current_year']['Total'];
       */
        return $data;
    }
    
    
    
    
    public function client_category_data()
    {
        $transaction_types = App::params('transaction_types');
        $client_categories = App::params('client_categories');
        
        
       // $users = User::find()->alias('t')->andWhere(" t.id in (select created_by from {{%transactions}} where Year(created_at)=Year(Now()) ) ")
        //->asArray()
       // ->all();

        $ranges = [
            'current_week' => $this->currentWeek(),
            'current_month' => $this->currentMonth(),
            'current_quarter' => $this->currentQuarter(),
            'current_year' => $this->currentYear(),
        ];

        $data = [];
        $total_female = 0;
        $total_male = 0;
        
        $transaction_type = Yii::$app->request->get('transaction_type'); // $this->transaction_type;
        $staff_id = Yii::$app->request->get('staff');
        
        if($transaction_type==1){
           $transaction_type=[1,4]; 
        }elseif($transaction_type==6){
            $transaction_type=[5,6,7,8,9,10]; 
        }

        foreach ($client_categories as $id => $row) {

            $models = [];

            foreach ($ranges as $key => $range) {
                $records = Transaction::find()
                    ->alias('t')
                    ->joinWith(['member m', 'member.gender s'])
                    ->select([
                        's.label',
                        'm.sex',
                        'COUNT("*") AS total',
                        'sum(if(t.status<>28,t.amount,0)) AS t_amount'
                    ])
                    ->daterange($range)
                    ->andWhere(['t.status' => 10])
                    //->andWhere(['transaction_type' => $id])  // created_by
                    // ->andWhere(['t.created_by' => $user['id'] ])  
                     ->andWhere("JSON_CONTAINS(`client_category`, '[\"".$row['label']."\"]', '$')")  //
                     ->andFilterWhere([
                         'transaction_type' =>$transaction_type,
                         't.created_by'=>$staff_id
                     ]) 
                    ->groupBy('m.sex')
                    ->asArray()
                    ->all();

                $models[$key] = ArrayHelper::map($records, 'label', 'total');
                $models_amount[$key] = ArrayHelper::map($records, 'label', 't_amount');
            }
            
            
           // print_r($models);

            foreach ($models as $key => &$model) {
                $model['Male'] = (int)($model['Male'] ?? 0);
                $model['Female'] = (int)($model['Female'] ?? 0);
                $model['Total'] = (int)$model['Male'] + (int)$model['Female'];
                $model['Total_Amount'] = (int)$models_amount[$key]['Male'] + (int)$models_amount[$key]['Female'];
            }
            
            
         


            $total_female += $models['current_year']['Female'];
            $total_male += $models['current_year']['Male'];
            $users_slug=  $row['id']; //$user->fullname;  //$user['username']; //str_replace('-', '_', $user['slug']);
            $data[$users_slug] = $models;
            $data['total_'.$users_slug]=$data[$users_slug]['current_year']['Total'];
            
            //echo $data[$users_slug]['current_year']['Total_Amount'].', ';
           
            
        }
        
        // print_r($data);
         //exit;

        $data['total_female'] = $total_female;
        $data['total_male'] = $total_male;
        
        
       // $data['users'] = $users;
        $data['client_categories'] = $client_categories;
        

        /*
        $data['total_emergency_welfare_program'] = $data['emergency_welfare_program']['current_year']['Total'];
        $data['total_senior_citizen_id_application'] = $data['senior_citizen_id_application']['current_year']['Total'];
        $data['total_social_pension'] = $data['social_pension']['current_year']['Total'];
        $data['total_death_assistance'] = $data['death_assistance']['current_year']['Total'];
        $data['total_certificate_of_indigency'] = $data['certificate_of_indigency']['current_year']['Total'];
        $data['total_financial_certification'] = $data['financial_certification']['current_year']['Total'];
        $data['total_social_case_study_report'] = $data['social_case_study_report']['current_year']['Total'];
        $data['total_certificate_of_marriage_counseling'] = $data['certificate_of_marriage_counseling']['current_year']['Total'];
        $data['total_certificate_of_compliance'] = $data['certificate_of_compliance']['current_year']['Total'];
        $data['total_certificate_of_apparent_disability'] = $data['certificate_of_apparent_disability']['current_year']['Total'];
       */
        return $data;
    }


    public function aics_search($params=[])
    {
        $query = Transaction::find()
            ->alias('t')
            ->emergency_welfare_program()
            ->joinWith(['member m', 'createdBy'])
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
                
        $query->andFilterWhere(['or', 
            ['like', 'm.qr_id', $this->keywords],  
            ['like', 'm.first_name', $this->keywords],  
            ['like', 'm.middle_name', $this->keywords],  
            ['like', 'm.last_name', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.first_name)', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.middle_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.middle_name, " ", m.first_name)', $this->keywords]
                ,  
            ['like', 'remarks', $this->keywords],  
        ]);

        $query->andFilterWhere([
            't.status' => $this->status,
            't.emergency_welfare_program' => $this->emergency_welfare_program,
        ]);

        $query->andWhere([
            't.emergency_welfare_program' => [
                Transaction::AICS_MEDICAL,
                Transaction::AICS_FINANCIAL,
                Transaction::AICS_LABORATORY_REQUEST,
                Transaction::EDUCATIONAL_ASSISTANCE,
                Transaction::FOOD_ASSISTANCE,
                Transaction::FINANCIAL_AND_OTHER_ASSISTANCE,
            ],
            't.status' => 10
        ]);
        

        $query->daterange($this->date_range);
        return $dataProvider;
    }
    
    
    
    
    public function staff_search($params=[])
    {
        $query = Transaction::find()
            ->alias('t')
            ->emergency_welfare_program()
            ->joinWith(['member m', 'createdBy'])
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
                
        $query->andFilterWhere(['or', 
            ['like', 'm.qr_id', $this->keywords],  
            ['like', 'm.first_name', $this->keywords],  
            ['like', 'm.middle_name', $this->keywords],  
            ['like', 'm.last_name', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.first_name)', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.middle_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.middle_name, " ", m.first_name)', $this->keywords]
                ,  
            ['like', 'remarks', $this->keywords],  
        ]);

        $query->andFilterWhere([
            't.status' => $this->status,
            't.emergency_welfare_program' => $this->emergency_welfare_program,
        ]);

        $query->andWhere([
            't.emergency_welfare_program' => [
                Transaction::AICS_MEDICAL,
                Transaction::AICS_FINANCIAL,
                Transaction::AICS_LABORATORY_REQUEST,
                Transaction::EDUCATIONAL_ASSISTANCE,
                Transaction::FOOD_ASSISTANCE,
                Transaction::FINANCIAL_AND_OTHER_ASSISTANCE,
                
            ],
            't.status' => 10
        ]);

        $query->daterange($this->date_range);
        return $dataProvider;
    }
    
    
    public function client_category_search($params=[])
    {
        $query = Transaction::find()
            ->alias('t')
            ->emergency_welfare_program()
            ->joinWith(['member m', 'createdBy'])
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
                
        $query->andFilterWhere(['or', 
            ['like', 'm.qr_id', $this->keywords],  
            ['like', 'm.first_name', $this->keywords],  
            ['like', 'm.middle_name', $this->keywords],  
            ['like', 'm.last_name', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.first_name)', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.middle_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.middle_name, " ", m.first_name)', $this->keywords]
                ,  
            ['like', 'remarks', $this->keywords],  
        ]);

        $query->andFilterWhere([
            't.status' => $this->status,
            't.emergency_welfare_program' => $this->emergency_welfare_program,
        ]);

        $query->andWhere([
            't.emergency_welfare_program' => [
                Transaction::AICS_MEDICAL,
                Transaction::AICS_FINANCIAL,
                Transaction::AICS_LABORATORY_REQUEST,
                Transaction::EDUCATIONAL_ASSISTANCE,
                Transaction::FOOD_ASSISTANCE,
                Transaction::FINANCIAL_AND_OTHER_ASSISTANCE,
            ],
            't.status' => 10
        ]);

        $query->daterange($this->date_range);
        return $dataProvider;
    }

    public function certification_data()
    {
        $transaction_types = [
            'senior_citizen_id_application' => Transaction::SENIOR_CITIZEN_ID_APPLICATION,
            'certificate_of_indigency' => Transaction::CERTIFICATE_OF_INDIGENCY,
            'financial_certification' => Transaction::FINANCIAL_CERTIFICATION,
            'social_case_study_report' => Transaction::SOCIAL_CASE_STUDY_REPORT,
            'certificate_of_marriage_counseling' => Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING,
            'certificate_of_compliance' => Transaction::CERTIFICATE_OF_COMPLIANCE,
            'certificate_of_apparent_disability' => Transaction::CERTIFICATE_OF_APPARENT_DISABILITY,
        ];

        $ranges = [
            'current_week' => $this->currentWeek(),
            'current_month' => $this->currentMonth(),
            'current_quarter' => $this->currentQuarter(),
            'current_year' => $this->currentYear(),
        ];

        $data = [];
        $total_female = 0;
        $total_male = 0;

        foreach ($transaction_types as $slug => $transaction_type) {

            $models = [];

            foreach ($ranges as $key => $range) {
                $records = Transaction::find()
                    ->alias('t')
                    ->joinWith(['member m', 'member.gender s'])
                    ->select([
                        's.label',
                        'm.sex',
                        'COUNT("*") AS total'
                    ])
                    ->daterange($range)
                    ->andWhere(['transaction_type' => $transaction_type])
                    ->groupBy('m.sex')
                    ->asArray()
                    ->all();

                $models[$key] = ArrayHelper::map($records, 'label', 'total');
            }

            foreach ($models as $key => &$model) {
                $model['Male'] = (int)($model['Male'] ?? 0);
                $model['Female'] = (int)($model['Female'] ?? 0);
                $model['Total'] = (int)$model['Male'] + (int)$model['Female'];
            }

            $total_female += $models['current_year']['Female'];
            $total_male += $models['current_year']['Male'];
            $data[$slug] = $models;
        }

        $data['total_female'] = $total_female;
        $data['total_male'] = $total_male;

        $data['total_senior_citizen_id_application'] = $data['senior_citizen_id_application']['current_year']['Total'];
        $data['total_certificate_of_indigency'] = $data['certificate_of_indigency']['current_year']['Total'];
        $data['total_financial_certification'] = $data['financial_certification']['current_year']['Total'];
        $data['total_social_case_study_report'] = $data['social_case_study_report']['current_year']['Total'];
        $data['total_certificate_of_marriage_counseling'] = $data['certificate_of_marriage_counseling']['current_year']['Total'];
        $data['total_certificate_of_compliance'] = $data['certificate_of_compliance']['current_year']['Total'];
        $data['total_certificate_of_apparent_disability'] = $data['certificate_of_apparent_disability']['current_year']['Total'];

        return $data;
    }


    public function certification_search($params=[])
    {
        $query = Transaction::find()
            ->alias('t')
            ->joinWith(['member m', 'createdBy'])
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
                
        $query->andFilterWhere(['or', 
            ['like', 'm.qr_id', $this->keywords],  
            ['like', 'm.first_name', $this->keywords],  
            ['like', 'm.middle_name', $this->keywords],  
            ['like', 'm.last_name', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.first_name)', $this->keywords],  
            ['like', 'CONCAT(m.first_name, " ", m.middle_name, " ", m.last_name)', $this->keywords],  
            ['like', 'CONCAT(m.last_name, " ", m.middle_name, " ", m.first_name)', $this->keywords]
                ,  
            ['like', 'remarks', $this->keywords],  
        ]);

        $query->andFilterWhere([
            't.status' => $this->status,
            't.transaction_type' => $this->transaction_type,
        ]);

        $query->andWhere([
            't.transaction_type' => [
                Transaction::SENIOR_CITIZEN_ID_APPLICATION,
                Transaction::CERTIFICATE_OF_INDIGENCY,
                Transaction::FINANCIAL_CERTIFICATION,
                Transaction::SOCIAL_CASE_STUDY_REPORT,
                Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING,
                Transaction::CERTIFICATE_OF_COMPLIANCE,
                Transaction::CERTIFICATE_OF_APPARENT_DISABILITY,
            ],
            't.status' => 10
        ]);

        $query->daterange($this->date_range);
        return $dataProvider;
    }

    public function showAicsGraph($data)
    {
        return $data['total_medical']
            || $data['total_laboratory_request']
            || $data['total_financial']
            || $data['total_male']
            || $data['total_female'];
    }

    public function showEwpGraph($data)
    {
        return $data['total_medical']
            || $data['total_laboratory_request']
            || $data['total_balik_probinsya']
            || $data['total_death_assistance']
            || $data['total_social_pension']
            || $data['total_male']
            || $data['total_female'];
    }

    public function showCertificationGraph($data)
    {
        return $data['total_senior_citizen_id_application']
            || $data['total_certificate_of_indigency']
            || $data['total_financial_certification']
            || $data['total_social_case_study_report']
            || $data['total_male']
            || $data['total_female'];
    }

    public function showTransactionTypeGraph($data)
    {
        return $data['total_emergency_welfare_program']
            || $data['total_senior_citizen_id_application']
            || $data['total_social_pension']
            || $data['total_death_assistance']
            || $data['total_certificate_of_indigency']
            || $data['total_financial_certification']
            || $data['total_social_case_study_report']
            || $data['total_male']
            || $data['total_female'];
    }
}