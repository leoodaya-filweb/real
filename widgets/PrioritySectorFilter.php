<?php

namespace app\widgets;
use Yii;
use app\helpers\Html;
use app\models\Database;

class PrioritySectorFilter extends BaseWidget
{
    public $data_report;
    public $rowsummary;
    public $content;

    public function init()
    {
        parent::init();

        $priority_sector = Database::priorityReIndex();
        $data_report = $this->data_report;


        $this->content = $this->render('priority-sector-filter/tab', [
            'total_active' => number_format($this->rowsummary['active_total'], 0, '.', ',') ?: 0,
            'male_active' => number_format($this->rowsummary['male_active_total'], 0, '.', ',') ?: 0,
            'female_active' => number_format($this->rowsummary['female_active_total'], 0, '.', ',') ?: 0,

            'code' => 'All',
            'class' => 'success',
            'url' => ['database/member', 'status' => 'Active']
        ]);

        $this->content .= Html::foreach($priority_sector, function($row, $key) use($data_report) {
            
            if($row['user_access']){
			   $user_access= json_decode($row['user_access']);
			   if(is_array($user_access) && !in_array(Yii::$app->user->identity->username,$user_access)){
			      return false;  
			   }
			
			   // return '..';
			}
            
            
            return $this->render('priority-sector-filter/tab', [
                'total_active' => isset($data_report[$row['id']]) ? 
                    number_format($data_report[$row['id']]['active'], 0, '.', ','): 0,

                'male_active' => isset($data_report[$row['id']]) ? 
                    number_format($data_report[$row['id']]['male_active'], 0, '.', ','): 0,

                'female_active' => isset($data_report[$row['id']]) ? 
                    number_format($data_report[$row['id']]['female_active'], 0, '.', ','): 0,

                'code' => $row['code'],
                'class' => $row['class'],
                'url' => ['database/member', 'priority_sector' => $row['id'], 'status' => 'Active']
            ]);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->render("priority-sector-filter/index", [
            'content' => $this->content
        ]);
    }
}
