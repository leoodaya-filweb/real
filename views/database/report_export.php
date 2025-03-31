<?php

use app\helpers\Html;
use app\helpers\Url;
use app\models\Database;
use app\widgets\BulkAction;
use app\widgets\FilterColumn;
use app\widgets\Grid;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DatabaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$priority_sector = Database::priorityReIndex();

	 
?>


<?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
			'tableOptions' => ['class' => 'table align-items-center table-flush table-striped'],
			'summaryOptions' => ['class' => 'dataTables_info', 'role'=>"status", 'aria-live'=>"polite"],
			 'layout' => '{items}',
			
			 'columns' => [
                        ['class' => 'yii\grid\SerialColumn', 'visible'=>false],

                       

                        [ //'attribute'=>'priority_sector',
                            'label'=>'Priority Sector',
							'headerOptions' =>['class'=>'text-center', 'style'=>'background-color: #181c32; color:#ffffff; font-weight: 600;'], 
							'contentOptions' =>['class'=>'text-center', 'style'=>''], 
                            'value'=> function ($model, $index)use($priority_sector){  
				                  return $priority_sector[$model['priority_sector']]['label'];
                            },
                            'format' => 'raw',
                        ],
						
						
						 [ //'attribute'=>'priority_sector',
                            'label'=>'Male',
                            'value'=> function ($model, $index) use($searchModel){  
							      $active_inactive='<div class="text-muted">Inactive: '.number_format($model['male_inactive'], 0, '.', ',').'</div>';
							
                                 return number_format($model['male_active'], 0, '.', ',');
								 return Html::a(number_format($model['male_active'], 0, '.', ','), 
					['database/index', 
					'priority_sector'=>$model['priority_sector'],
					'gender'=>'Male',
					'status'=>'Active',
					'date_range'=>$searchModel->date_range,
					], 
					[ 'title' => 'View details' ]
					).$active_inactive;
					
					
                            },
                            'format' => 'raw',
                        ],
						
						
						[ //'attribute'=>'priority_sector',
                            'label'=>'Female',
                            'value'=> function ($model, $index) use($searchModel){  
							$active_inactive='<div class="text-muted">Inactive: '.number_format($model['female_inactive'], 0, '.', ',').'</div>';
							
							return number_format($model['female_active'], 0, '.', ',');
                               return Html::a(number_format($model['female_active'], 0, '.', ','), 
					['database/index', 
					'priority_sector'=>$model['priority_sector'],
					'gender'=>'Female',
					'status'=>'Active',
					'date_range'=>$searchModel->date_range,
					], 
					[ 'title' => 'View details' ]
					).$active_inactive;
                            },
                            'format' => 'raw',
                        ],
						
						
						
						[ //'attribute'=>'priority_sector',
                            'label'=>'Ratio',
                            'value'=> function ($model, $index) use($searchModel){  
                                return '';
                            },
                            'format' => 'raw',
							'visible'=>false
                        ],
						
						/*[//'attribute'=>'priority_sector',
                            'label'=>'Total',
                            'value'=> function ($model, $index) use($searchModel){  
							   $active_inactive='<div class="text-muted">Inactive: '.number_format($model['inactive'], 0, '.', ',').'</div>';
                               
							   return number_format($model['active'], 0, '.', ',');
							   
							   return Html::a(number_format($model['active'], 0, '.', ','), 
					['database/index', 
					'priority_sector'=>$model['priority_sector'],
					'status'=>'Active',
					'date_range'=>$searchModel->date_range,
					], 
					[ 'title' => 'View details' ]
					).$active_inactive;
                            
							},
                            'format' => 'raw',
                        ],*/
						
						
						
						 /*[
						 'class' => 'yii\grid\ActionColumn',
						 'visible' =>false
						 ]*/
					]
			
        ]); 
?>