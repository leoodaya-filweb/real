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
$this->params['showExportButton'] = false;
$this->params['activeMenuLink'] = '/database';
$this->params['createTitle'] = 'Create Database Entry';
$this->params['noFooter']=true;


  if($searchModel->date_range){
         $dates=explode( ' - ', $searchModel->date_range);
		 $year= date("Y", strtotime($dates[0]) );
    }

?>


<div class="database-index-page mt-10" style="widht:210mm; margin:auto;">
    
      <h3>DATABASE OF VAWC, TIP AND OTHER GENDER BASED VIOLENCE</h3>
      <strong>CY <?= $year ?></strong>
    
        <?php 
        
        if ($searchModel->priority_sector==11){
            
          
         
         echo Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'withActionColumn'=>false,
            'layout'=>'<div class="my-2">{items}</div>',
            'options'=>null,
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
                     
                  
			'date_registered' => ['attribute' => 'date_registered', 'format' => 'raw', 'label' => 'Date', 'enableSorting' => false],
            'sector_id' => ['attribute' => 'sector_id', 'format' => 'raw', 'label' => 'CASE #', 'enableSorting' => false],
            
            'full_name' => [
                //'attribute' => 'first_name', 
                'format' => 'raw',
                'label' => 'Name',
                'value'=> function ($model, $index){ 
				     return $model->Fullnamefirst; //$model->Fullnamelast;
                    
                },
			  ],
			  'gender' => ['attribute' => 'gender', 'format' => 'raw', 'label' => 'Sex', 'enableSorting' => false],
			  'age' => ['attribute' => 'age', 'format' => 'raw', 'enableSorting' => false],
			  
			  'address' => ['attribute' => 'address', 'format' => 'raw', 'label' => 'Address', 'enableSorting' => false],
			  'vawc_case' => ['attribute' => 'vawc_case', 'format' => 'raw', 'label' => 'Case', 'enableSorting' => false],
			  'perpetrator' => ['attribute' => 'perpetrator', 'format' => 'raw', 'label' => 'Perpetrator', 'enableSorting' => false],
			  'perpetrator_relation' => ['attribute' => 'perpetrator_relation', 'format' => 'raw', 'label' => 'Relationship', 'enableSorting' => false],
			  'remarks' => ['attribute' => 'remarks', 'format' => 'raw', 'label' => 'Remarks', 'enableSorting' => false],

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
        
        
        
        <?php if ($searchModel->priority_sector==11){   ?>
       <hr/>
   
        <div class="break-before-report" style="margin:30px 0px;">
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
        
        
        
                  <div class="mt-30" style="padding-left: 50px;">
						<p>PREPARED BY:</p>
						<p class="mt-3">
							<span class="font-weight-bolder text-uppercase"><?php echo App::identity()->fullname ?></span>
							<br><?php echo App::identity()->profile->position; ?>
						</p>
					</div>

					<div class="mt-20 mb-50" style="padding-left: 500px; padding-bottom:100px;">
						<p>NOTED BY:</p>
						<p class="mt-3">
							<span class="font-weight-bolder text-uppercase"><?php echo App::setting('personnel')->mswdo; ?></span>
							<br>MSWDO
						</p>
					</div>
        
        
        
         </div>
        
        <?php }  ?>
        
        
    <?= Html::endForm(); ?> 
</div>