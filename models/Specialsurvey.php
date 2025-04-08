<?php

namespace app\models;

use Yii;
use app\helpers\App;
use app\helpers\ArrayHelper;
use app\models\form\setting\SurveySettingForm;
use app\widgets\Anchor;

/**
 * This is the model class for table "{{%specialsurvey}}".
 *
 * @property int $id
 * @property string $last_name
 * @property string $first_name
 * @property string|null $middle_name
 * @property string|null $gender
 * @property int|null $age
 * @property string|null $date_of_birth
 * @property string|null $civil_status
 * @property string|null $house_no
 * @property string|null $street
 * @property string|null $barangay
 * @property string|null $municipality
 * @property string|null $province
 * @property string|null $religion
 * @property int|null $mayor_color_id
 * @property int|null $president_color_id
 * @property int|null $vice_mayor_color_id
 * @property int|null $congressman_color_id
 * @property int|null $governor_color_id
 * @property string|null $date_survey
 * @property string|null $remarks
 * @property string|null $status
 * @property int $record_status
 * @property int|null $created_by
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property string|null $updated_at
 */
class Specialsurvey extends ActiveRecord
{
    public $converted_voter; // Define it as a public property

    /**
     * {@inheritdoc}
     */
	 
    public static function tableName()
    {
        return '{{%specialsurvey}}';
    }

