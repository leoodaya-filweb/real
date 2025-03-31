<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Specialsurvey;
use app\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SpecialsurveySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$criteria=!$criteria?1:$criteria;
//$id=1;
//$barangay_name='Bagong Silang';
$barangay_name=$row['barangay'];

         $color_survey = $queryParams['color_survey'];
        if($color_survey){
         $color_survey = explode(',', $color_survey);
        }

$barangay = Specialsurvey::find()->alias('t')
            ->select(['t.purok, 
            sum(t.criteria'.$criteria.'_color_id=1) as Black, 
            sum(t.criteria'.$criteria.'_color_id=2) as Gray,
            sum(t.criteria'.$criteria.'_color_id=3) as Green,
            sum(t.criteria'.$criteria.'_color_id=4) as Red
            '])
           ->where(['t.barangay'=>$barangay_name])
           ->andWhere("t.purok not in ('','0','-',' - ','\\\')")
            ->andFilterWhere([
                't.survey_name'=>$queryParams['survey_name'],
                't.purok'=>$queryParams['purok'],
                't.criteria'.$criteria.'_color_id' => $color_survey
            ])
           ->groupBy("trim(t.purok)")
           ->orderBy(["t.purok"=>SORT_ASC])
           ->asArray()
        ->all();
        
        
//print_r($barangay);  

foreach($barangay as $key=>$row){
      $barangay_label[]=$row['purok'];
      
}

$survey_color = Specialsurvey::surveyColorReIndex();
 $data=[];
 foreach($survey_color as $key=>$row){
        $databar=[];
        foreach($barangay as $keyx=>$rowx){
              $databar[]=$rowx[$row['label']];
              //echo $rowx[$row['label']].', ';
          }
          
          $data[]=['name'=>$row['label'],'data'=>$databar ];
 }


 
 $barangay_label = json_encode($barangay_label);
 $data = json_encode($data);




$widgetFunction='purok'.$id;
$this->registerWidgetJs($widgetFunction, <<< JS

   
    const black = '#181c32';
	const gray = '#e4e6ef';
	const green = '#1bc5bd';
	const red = '#f64e60';
   
    let data = {$data};
    let label = {$barangay_label};

	var options = {
          series: data,
          chart: {
          toolbar: {
				show: false 
			},
          type: 'bar',
          height: 650,
          stacked: true,
          //stackType: '100%'
        },
        plotOptions: {
          bar: {
            horizontal: true,
          },
        },
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        title: {
          text: "{$barangay_name} Voters Color Per Purok",
          align: 'left',
          style: {
              fontSize:  '16px',
              fontWeight:  'bold',
          }
        },
        xaxis: {
          categories: label,
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + " Voters"
            }
          }
        },
        fill: {
          opacity: 1
        
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: 40
        },
        colors: [black, gray, green, red]
        };

        var chart = new ApexCharts(document.querySelector("#chart-purok{$id}"), options);
        chart.render();
   
JS);

?>


<div class="specialsurvey-index-page" >
    
    <div class="mt-10"></div>
     <hr/>
    <div id="chart-purok<?=$id?>"></div>
   
</div>
