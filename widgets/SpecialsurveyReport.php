<?php

namespace app\widgets;

use app\helpers\App;
use app\helpers\Html;

class SpecialsurveyReport extends BaseWidget
{
    public $template = 'per-barangay';
    public $models;
    public $rowsummary;
	public $searchModel;
    public $priority_sector;
    public $content;
    public $default = 0;
    public $per_purok = false;


    public function init()
    {
        parent::init();


        if ($this->template == 'per-barangay') {
            $th = '';
            $tbody = '';

           
            $footer_total_active = '';
            $footer_total_male_active = '';
            $footer_total_female_active = '';
            foreach ($this->models as $model) {
                $th = '';
                $active = '';
                $black = '';
				$gray = '';
				$green = '';
                $red = '';
				$black_totalfooter = '';
				$gray_totalfooter = '';
				$green_totalfooter = '';
                $red_totalfooter = '';
                $footer_total_active = '';
                $footer_total_male_active = '';
                $footer_total_female_active = '';

                // $total_active = Html::number($this->rowsummary['active_total']);
                // $total_male = Html::number($this->rowsummary['active_male_total']);
                // $total_female = Html::number($this->rowsummary['active_female_total']);
   
               for ($i = 1; $i <= 5; $i++) {
                    $th .= Html::tag('th', 'Criteria '.$i);
                 

                    $black .=  Html::tag('td',  Html::number($model['criteria'.$i.'_color_black']) ?: $this->default) ;
					$gray .=  Html::tag('td',  Html::number($model['criteria'.$i.'_color_gray']) ?: $this->default) ;
					$green .=  Html::tag('td',  Html::number($model['criteria'.$i.'_color_green']) ?: $this->default) ;
					$red .=  Html::tag('td',  Html::number($model['criteria'.$i.'_color_red']) ?: $this->default) ;
					$total=$model['criteria'.$i.'_color_black'] + $model['criteria'.$i.'_color_gray']+$model['criteria'.$i.'_color_green']+$model['criteria'.$i.'_color_red'];
					
					$active.= Html::tag('td',  Html::number($total) ?: $this->default) ;

                    $black_totalfooter .=  Html::tag('td',  Html::number($this->rowsummary['criteria'.$i.'_color_black_total']) ?: $this->default) ;
					$gray_totalfooter .=  Html::tag('td',  Html::number($this->rowsummary['criteria'.$i.'_color_gray_total']) ?: $this->default) ;
					$green_totalfooter .=  Html::tag('td',  Html::number($this->rowsummary['criteria'.$i.'_color_green_total']) ?: $this->default) ;
					$red_totalfooter .=  Html::tag('td',  Html::number($this->rowsummary['criteria'.$i.'_color_red_total']) ?: $this->default) ;
					
					
                    // $footer_total_active .= Html::tag('td', Html::number($this->rowsummary["{$row['id']}_active_total"]) ?: $this->default);

                    // $footer_total_male_active .= Html::tag('td',  Html::number($this->rowsummary["{$row['id']}_active_male_total"]) ?: $this->default);

                    // $footer_total_female_active .= Html::tag('td', Html::number($this->rowsummary["{$row['id']}_active_female_total"]) ?: $this->default);
                }
				

                $total_active = Html::number($model['active'] ?? 0) ?: $this->default;
                $total_active_male = Html::number($model['active_male'] ?? 0) ?: $this->default;
                $total_active_female = Html::number($model['active_female'] ?? 0) ?: $this->default;
                
                $arrayKey = $this->per_purok ? 'purok': 'barangay';
                $survey_color = App::setting('surveyColor')->survey_color;

                $tbody .= '
                    <tr>
                        <td rowspan="4"> '.$model[$arrayKey].'</td>
                        <td>'. $survey_color[0]['label'] .'</td>
                        '.$black.'
                   
                    </tr>
                    <tr>
                        <td>'. $survey_color[1]['label'] .'</td>
                        '.$gray.'
                       
                    </tr>
                     <tr>
                        <td>'.$survey_color[2]['label'].'</td>
                        '.$green.'
                       
                    </tr>
					<tr>
                        <td>'. $survey_color[3]['label'] .'</td>
                        '.$red.'
                     
                    </tr>
                    ';
            }

            $summary_active = Html::number($this->rowsummary['active_total'] ?? 0) ?: $this->default;
            $summary_male_active = Html::number($this->rowsummary['active_male_total'] ?? 0) ?: $this->default;
            $summary_female_active = Html::number($this->rowsummary['active_female_total'] ?? 0) ?: $this->default;

            $label = $this->per_purok ? 'PUROK': 'BARANGAY';
            $this->content = 
                '<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2">'.$label.'</th>
                            '.$th.'
                          
                        </tr>
                    </thead>
                    <tbody>
                        '.$tbody.'
                        <tr>
                            <th rowspan="5"> TOTAL </th>
                            <th>Black</th>
                            '.($black_totalfooter ?? 0).'
                        </tr>
                        <tr>
                            <th>Gray</th>
                            '.($gray_totalfooter ?? 0).'
                        </tr>
						
						 <tr>
                            <th>Green</th>
                            '.($green_totalfooter ?? 0).'
                        </tr>
						 <tr>
                            <th>Red</th>
                            '.($red_totalfooter ?? 0).'
                            
                        </tr>
                       
                    </tbody>
                </table>';
        }
    }
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->content;
    }
}
