<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Database;
use app\widgets\BulkAction;
use app\widgets\FilterColumn;
use app\widgets\Grid;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DatabaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Database Reports';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['showCreateButton'] = true; 
$this->params['showExportButton'] = true;
$this->params['xlsxUrl']=['database/export-xlsx-report'];

//$priority_sector = Database::priorityReIndex();


$data_sector = $dataProvider->getModels();
$data_sector_index = $data_sector?ArrayHelper::index($data_sector , 'priority_sector'):null;
/*
foreach($priority_sector as $key=>$row){
	$label['label'][]=$row['code'];
	$label['male'][]= $data_sector_index[$row['id']]['male_active']?:0;
	$label['female'][]= $data_sector_index[$row['id']]['female_active']?:0;
}
*/

foreach($data_sector_index as $key=>$row){
	$label['label'][]=  $priority_sector[$key]['code'];
	$label['male'][]= $row['male_active']?:0;
	$label['female'][]= $row['female_active']?:0;
}

//print_r($data_sector_index);
//print_r($label['male']);

//echo json_encode($label['label']);


$data_label = json_encode($label['label']); //"['January','February','March', 'XX']";
$data_name = "Male";
$data_value = json_encode($label['male']);//"[100,200,300]";
$data_name2 = "Female";
$data_value2 = json_encode($label['female']);//"[300,400,500]";

//echo $data_value;

$script = <<< JS

 $(document).ready(function () {

	 var options = {
          series: [
		    {
			name: '{$data_name}',
            data: {$data_value}  
           },
		   
		   {
				name: '{$data_name2}',
				data: {$data_value2}
			}
			
			
		
		
		],
          chart: {
          type: 'bar',
          height: 350,
		  toolbar: {
			show: false 
		    }
        },
        plotOptions: {
          bar: {
            borderRadius: 4,
            horizontal: false,
          }
        },
        dataLabels: {
          enabled: false
        },
        xaxis: {
          categories: {$data_label},
        },
		
		yaxis: {
  labels: {
    formatter: function (value) {
      //return value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
	  return value.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
     }
    },
   },
		
		title: {
    text: 'BY PRIORITY SECTORS AND GENDER {$searchModel->date_range}',
    align: 'center',
    margin: 10,
    offsetX: 0,
    offsetY: 0,
    floating: false,
    style: {
      fontSize:  '14px',
      fontWeight:  'bold',
      fontFamily:  undefined,
      color:  '#263238'
    },
          }
		
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        // chart.render();
      
	
	// setTimeout('window.print()', 2000);
    // setTimeout(window.close, 2500);

	
});



JS;
$this->registerJs($script, View::POS_END);	
	 
$this->registerCss("

* {
    -webkit-print-color-adjust: exact !important;   /* Chrome, Safari */
    color-adjust: exact !important;                 /*Firefox*/
}

@media print {
  body {-webkit-print-color-adjust: exact;}
}

@page { 
        size: A4;
		margin: 0.2in;
		 -webkit-print-color-adjust: exact;
       }
  
div.page
      {
		page-break-after: always;
        page-break-inside: avoid;
      }	


html, body {
      margin: 0!important; 
      padding: 0!important;
	  background-color:#FFFFFF; 
    }	
	
table {page-break-inside: avoid;}
	
	

", ['media'=>"print", 'type'=>"text/css" ]);
	 
?>
<div class="client-view-print">
		<div style="max-width: 10in;margin: 0 auto">
		 	<!-- <div id="chart"></div> -->
		</div>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
			'tableOptions' => ['class' => 'table align-items-center table-flush table-striped'],
			'summaryOptions' => ['class' => 'dataTables_info', 'role'=>"status", 'aria-live'=>"polite"],
			'showFooter' => true,
			'footerRowOptions'=>['class'=>'text-center','style'=>"font-weight: bold;"],
			 'layout' => '{items}',
			
			 'columns' => [

                        [
                            'label'=>'Priority Sector',
                            'value'=> function ($model, $index)use($priority_sector){  
				                  return $priority_sector[$model['priority_sector']]['label'];
                            },
                            'format' => 'raw',
							// 'footer' => 'Total',
                        ],
						
						
						 [
                            'label'=>'Male',
							'headerOptions' =>['class'=>'text-center'], 
							'contentOptions' =>['class'=>'text-center'], 
                            'value'=> function ($model, $index){  
							      $active_inactive='<div class="text-muted">Inactive: '.number_format($model['male_inactive'], 0, '.', ',').'</div>';
							
                                
								 return number_format($model['male_active'], 0, '.', ',').$active_inactive;
                            },
                            'format' => 'raw',
							
							// 'footer' => number_format($rowsummary['male_active_total'], 0, '.', ','),
                        ],
						
						
						[ //'attribute'=>'age',
                            'label'=>'Female',
							'headerOptions' =>['class'=>'text-center'], 
							'contentOptions' =>['class'=>'text-center'], 
                            'value'=> function ($model, $index){  
							$active_inactive='<div class="text-muted">Inactive: '.number_format($model['female_inactive'], 0, '.', ',').'</div>';
							
                                return number_format($model['female_active'], 0, '.', ',').$active_inactive;
                            },
                            'format' => 'raw',
							// 'footer' => number_format($rowsummary['female_active_total'], 0, '.', ','),
                        ],
						
						
					
						/*[
						'headerOptions' =>['class'=>'text-center'], 
							'contentOptions' =>['class'=>'text-center'], 
                            'label'=>'Total',
                            'value'=> function ($model, $index) use($searchModel){  
							   $active_inactive='<div class="text-muted">Inactive: '.number_format($model['inactive'], 0, '.', ',').'</div>';
                               return number_format($model['active'], 0, '.', ',').$active_inactive;
                            
							},
                            'format' => 'raw',
							'footer' => number_format($rowsummary['active_total'], 0, '.', ','),
                        ],*/
						
					]
			
        ]); ?>
    <?php // Html::endForm(); ?> 

    <p>Preferred By: <?= App::identity('fullname') ?></p>
</div>