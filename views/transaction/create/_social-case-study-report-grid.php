<?php

use yii\helpers\Html as YiiHtml;
use app\helpers\Html;
use app\widgets\Grid;
?>

<?= Grid::widget([
    'options' => ['class' => 'social-case-study-report-table'],
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'pager' => ['maxButtonCount' => 5],
    'layout' => <<< HTML
        <div class="d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap">
                <div class="mr-2">
                    {summary}
                </div>
            </div>
        </div>
        <div class="my-2">
            {items}
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="">
                {pager}
            </div>
        </div>
    HTML,
    'columns' => [
        'searial' => ['class' => 'yii\grid\SerialColumn'],
        'date' => [
            'attribute' => 'created_at', 
            'label' => 'date',
            'format' => 'raw',
            'value' => function($model) {
                return implode(' ', [
                    $model->fulldate,
                    Html::tag('label', $model->createdByEmail, [
                        'class' => 'badge badge-secondary'
                    ])
                ]);
            }
        ],

        'actions' => [
            'class' => 'yii\grid\ActionColumn',
            'header' => '<span style="color:#3699FF">action</span>',
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center', 'width' => '200'],
            'template' => '{action}',

            'buttons' => [
                'action' => function($url, $model) {
                    return implode(' ', [
                        YiiHtml::a('<i class="fa fa-eye"></i> View', $model->viewUrl, [
                            'class' => 'btn btn-info font-weight-bold btn-sm btn-view-transaction',
                            'data-token' => $model->token
                        ]),
                        YiiHtml::a('<i class="fa fa-edit"></i> Edit', $model->updateUrl, [
                            'class' => 'btn btn-primary font-weight-bold btn-sm',
                            'data-token' => $model->token
                        ])
                    ]);
                }
            ]
        ]
    ]
]); ?>
