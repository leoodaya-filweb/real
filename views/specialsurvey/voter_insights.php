<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Specialsurvey;
use app\widgets\Anchors;
use app\widgets\BulkAction;
use app\widgets\DateRange;
use app\widgets\ExportButton;
use app\widgets\FilterColumn;
use app\widgets\Grid;
use app\widgets\Mapbox;
use app\widgets\TinyMce;
//use yii\helpers\Url;
use app\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SpecialsurveySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

 $searchModel->searchTemplate = 'specialsurvey/_search_voters';
 $searchModel->searchAction = ['specialsurvey/voter-insights'];


$this->title = 'Voter Demographics & Household Insights';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['searchForm'] = 'testvv';
$this->params['showCreateButton'] = false;//true; 
$this->params['showExportButton'] = false; //true;
$this->params['activeMenuLink'] = '/specialsurvey/voter-insights';
$this->params['createTitle'] = 'Create Survey';



// FilterColumn::widget(['searchModel' => $searchModel]);

$url = ['specialsurvey/voter-insights','print' => true];

$this->params['headerButtons'] = implode(' ', [
    
    FilterColumn::widget(['searchModel' => $searchModel, 'searchModelOnly'=>1]),
    
   	Html::a('Print', '#',[
		'class' => "btn btn-bg-white btn-text-dark-50 btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm px-5 mr-3",
		'onClick' => 'popupCenter("'. Url::to(array_merge($url, App::queryParams())) .'")'
	]),

	ExportButton::widget([
        //'csvUrl' =>['specialsurvey/export-csv-report-per-barangay'],
        //'xlsxUrl' => ['specialsurvey/export-xlsx-report-per-barangay'],
    ]),
    /*
	Anchors::widget([
        'names' => 'create',
        'forceTitle' => 'Create Survey',
    ]),
     Html::a('Import CSV', ['specialsurvey/importcsv'],[
        'class' => "font-weight-bold btn btn-primary font-weight-bolder font-size-sm btn-create ml-1 mr-1",
    ]),
    */
]);







$print_url = Url::to([
	'specialsurvey/voters-list',
	'print' => true,
	'date_range' => $searchModel->date_range,
	'status' => 'Active'
], true);






$image = $image ?: App::setting('image');
$address = $address ?: App::setting('address');


?>


<div class="specialsurvey-index-page">



       <div class="print-content" style="font-family: 'Anonymous Pro', monospace;">

        <h2>Comming soon!</h2>
        
        </div>
        
        
    
</div>

