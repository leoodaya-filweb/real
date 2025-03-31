<?php
use Yii;
use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\Database;
use app\widgets\BulkAction;
use app\widgets\FilterColumn;
use app\widgets\Grid;
use app\widgets\PrioritySectorFilter;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DatabaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->prioritySectorLabel ?: 'All Priority Sector Members';
$this->params['breadcrumbs'][] = [
	'label' => 'Database: Priority Sectors',
	'url' => $searchModel->indexUrl
];
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['showCreateButton'] = false; 
$this->params['showExportButton'] = true;
$this->params['activeMenuLink'] = '/database';
$this->params['createTitle'] = 'Create Database Entry';


$url = ['database/member','print' => true];

$this->params['headerButtons'] = implode(' ', [
    
   	Html::a('<i class="fa fa-print"></i> Print', '#',[
		'class' => "btn btn-bg-white btn-text-dark-50 btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm px-5 mr-3",
		'onClick' => 'popupCenter("'. Url::to(array_merge($url, App::queryParams())) .'")'
	]),
    
    Html::a('Import', ['database/import'], ['class' => 'btn btn-primary font-weight-bold']),
    $searchModel->headerCreateButton
]);




if(App::identity()->id==17){
 //print_r(App::identity()->filterColumns($searchModel, true, ($searchModel->priority_sector==11?'tblvawc':false) ));
 
 
 // print_r(App::identity()->getTableColumnsMeta($searchModel));
 
 
// print_r($searchModel->gettableColumns('tblvawc'));
}


?>

<?= PrioritySectorFilter::widget([
	'rowsummary' => $rowsummary,
	'data_report' => $searchModel->getDataReport($dataProviderReport),
]) ?>

