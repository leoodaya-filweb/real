<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Database;
use app\widgets\Anchors;
use app\widgets\BulkAction;
use app\widgets\DatabaseReport;
use app\widgets\ExportButton;
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
$this->params['showCreateButton'] = false; 
$this->params['showExportButton'] = false;
$this->params['xlsxUrl']=['database/export-xlsx-report'];
$this->params['activeMenuLink'] = '/database/report-per-barangay';

$this->params['headerButtons'] = implode(' ', [
	Html::a('Print', '#',[
		'class' => "btn btn-bg-white btn-text-dark-50 btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm px-5 mr-3",
		'onClick' => 'popupCenter("'. Url::to(['database/report-per-barangay','print' => true]) .'")'
	]),
	ExportButton::widget([
        'csvUrl' =>['database/export-csv-report-per-barangay'],
        'xlsxUrl' => ['database/export-xlsx-report-per-barangay'],
    ]),
	Anchors::widget([
        'names' => 'create',
        'forceTitle' => 'Create Database Entry',
    ]),
]);

//$priority_sector = Database::priorityReIndex();

$print_url = Url::to(['database/report-per-barangay',
'print'=>true,
'date_range'=>$searchModel->date_range,
'status'=>'Active' //$searchModel->status,
],true);

$query = clone $dataProvider->query;
$models = $query->asArray()->all();

$data_sector = $models;
$data_sector_index = $data_sector?ArrayHelper::index($data_sector , 'barangay'):null;
$additional_columns=[];
foreach($priority_sector as $key=>$row){
	array_push($additional_columns, [
		'label'=>$row['code'],
		'value'=> function ($model, $index)use($searchModel, $row, $data_sector_index){
			$active_mf='<div class="text-muted">
			M: '.
			(isset($data_sector_index[$model['barangay']][$row['id'].'_active_male'])?
				number_format($data_sector_index[$model['barangay']][$row['id'].'_active_male'], 0, '.', ','): 0)
			.' | F: '.
			(
				isset($data_sector_index[$model['barangay']][$row['id'].'_active_female'])?
				number_format($data_sector_index[$model['barangay']][$row['id'].'_active_female'], 0, '.', ','): 0
			)
			.'</div>';

			return $active_mf.Html::a(isset($data_sector_index[$model['barangay']][$row['id'].'_active'])? number_format($data_sector_index[$model['barangay']][$row['id'].'_active'], 0, '.', ','): 0, 
					['database/index', 
					'priority_sector'=>$row['id'],
					//'gender'=>'Female',
					'barangay'=>$model['barangay']?$model['barangay']:'none',
					'status'=>'Active',
					'date_range'=>$searchModel->date_range,
					], 
					[ 'title' => 'View details' ]
					);
		},
                            'format' => 'raw',
							'headerOptions' =>['class'=>'text-center'], 
							'contentOptions' =>['class'=>'text-center'], 
							/*'footer' => 
							'<div class="text-muted" style="font-weight:normal;">
							M: '.number_format($rowsummary[$row['id'].'_active_male_total'], 0, '.', ',')
						    .' <br/> F: '.number_format($rowsummary[$row['id'].'_active_female_total'], 0, '.', ',')
							.'</div>'
							.Html::a(number_format($rowsummary[$row['id'].'_active_total'], 0, '.', ','), 
					['database/index', 
					'priority_sector'=>$row['id'],
					'status'=>'Active',
					'date_range'=>$searchModel->date_range,
					], 
					[ 'title' => 'View details' ]
					             ),*/
							
								 
                        ]
						);
						
	

   
}


/*array_push($additional_columns, [
                            'label'=>'Total',
                            'value'=> function ($model, $index)use($searchModel){  
							$active_mf='<div class="">
							M: '.number_format($model['active_female'], 0, '.', ',')
						    .' | F: '.number_format($model['active_male'], 0, '.', ',')
							.'</div>';
							
                                return $active_mf.Html::a(number_format($model['active'], 0, '.', ','), 
					['database/index', 
					//'priority_sector'=>$row['id'],
					//'gender'=>'Female',
					'barangay'=>$model['barangay']?$model['barangay']:'none',
					'status'=>'Active',
					'date_range'=>$searchModel->date_range,
					], 
					[ 'title' => 'View details' ]
					);
                            },
                            'format' => 'raw',
							'headerOptions' =>['class'=>'text-center'], 
							'contentOptions' =>['class'=>'text-center', 
							//'style'=>"font-weight: bold;"
							], 
							'footer' => 
							'<div class="text-muted" style="font-weight:normal;">
							M: '.number_format($rowsummary['active_male_total'], 0, '.', ',')
						    .' <br/> F: '.number_format($rowsummary['active_female_total'], 0, '.', ',')
							.'</div>'
							.number_format($rowsummary['active_total'], 0, '.', ','),
                        ]
						);*/


$label = [];
if ($data_sector_index) {
	foreach($data_sector_index as $key=>$row){
		$label['label'][]=  $row['barangay']; //$priority_sector[$key]['code'];
		$label['male'][]= $row['active_male']??0;
		$label['female'][]= $row['active_female']??0;
	}
}




$data_label = json_encode($label['label'] ?? ''); //"['January','February','March', 'XX']";
$data_name = "Male";
$data_value = json_encode($label['male'] ?? '');//"[100,200,300]";
$data_name2 = "Female";
$data_value2 = json_encode($label['female'] ?? '');//"[300,400,500]";

//echo $data_value;

$asDaterange = App::formatter()->asDaterange($searchModel->date_range);
$asDaterange = ($asDaterange) ? "({$asDaterange})": '';
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
    text: 'BY BARANGAY AND GENDER {$asDaterange}',
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
      
	
	
	
	
			var newwindow;
  $('.printMe').click(function(e){
    e.preventDefault();
    var URL = '{$print_url}';
	newwindow=window.open(URL,'PrintWindow', 'width=1200,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes');
   // var W = window.open(URL);   
   // newwindow.focus();
   // newwindow.print(); 
	//	setTimeout(newwindow.print(), 5000);
	//setTimeout(function(){newwindow.close();}, 5000);
  
    });

	
});



JS;
$this->registerJs($script, View::POS_END);	

$this->registerCss(<<< CSS
	.table th,
	.table td {
		font-size: 11pt !important;
	}
CSS)
?>
<div class="database-index-page">
	<div style="max-width: 10in; margin: 0 auto;">
		<!-- <div id="chart"></div> -->
	</div>
		<?= DatabaseReport::widget([
			'models' => $models,
			'rowsummary' => $rowsummary,
			'priority_sector' => $priority_sector,
		]) ?>
</div>
