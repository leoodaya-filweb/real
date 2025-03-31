<?php

namespace app\widgets;
use Yii;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Database;

class DatabaseCard extends BaseWidget
{
    public $data_report;
    public $content;

    public function init()
    {
        parent::init();

        $data_report = $this->data_report;
        $priority_sector = Database::priorityReIndex();

        $this->content = Html::foreach($priority_sector, function($row, $key) use($data_report) {
            
            if($row['user_access']){
			   $user_access= json_decode($row['user_access']);
			   if(is_array($user_access) && !in_array(Yii::$app->user->identity->username,$user_access)){
			      return false;  
			   }
			
			   // return '..';
			}
            
            
            return $this->render('database-card/card', [
                'row' => $row,
                'total_active' => isset($data_report[$row['id']]) ? 
                    number_format($data_report[$row['id']]['active'], 0, '.', ','): 0,

                'male_active' => isset($data_report[$row['id']]) ? 
                    number_format($data_report[$row['id']]['male_active'], 0, '.', ','): 0,

                'female_active' => isset($data_report[$row['id']]) ? 
                    number_format($data_report[$row['id']]['female_active'], 0, '.', ','): 0,
                
                'total_active_url' => Url::to(['database/member',
                    'priority_sector' => $row['id'],
                    'status' => 'Active'
                ]),

                'total_male_active_url' => Url::to(['database/member',
                    'priority_sector' => $row['id'],
                    'status' => 'Active',
                    'gender' => 'Male'
                ]),

                'total_female_active_url' => Url::to(['database/member',
                    'priority_sector' => $row['id'],
                    'status' => 'Active',
                    'gender' => 'Female'
                ]),
                'last_updated' => Database::lastUpdated($row['id'])
            ]);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render('database-card/index', [
            'content' => $this->content
        ]);
    }
}
