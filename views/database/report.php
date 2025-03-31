<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\widgets\Anchors;
use app\widgets\BulkAction;
use app\widgets\DatabasePrioritySector;
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

$url = ['database/report','print' => true];

$this->params['headerButtons'] = implode(' ', [
	Html::a('Print', '#',[
		'class' => "btn btn-bg-white btn-text-dark-50 btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm px-5 mr-3",
		'onClick' => 'popupCenter("'. Url::to(array_merge($url, App::queryParams())) .'")'
	]),
	ExportButton::widget([
        'csvUrl' =>['database/export-csv-report'],
        'xlsxUrl' => ['database/export-xlsx-report'],
    ]),
	$searchModel->headerCreateButton,
]);

$this->params['activeMenuLink'] = '/database/report';

$print_url = Url::to([
	'database/report', 
	'print'=>true,
	'date_range'=>$searchModel->date_range, 
	'status'=>'Active'
],true);

$data_sector = $dataProvider->getModels();
$data_sector_index = $data_sector?ArrayHelper::index($data_sector , 'priority_sector'):null;

$label = [];
if ($data_sector_index) {
	foreach($data_sector_index as $key=>$row){
		$label['label'][] =  $priority_sector[$key]['code'];
		$label['male'][] = $row['male_active']?:0;
		$label['female'][] = $row['female_active']?:0;
	}
}

$data_label = json_encode($label['label'] ?? []); //"['January','February','March', 'XX']";
$data_name = "Male";
$data_value = json_encode($label['male'] ?? []);//"[100,200,300]";
$data_name2 = "Female";
$data_value2 = json_encode($label['female'] ?? []);//"[300,400,500]";

$asDaterange = App::formatter()->asDaterange($searchModel->date_range);
$asDaterange = ($asDaterange) ? "({$asDaterange})": '';

$this->registerJs(<<< JS
 $(document).ready(function () {
	 var options = {
	 	series: [
		    {name: '{$data_name}', data: {$data_value}},
		    {name: '{$data_name2}', data: {$data_value2}}
		],
		chart: {
			type: 'bar',
			height: 350,
			toolbar: {show: false}
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
			text: 'BY PRIORITY SECTORS AND GENDER {$asDaterange}',
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
    });
});
JS, View::POS_END);	
	 
?>
<div class="database-index-page">
	<?= DatabasePrioritySector::widget([
		'dataProvider' => $dataProvider
	]) ?>
</div>