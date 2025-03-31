<?php

use app\helpers\Html;
use yii\grid\GridView;
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'tableOptions' => ['class' => 'table align-items-center table-flush table-striped'],
	'summaryOptions' => ['class' => 'dataTables_info', 'role' => 'status', 'aria-live' => 'polite'],
	'showFooter' => true,
	'footerRowOptions' => ['class' => 'text-center', 'style' => 'font-weight: bold'],
	'layout' => '{items}',
	'columns' => [
		[
			'attribute' => 'priority_sector',
			'label' => 'Priority Sector',
			'enableSorting' => $enableSorting,
			'format' => 'raw',
			'value'=> function ($model, $index) use($priority_sector) {  
				return $priority_sector[$model['priority_sector']]['label'];
			},
		],
		[
			'attribute' => 'gender',
			'label' => 'Male',
			'enableSorting' => $enableSorting,
			'headerOptions' => ['class' => 'text-center'], 
			'contentOptions' => ['class' => 'text-center'], 
			'format' => 'raw',
			'value'=> function ($model, $index) {  
				return implode('', [
					Html::a(
						Html::number($model['male_active']), 
						[
							'database/member', 
							'priority_sector' => $model['priority_sector'],
							'gender' => 'Male',
							'status' => 'Active',
						], 
						['title' => 'View details' ]
					),
					Html::tag('div', 
						implode(': ', [
							'Inactive',
							Html::number($model['male_inactive'])
						]), 
						['class' => 'text-muted']
					)
				]);
			},
		],
		[
			'attribute' => 'age',
			'label' => 'Female',
			'enableSorting' => $enableSorting,
			'headerOptions' => ['class' => 'text-center'], 
			'contentOptions' => ['class' => 'text-center'], 
			'format' => 'raw',
			'value'=> function ($model, $index) {  
				return implode('', [
					Html::a(
						Html::number($model['female_active']), 
						[
							'database/member', 
							'priority_sector' => $model['priority_sector'],
							'gender' => 'Female',
							'status' => 'Active',
						], 
						['title' => 'View details' ]
					),
					Html::tag('div', 
						implode(': ', [
							'Inactive',
							Html::number($model['female_inactive'])
						]), 
						['class' => 'text-muted']
					)
				]);
			},
		],
	]
]); ?>