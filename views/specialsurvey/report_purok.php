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

$this->title = ($searchModel->barangay?'Brgy. '. $searchModel->barangay.' ':null).'Socio Economic Survey Reports ';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['showCreateButton'] = false; 
$this->params['showExportButton'] = false;
$this->params['xlsxUrl']=['specialsurvey/export-xlsx-report'];
$this->params['activeMenuLink'] = '/specialsurvey/report-per-purok';

$url = ['specialsurvey/report-per-purok','print' => true];

$this->params['headerButtons'] = implode(' ', [
	Html::a('Print', '#',[
		'class' => "btn btn-bg-white btn-text-dark-50 btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm px-5 mr-3",
		'onClick' => 'popupCenter("'. Url::to(array_merge($url, App::queryParams())) .'")'
	]),
	ExportButton::widget([
        'csvUrl' =>['specialsurvey/export-csv-report-per-purok'],
        'xlsxUrl' => ['specialsurvey/export-xlsx-report-per-purok'],
    ]),
	Anchors::widget([
        'names' => 'create',
        'forceTitle' => 'Create Database Entry',
    ]),
]);

$additional_columns=[];
$survey_color = App::setting('surveyColor')->survey_color;
$colors = ['black', 'gray', 'green', 'red'];

for ($i = 1; $i <= 5; $i++) {
	array_push($additional_columns, [
		'label' => "Criteria {$i}",
		'value' => function ($model, $index) use($searchModel, $i, $survey_color, $colors) {
			return Html::foreach($colors, function($color, $index) use($survey_color, $i, $model, $searchModel) {
				return Html::tag('div', implode(': ', [
					$survey_color[$index]['label'],
					Html::a(
						Html::number($model["criteria{$i}_color_{$color}"]), 
						[
							'specialsurvey/index', 
							"criteria{$i}_color_id" => $survey_color[$index]['id'],
							'barangay' => $model['barangay'] ?: '',
							'purok' => $model['purok'] ?: '',
							'date_range' => $searchModel->date_range,
						], 
						['title' => 'View details']
					)
				]));
			});
		},
		'format' => 'raw',
		'headerOptions' => ['class' => 'text-left'], 
		'contentOptions' => ['class' => 'text-left'], 
		'footer' => Html::foreach($colors, function($color, $index) use($survey_color, $i, $rowsummary, $searchModel) {
			return Html::tag('div', implode(': ', [
				$survey_color[$index]['label'],
				Html::a(
					Html::number($rowsummary["criteria{$i}_color_{$color}_total"]), 
					[
						'specialsurvey/index', 
						"criteria{$i}_color_id" => $survey_color[$index]['id'],
						'date_range' => $searchModel->date_range,
					], 
					['title' => 'View details']
				)
			]));
		}),
	]);
}

$print_url = Url::to([
	'specialsurvey/report-per-purok',
	'print' => true,
	'date_range' => $searchModel->date_range,
	'status' => 'Active' //$searchModel->status,
], true);

$this->registerJs(<<< JS
$(document).ready(function () {
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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'tableOptions' => ['class' => 'table align-items-center table-flush table-striped'],
		'summaryOptions' => ['class' => 'dataTables_info', 'role'=>"status", 'aria-live'=>"polite"],
		'showFooter' => true,
		'footerRowOptions'=>['class'=>'text-left','style'=>"font-weight: bold;"],
		'layout' => '{items}',
		'columns' => array_merge(
	        [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label'=>'Purok',
                    'value'=> function($model) {
                    	return "{$model['purok']} ({$model['barangay']})";
                    },
                    'format' => 'raw',
					'footer' => 'Total',
                ],
            ], 
            $additional_columns 
        )
    ]); ?>
</div>