    public function config()
    {
        return [
            'controllerID' => 'specialsurvey',
            'mainAttribute' => 'id',
            'paramName' => 'id',
            'dateAttribute' => 'date_survey'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->setRules([
            [['last_name', 'first_name'], 'required'],
            [['age', 'criteria1_color_id', 'criteria2_color_id', 'criteria3_color_id', 'criteria4_color_id', 'criteria5_color_id'], 'integer'],
            [['date_of_birth', 'date_survey', 'survey_name', 'household_no','encoder', 'leader'], 'safe'],
            [['civil_status', 'house_no','purok', 'sitio', 'barangay', 'municipality', 'province', 'religion','precinct_no', 'sector_identifier'], 'string', 'max' => 32],
            [['gender'], 'string', 'max' => 16],
            [['remarks'], 'string', 'max' => 128],
            [['last_name', 'first_name', 'middle_name',], 'string', 'max' => 255],
            [['converted_voter'], 'safe'], // Ensure it's a safe attribute

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->setAttributeLabels([
            'id' => 'ID',
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'gender' => 'Gender',
            'age' => 'Age',
            'date_of_birth' => 'Date Of Birth',
            'civil_status' => 'Civil Status',
            'house_no' => 'House No',
            'sitio' => 'Sitio',
            'barangay' => 'Barangay',
            'municipality' => 'Municipality',
            'province' => 'Province',
            'religion' => 'Religion',
            'criteria1_color_id' => 'Criteria 1',
            'criteria2_color_id' => 'Criteria 2',
            'criteria3_color_id' => 'Criteria 3',
            'criteria4_color_id' => 'Criteria 4',
			'criteria5_color_id' => 'Criteria 5',
            'date_survey' => 'Date Survey',
            'remarks' => 'Remarks',
            'status' => 'Status',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\SpecialsurveyQuery the active query used by this AR class.
     */
	
    public static function find()
    {
        
		return new \app\models\query\SpecialsurveyQuery(get_called_class());
    }

    public function init()
    {
        parent::init();
        $address = App::setting('address');

        $this->municipality = $this->municipality ?: strtoupper($address->municipalityName);
        $this->province = $this->province ?: strtoupper($address->provinceName);
    }

    public function getFullname()
    {
        return implode(' ', [
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ]);
    }
    
      public function getFullnamelast()
    {
        return implode(' ', [
            $this->last_name.',',
            $this->first_name,
            $this->middle_name,
        ]);
    }
	
     
    public function gridColumns()
    {
        return [
            'survey_name' => [
                'attribute' => 'survey_name', 
                'format' => 'raw',
                'value' => function($model) {
                    return Anchor::widget([
                        'title' => $model->survey_name,
                        'link' => $model->viewUrl,
                        'text' => true
                    ]);
                }
            ],
           // 'precinct_no' => ['attribute' => 'precinct_no', 'format' => 'raw'],
            'fullname' => ['attribute' => 'first_name', 'format' => 'raw', 'value' => 'fullnamelast', 'label' => 'FULLNAME'],
            'purok' => ['attribute' => 'purok', 'format' => 'raw'],
             'barangay' => ['attribute' => 'barangay', 'format' => 'raw'],
           // 'household_no' => ['attribute' => 'household_no', 'format' => 'raw'],
            'gender' => ['attribute' => 'gender', 'format' => 'raw'],
            'sector_identifier' => ['attribute' => 'sector_identifier', 'format' => 'raw'],
            'age' => ['attribute' => 'age', 'format' => 'raw'],
           // 'religion' => ['attribute' => 'religion', 'format' => 'raw'],
          //  'date_of_birth' => ['attribute' => 'date_of_birth', 'format' => 'raw'],
          //  'civil_status' => ['attribute' => 'civil_status', 'format' => 'raw'],
          //  'house_no' => ['attribute' => 'house_no', 'format' => 'raw'],
           // 'sitio' => ['attribute' => 'sitio', 'format' => 'raw'],
			
           
           // 'municipality' => ['attribute' => 'municipality', 'format' => 'raw'],
           // 'province' => ['attribute' => 'province', 'format' => 'raw'],
           // 'religion' => ['attribute' => 'religion', 'format' => 'raw'],
            'criteria1_color_id' => [
			'attribute' => 'criteria1_color_id', 
			'label'=>'Criteria'.(isset($_GET['criteria'])?' '.$_GET['criteria']:null),
			'format' => 'raw',
			 'value' => function($model) {
				 
				    $survey_color = Specialsurvey::surveyColorReIndex();
                    return '<span class="badge badge-pill" style="background-color: '.$survey_color[$model->criteria1_color_id]['color'].';">&nbsp; &nbsp; </span>  '.$survey_color[$model->criteria1_color_id]['label'];
			  },

			'headerOptions' =>['style' =>"background:".(isset($_GET['criteria1_color_id'])?'#b2d7b8':'none')." "], 
			'contentOptions' =>['style' =>"background:".(isset($_GET['criteria1_color_id'])?'#b2d7b8':'none')." "], 
			
			],
          
             /*
            'criteria2_color_id' => ['attribute' => 'criteria2_color_id', 'format' => 'raw',
			'value' => function($model) {
				 
				    $survey_color = Specialsurvey::surveyColorReIndex();
                    return '<span class="badge badge-pill" style="background-color: '.$survey_color[$model->criteria2_color_id]['color'].';">&nbsp; &nbsp; </span>  '.$survey_color[$model->criteria2_color_id]['label'];
			  },
			'headerOptions' =>['style' =>"background:".(isset($_GET['criteria2_color_id'])?'#b2d7b8':'none')." "], 
			'contentOptions' =>['style' =>"background:".(isset($_GET['criteria2_color_id'])?'#b2d7b8':'none')." "], 
			],
            'criteria3_color_id' => ['attribute' => 'criteria3_color_id', 'format' => 'raw',
			'value' => function($model) {
				 
				    $survey_color = Specialsurvey::surveyColorReIndex();
                    return '<span class="badge badge-pill" style="background-color: '.$survey_color[$model->criteria3_color_id]['color'].';">&nbsp; &nbsp; </span>  '.$survey_color[$model->criteria3_color_id]['label'];
			  },
			  'headerOptions' =>['style' =>"background:".(isset($_GET['criteria3_color_id'])?'#b2d7b8':'none')." "], 
			  'contentOptions' =>['style' =>"background:".(isset($_GET['criteria3_color_id'])?'#b2d7b8':'none')." "], 
			],
            'criteria4_color_id' => ['attribute' => 'criteria4_color_id', 'format' => 'raw',
			'value' => function($model) {
				 
				    $survey_color = Specialsurvey::surveyColorReIndex();
                    return '<span class="badge badge-pill" style="background-color: '.$survey_color[$model->criteria4_color_id]['color'].';">&nbsp; &nbsp; </span>  '.$survey_color[$model->criteria4_color_id]['label'];
			  },
			  'headerOptions' =>['style' =>"background:".(isset($_GET['criteria4_color_id'])?'#b2d7b8':'none')." "], 
			'contentOptions' =>['style' =>"background:".(isset($_GET['criteria4_color_id'])?'#b2d7b8':'none')." "], 
			],
			  'criteria5_color_id' => ['attribute' => 'criteria5_color_id', 'format' => 'raw',
			  
			  'value' => function($model) {
				 
				    $survey_color = Specialsurvey::surveyColorReIndex();
                    return '<span class="badge badge-pill" style="background-color: '.$survey_color[$model->criteria5_color_id]['color'].';">&nbsp; &nbsp; </span>  '.$survey_color[$model->criteria5_color_id]['label'];
			  },
			  'headerOptions' =>['style' =>"background:".(isset($_GET['criteria5_color_id'])?'#b2d7b8':'none')." "], 
			 'contentOptions' =>['style' =>"background:".(isset($_GET['criteria5_color_id'])?'#b2d7b8':'none')." "], 
			  
			  ],
			  */
			  
            'date_survey' => ['attribute' => 'date_survey', 'format' => 'raw'],
            'remarks' => ['attribute' => 'remarks', 'format' => 'raw',
            //'visible'=>false
            ],
        ];
    }
	
	
	public function getExportColumns()
    {
        $columns = [
            'survey_name' => ['attribute' => 'survey_name', 'format' => 'raw'],
            'precinct_no' => ['attribute' => 'precinct_no', 'format' => 'raw'],
            'last_name' => ['attribute' => 'last_name', 'format' => 'raw'],
            'first_name' => ['attribute' => 'first_name', 'format' => 'raw'],
            'middle_name' => ['attribute' => 'middle_name', 'format' => 'raw'],
            'household_no' => ['attribute' => 'household_no', 'format' => 'raw'],
            'gender' => ['attribute' => 'gender', 'format' => 'raw'],
            'age' => ['attribute' => 'age', 'format' => 'raw'],
            'date_of_birth' => ['attribute' => 'date_of_birth', 'format' => 'raw'],
            'civil_status' => ['attribute' => 'civil_status', 'format' => 'raw'],
            'religion' => ['attribute' => 'religion', 'format' => 'raw'],
            'house_no' => ['attribute' => 'house_no', 'format' => 'raw'],
            'sitio' => ['attribute' => 'sitio', 'format' => 'raw'],
			'purok' => ['attribute' => 'purok', 'format' => 'raw'],
            'barangay' => ['attribute' => 'barangay', 'format' => 'raw'],
            'municipality' => ['attribute' => 'municipality', 'format' => 'raw'],
            'province' => ['attribute' => 'province', 'format' => 'raw'],
            'criteria1_color_id' => ['attribute' => 'criteria1_color_id', 'format' => 'raw', ],
            'criteria2_color_id' => ['attribute' => 'criteria2_color_id', 'format' => 'raw'],
            'criteria3_color_id' => ['attribute' => 'criteria3_color_id', 'format' => 'raw'],
            'criteria4_color_id' => ['attribute' => 'criteria4_color_id', 'format' => 'raw'],
            'criteria5_color_id' => ['attribute' => 'criteria5_color_id', 'format' => 'raw'],
            'date_survey' => ['attribute' => 'date_survey', 'format' => 'raw'],
            'remarks' => ['attribute' => 'remarks', 'format' => 'raw'],
        ];

        foreach ($columns as &$column) {
            $column['label'] = strtoupper($column['attribute']);
        }

        return $columns;
    }

    public function detailColumns()
    {
        return [
            'last_name:raw',
            'first_name:raw',
            'middle_name:raw',
            'gender:raw',
            'age:raw',
            'date_of_birth:raw',
            'civil_status:raw',
            'house_no:raw',
            'purok:raw',
            'barangay:raw',
            'municipality:raw',
            'province:raw',
            'religion:raw',
            'criteria1_color_id:raw',
            'criteria2_color_id:raw',
            'criteria3_color_id:raw',
            'criteria4_color_id:raw',
            'criteria4_color_id:raw',
            'criteria5_color_id:raw',
            'date_survey:raw',
            'remarks:raw',
        ];
    }

    public function getFooterGridColumns()
    {
        $columns = [
            'created_at' => ['attribute' => 'created_at', 'format' => 'fulldate', 'visible'=>false],
            // 'created_by' => ['attribute' => 'created_by', 'format' => 'raw', 'value' => 'createdByName'],
            'last_updated' => [
                'attribute' => 'updated_at',
                'label' => 'last updated',
                'format' => 'ago',
				'visible'=>false
            ],
            // 'updated_by' => ['attribute' => 'updated_by', 'format' => 'raw', 'value' => 'updatedByName'],
        ];

        if (App::isLogin() && App::identity()->can('in-active-data', $this->controllerID())) {
            // $columns['active'] = [
            //     'attribute' => 'record_status',
            //     'label' => 'active',
            //     'format' => 'raw', 
            //     'value' => 'recordStatusHtml'
            // ];
        }
        
        return $columns;
    }



    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['DateBehavior'] = [
            'class' => 'app\behaviors\DateBehavior',
            'attributes' => [
                'date_survey',
                'date_of_birth',
            ]
        ];

        $behaviors['AgeBehavior'] = [
            'class' => 'app\behaviors\AgeBehavior',
            'dateAttribute' => 'date_of_birth',
            'condition' => function($model) {
                return $model->isActive;
            }
        ];

        return $behaviors;
    }

    public static function monthFilter($year='')
    {
        $year = $year ?: App::formatter()->asDateToTimezone('', 'Y');

        $models = self::find()
            ->select(['*', 'DATE_FORMAT(date_survey, "%Y-%m") AS date'])
            ->where(['DATE_FORMAT(date_survey, "%Y")' => $year])
            ->groupBy('date')
            ->orderBy(['date' => SORT_ASC])
            ->asArray()
            ->all();

        $models = ArrayHelper::map($models, 'date', 'date_survey');
        
        $data = [];
        foreach ($models as $key => $value) {
            $arr = explode('-', $key);
            $month = end($arr);

            // $days = cal_days_in_month(CAL_GREGORIAN, (int)$month, $year);
            $days = date('t', mktime(0, 0, 0, (int)$month, 1, $year)); 

            $data[$key] = [
                'label' => date('F', strtotime($value)),
                'date_range' => implode(' - ', [
                    date('Y-m-d', strtotime($key . '-01')),
                    date('Y-m-d', strtotime($key .  '-'. $days)),
                ])
            ];
        }

        return $data;
    }

    public static function yearFilter()
    {
        $models = self::find()
            ->select(['*', 'DATE_FORMAT(date_survey, "%Y") AS year'])
            ->groupBy('year')
            ->orderBy(['year' => SORT_ASC])
            ->asArray()
            ->all();

        $models = ArrayHelper::map($models, 'year', 'year');

        foreach ($models as $key => &$value) {
            $value = "{$value}-01-01 - {$value}-12-31";
        }

        return $models;
    }

    public static function colorFilter()
    {
        return App::mapParams(App::setting('surveyColor')->survey_color);
    }

    public function getDominantColor($total_blue=0, $total_gray=0, $total_blackx=0, $total_blacky=0, $total_blacku=0)
    {
        $color = '';
        $surveyColor = App::setting('surveyColor')->survey_color;
        $survey_color = ArrayHelper::index($surveyColor, 'id');
        $array = [$total_blue, $total_gray, $total_blackx, $total_blacky, $total_blacku];

        $color_max = max($total_blue, $total_gray, $total_blackx, $total_blacky, $total_blacku);
        $total_color = array_sum($array);
        $total_color_percent = ($total_color * (App::setting('surveyColor')->dominance_percentage / 100));

        if ($color_max == $total_blue && $color_max >= $total_color_percent) {
            $color = $survey_color[1]['color'];
            $percent = $total_color > 0 ? ($color_max / $total_color) * 100 : 0;
        } elseif ($color_max == $total_blackx && $color_max >= $total_color_percent) {
            $color = $survey_color[3]['color'];
            $percent = $total_color > 0 ? ($color_max / $total_color) * 100 : 0;
        } elseif ($color_max == $total_blacky && $color_max >= $total_color_percent) {
            $color = $survey_color[4]['color'];
            $percent = $total_color > 0 ? ($color_max / $total_color) * 100 : 0;
        } elseif ($color_max == $total_blacku && $color_max >= $total_color_percent) {
            $color = $survey_color[5]['color'];
            $percent = $total_color > 0 ? ($color_max / $total_color) * 100 : 0;
        } elseif ($color_max == $total_gray && $color_max >= $total_color_percent) {
            $color = $survey_color[2]['color'];
            $percent = $total_color > 0 ? ($color_max / $total_color) * 100 : 0;
        } else {
            $color = $survey_color[2]['color'];
            $percent = $total_color > 0 ? ($total_gray / $total_color) * 100 : 0;
        }

        return ['color' => $color, 'percent' => round($percent, 2)];
    }

    // public function getDominantColor($total_black=0, $total_gray=0, $total_green=0, $total_red=0)
    // {
    //     $color = '';
    //     $surveyColor = App::setting('surveyColor')->survey_color;
    //     $survey_color = ArrayHelper::index($surveyColor, 'id');
    //     $array = [$total_black, $total_gray, $total_green, $total_red];

    //     $color_max = max($total_black, $total_gray, $total_green, $total_red);
    //     $total_color = array_sum($array); //-$color_max;
    //     $total_color_percent = ($total_color * (App::setting('surveyColor')->dominance_percentage / 100)); //50%;

    //     /*
    //     $maxs = array_keys($array, max($array));
    //     if ($maxs && count($maxs) > 1) {
    //         $maxPriority = 0;
    //         foreach ($maxs as $max) {
    //             if ($surveyColor[$max]['priority'] > $maxPriority) {
    //                 $maxPriority = $surveyColor[$max]['priority'];
    //                 $color_max = $array[$max];
    //             }
    //         }
    //     }
    //     */

    //     if($color_max == $total_black && $color_max >= $total_color_percent) {
    //         $color = $survey_color[1]['color']; 
    //         $percent = $total_color>0?($color_max/$total_color)*100:0; 
    //     }
    //     elseif($color_max == $total_green && $color_max >= $total_color_percent) {
    //         $color = $survey_color[3]['color'];  
    //         $percent = $total_color>0?($color_max/$total_color)*100:0; 
    //     }
    //     elseif($color_max == $total_red && $color_max >= $total_color_percent) {
    //         $color = $survey_color[4]['color'];  
    //         $percent = $total_color>0?($color_max/$total_color)*100:0; 
    //     }
    //     elseif($color_max == $total_gray && $color_max >= $total_color_percent) {
    //         $color = $survey_color[2]['color'];   
    //         $percent = $total_color>0?($color_max/$total_color)*100:0; 
    //     }
    //     else{
    //         $color = $survey_color[2]['color'];  
    //         $percent = $total_color>0?($total_gray/$total_color)*100:0; 
    //     }

    //     /*
    //     if ((count(array_unique($array)) === 1)) {
    //         $maxPriority = 0;
    //         foreach ($surveyColor as $index => $sv) {
    //             if ($sv['priority'] > $maxPriority) {
    //                 $maxPriority = $sv['priority'];
    //                 $color = $survey_color[$sv['id']]['color'];
    //             }
    //         }
    //     }
    //     */
        
    //     $color = ['color' => $color, 'percent'=>round($percent,2)];

    //     return $color;
    // }

    public static function colorPriority()
    {
        $survey_color = App::setting('surveyColor')->survey_color;
        uasort($survey_color, function($a, $b){
            if ($a['priority'] < $b['priority']) {
                return -1;
            } elseif ($a['priority'] > $b['priority']) {
                return 1;
            } else {
                return 0;
            }
        });

        return $survey_color;
    }

    public static function surveyColorReIndex()
    {
        return ArrayHelper::index(App::setting('surveyColor')->survey_color, 'id');
    }

    public static function findByKeywords($keywords='', $attributes='', $limit=10, $andFilterWhere=[])
    {
        return parent::findByKeywordsData($attributes, function($attribute) use($keywords, $limit, $andFilterWhere) {
            return self::find()
                ->select("{$attribute} AS data")
                ->alias('m')
                ->groupBy('data')
                ->where(['LIKE', $attribute, $keywords])
                ->andFilterWhere($andFilterWhere)
                ->limit($limit)
                ->asArray()
                ->all();
        });
    }

    public function getConvertedVoters()
    {
        return Yii::$app->db->createCommand("
            SELECT t1.*, t2.survey_name AS previous_survey
            FROM tbl_specialsurvey t1
            JOIN tbl_specialsurvey t2 
                ON t1.household_no = t2.household_no 
                AND t1.precinct_no = t2.precinct_no 
                AND t1.date_survey > t2.date_survey
            WHERE 
                (t1.criteria1_color_id <> t2.criteria1_color_id 
                OR t1.criteria2_color_id <> t2.criteria2_color_id
                OR t1.criteria3_color_id <> t2.criteria3_color_id
                OR t1.criteria4_color_id <> t2.criteria4_color_id
                OR t1.criteria5_color_id <> t2.criteria5_color_id)
            ORDER BY t1.date_survey DESC
        ")->queryAll();
    }

    public function getConvertedVoterCount()
    {
        return count($this->getConvertedVoters());
    }

}