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
 $searchModel->searchAction = ['specialsurvey/voters-list'];


$this->title = 'Barangay Voter\'s List';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['searchForm'] = 'testvv';
$this->params['showCreateButton'] = false;//true; 
$this->params['showExportButton'] = false; //true;
$this->params['activeMenuLink'] = '/specialsurvey/voters-list';
$this->params['createTitle'] = 'Create Survey';



// FilterColumn::widget(['searchModel' => $searchModel]);

$url = ['specialsurvey/voters-list','print' => true];

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

       <?php 
        $totalItemCount = $dataProvider->getTotalCount();
        $precinct_no="";
        
        $models = $dataProvider->getModels();
      
        ?>
        
        <table class="table table-striped table-hover">

           <tbody>
			  
			    
		 <?php
               $precinct_no="";
               $ctr = 1;
               foreach($models as $key=>$row) {
               
               ?>	    
		   <?php if($row->precinct_no!=$precinct_no || ($key==0)){
                 $ctr=1;
               ?> 
               
             

             <tr style="font-weight:bold; text-align:left;">
			      <td colspan="5" style="border:none;">
			          <?php  if($key!=0){ ?>     
                       <div class="p-break-before"></div>
                      <?php }  ?> 
                          
<div class="text-center" style=" font-weight:normal; box-sizing: border-box; display: flex !important; flex-wrap: nowrap !important; -webkit-box-pack: justify !important; justify-content: space-between !important; -webkit-box-align: center !important; align-items: center !important;">
  
    <div style="box-sizing: border-box; text-align: center !important; width: 100%;">
       
        <img style="box-sizing: border-box; vertical-align: middle; border-style: none;" src="<?= Url::image($image->municipality_logo, ['w' => 60], true) ?>" alt="" />
        <img style="box-sizing: border-box; vertical-align: middle; border-style: none;" src="<?= Url::image($image->province_logo, ['w' => 57], true) ?>" alt="" />
        <img style="box-sizing: border-box; vertical-align: middle; border-style: none;" src="<?= Url::image($image->philippines_logo, ['w' => 60], true) ?>" alt="" />
         <div>
        PROVINCE : QUEZON<br/>
        CITY/MUNICIPALITY : REAL<br/>
        BARANGAY : <?= strtoupper($searchModel->barangay) ?><br/>
        </div>
        <div style="">
               LIST OF VOTERS (PRESICNT LEVEL)
          </div>
       
    </div>

 </div>
                       
                       
			           <div class="text-left">Prec No. <?= $row->precinct_no?:'None' ?></div>
			           
			           <div style="box-sizing: border-box; height: 10px; border-bottom: 3px solid #3f4253; margin-top: 0px !important; padding-bottom: 0.5rem !important; text-align: center !important; font-size: 16pt !important;"></div>

			          
			          </td>
			 </tr>
             <tr>
               <th>#</th>
               <th>VOTER'S NAME</th>
                <th>ADDRESS</th>
                <th>ILLITERACY/DISABILIYT</th>
               <th>SEX / BIRTHDATE</th>
            </tr>
            
           
          
			    
			     <?php } ?>
			    
			    
			    
			    
                <tr data-key="401">
                   <td><?= $ctr ?></td>
                   
                   <td><?= $row->fullnamelast ?></td>
                   <td><?php 
                   
                   $row->purok = $row->purok?'Purok '.$row->purok.', ':null;
                  echo strtoupper(implode(' ', [$row->house_no, $row->purok, $row->barangay]));
                   
                   ?></td>
                   <td></td>
                   <td><?= strtoupper($row->gender) ?> <div><?= strtoupper($row->date_of_birth) ?></div></td>
                   </tr>
     
                  
                 
                   
                   
               <?php 
               
               $precinct_no = $row->precinct_no;
                $ctr++;
               }  ?>
               
			 
			 
			 
			 
			 
			 </tbody>
		</table>




        
        <?php
        
        /*
        Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'withActionColumn'=>false,
            'afterRow'=>function($model, $index, $key, $value)use(
			$totalItemCount,		
			&$totalItemCountctr,
			&$precinct_no
					){
         
		   $totalItemCountctr++;
		   
		  //if($totalItemCountctr>=$totalItemCount){
		   $row = ' 
			  <tr style="font-weight:bold; text-align:left;">
	
			    <td colspan="5">Prec No. '.$model->precinct_no.'</td>
	     
			  </tr>
			  ';
			  
			
			
		 //  }
			
			return  $row;
						
					},
					
            
            'columns' => [
                       ['class' => 'yii\grid\SerialColumn', 'visible'=>true],
                    
                       ['attribute' => 'precinct_no', 'format' => 'raw'],
                       ['attribute' => 'first_name', 'format' => 'raw', 'value' => 'fullnamelast', 'label' => 'FULLNAME'],
                       [//'attribute' => 'barangay', 
                       'format' => 'raw',
                       'label' => 'Address', 
                       'value'=>function($model)use(&$precinct_no) {
                              $model->purok = $model->purok?'Purok '.$model->purok.', ':null;
                              return implode(' ', [$model->house_no, $model->purok, $model->barangay]);
                        }
                       ],
                       
                       [//'attribute' => 'barangay', 
                       'format' => 'raw',
                       'label' => 'Sex / Birthdate', 
                       'value'=>function($model) {
                           
                              
                              return implode(' ', [$model->gender, '<div>'.$model->date_of_birth.'</div>']);
                        }
                       ],
                       
                   
                      
                    
                    
                ]
        ]);
        
        */
        
        ?>
        
        
        </div>
        
        
    
</div>