<div class="database-index-page mt-10">
    <?= FilterColumn::widget(['searchModel' => $searchModel, 
     'customTblname'=>($searchModel->priority_sector==11?'tblvawc':false), 
   // 'searchModelOnly' => ($searchModel->priority_sector==11?true:false) 
    ]) ?>
    <?= Html::beginForm(['bulk-action'], 'post'); ?>
        <?= BulkAction::widget(['searchModel' => $searchModel]) ?>
        
        
        <?php 
        
        if ($searchModel->priority_sector==11){
            
          
         
         echo Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rowOptions' => function ($model, $index, $widget, $grid){
			           if($model->priority_sector==11){
					   return []; //['style' => 'background-color:#f1f1f1;']; 
			           }else{
			           return [];  
			           }
                   },
                   
            'columns' =>$searchModel->gettableColumns('tblvawc')
            
            /*
            [
                      [ 'class' => 'yii\grid\SerialColumn', 'header' => 'No.'],
                      ['class' => 'yii\grid\CheckboxColumn'],
                     'priority_sector' => [
			'attribute' => 'priority_sector',
			'format' => 'raw',
			'value'=> function ($model, $index){ 
                return $model->prioritySectorLabel;
			 },
			],
			'date_registered' => ['attribute' => 'date_registered', 'format' => 'raw', 'label' => 'Date'],
            'sector_id' => ['attribute' => 'sector_id', 'format' => 'raw', 'label' => 'Case ID No.'],
            
            'full_name' => [
                //'attribute' => 'first_name', 
                'format' => 'raw',
                'label' => 'Name',
                'value'=> function ($model, $index){ 
				    return $model->Fullnamefirst; //$model->Fullnamelast;
                    
                },
			  ],
			  'gender' => ['attribute' => 'gender', 'format' => 'raw', 'label' => 'Sex'],
			  'age' => ['attribute' => 'age', 'format' => 'raw'],
			  
			  'address' => ['attribute' => 'address', 'format' => 'raw', 'label' => 'Address'],
			  'vawc_case' => ['attribute' => 'vawc_case', 'format' => 'raw', 'label' => 'Case'],
			  'perpetrator' => ['attribute' => 'perpetrator', 'format' => 'raw', 'label' => 'Perpetrator'],
			  'perpetrator_relation' => ['attribute' => 'perpetrator_relation', 'format' => 'raw', 'label' => 'Relationship'],
			  'remarks' => ['attribute' => 'remarks', 'format' => 'raw', 'label' => 'Remarks'],

                ]
              */
            
        ]); 
         
         
         
          
            
        }else{
        
        echo Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rowOptions' => function ($model, $index, $widget, $grid){
			           if($model->priority_sector==11){
					   return []; //['style' => 'background-color:#f1f1f1;']; 
			           }else{
			           return [];  
			           }
                   }
        ]); 
        
        }
        
        
        ?>
        
        
        
        <?php if ($searchModel->priority_sector==11){  
        
        
       
        if($searchModel->date_range){
         $dates=explode( ' - ', $searchModel->date_range);
		 $year= date("Y", strtotime($dates[0]) );
        }
        
        
        ?>
       <hr/>
   
         <strong>CY <?= $year ?></strong>
        <h3>SUMMARY</h3>
        <table class="table table-striped">
           <thead>
               <tr>
                   <th style="width:16.6%; text-align:center;"></th>
                   <th style="width:16.6%; text-align:center;">TOTAL</th>
                   <th style="width:16.6%; text-align:center;">ECONOMIC</th>
                   <th style="width:16.6%; text-align:center;">PHYSICAL</th>
                   <th style="width:16.6%; text-align:center;">PSYCHOLOGICAL/ EMOTIONAL</th>
                   <th style="width:16.6%; text-align:center;">SEXUAL</th>
                   
               </tr>
           </thead> 
           <tbody>
               <?php
               $total=0;
               $economic=0;     
               $physical=0;
               $sexual=0;
               $psychological=0;
               $x=1;
               
               $locale = 'en_US';
               $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
               
               do{
                   
        $queryst=null; 
        
        if($x==1){
            $start=$year.'-01-01';
            $end=$year.'-03-31';
        }elseif($x==2){
            $start=$year.'-04-01';
            $end=$year.'-06-30';  
        }elseif($x==3){
            $start=$year.'-07-01';
            $end=$year.'-09-30';  
        }else{
            $start=$year.'-10-01';
            $end=$year.'-12-31';    
        }
        
        $queryst = Database::find()->select([
        "count(*) as total",    
        "sum(`vawc_case`='Physical') as physical", 
        "sum(`vawc_case`='Economic') as economic", 
        "sum(`vawc_case`='Sexual') as sexual", 
        "sum(`vawc_case`='Psychological/Emotional') as psychological"
        ])
        ->where("`vawc_case` is not null")
        ->andWhere(['priority_sector'=>11])
        ->andFilterWhere(['between',"date_registered",  $start, $end])
        ->asArray()
        ->one();
               
          $total+=$queryst['total'];  
          $economic+=$queryst['economic'];     
          $physical+=$queryst['physical']; 
          $sexual+=$queryst['sexual']; 
          $psychological+=$queryst['psychological']; 
 
               ?>
              <tr>
                 <td><?= $nf->format($x); ?> Quarter</td>
                 <td style="text-align:center;"><?= $queryst['total']; ?></td>
                 <td style="text-align:center;"><?= $queryst['economic']; ?></td>
                 <td style="text-align:center;"><?= $queryst['physical']; ?></td>
                 <td style="text-align:center;"><?= $queryst['psychological']; ?></td>
                 <td style="text-align:center;"><?= $queryst['sexual']; ?></td>
                
              </tr>
            <?php 
             $x++;    
              }while($x<=4);
                 ?>
             
             <tr>
                 <td><strong>TOTAL</strong></td>
                  <td style="text-align:center;font-weight: bold;"><?= $total ?></td>
                 <td style="text-align:center;font-weight: bold;"><?= $economic ?></td>
                 <td style="text-align:center;font-weight: bold;"><?= $physical ?></td>
                 <td style="text-align:center;font-weight: bold;"><?= $psychological ?></td>
                 <td style="text-align:center;font-weight: bold;"><?= $sexual ?></td>
              </tr>
              
               
           </tbody>
            
        </table>
        
        <?php }  ?>
        
        
    <?= Html::endForm(); ?> 
</div>