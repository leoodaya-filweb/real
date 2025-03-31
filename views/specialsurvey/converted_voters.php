<?php
use yii\grid\GridView;




echo "<h3>Converted Voters Count: $convertedCount</h3>";

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'survey_name',
        'household_no',
        'voter_name',
        [
            'attribute' => 'previous_color',
            'label' => 'Previous Color',
            'value' => function ($model) {
                return $model['previous_color'] == 2 ? 'Gray' : $model['previous_color'];
            }
        ],
        [
            'attribute' => 'current_color',
            'label' => 'Current Color',
            'value' => function ($model) {
                $colors = [
                    1 => 'Black',
                    2 => 'Gray',
                    3 => 'Green',
                    4 => 'Red'
                ];
                return $colors[$model['current_color']] ?? 'Unknown';
            }
        ]
    ]
]); 


?>
