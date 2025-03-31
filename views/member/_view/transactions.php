<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Transaction;
use app\widgets\Grid;
?>
<div class="card card-custom card-stretch gutter-b">
    <div class="card-header border-0 pt-6">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label font-weight-bolder font-size-h4 text-dark-75">
                <?= $tabData['title'] ?>
            </span>
            <span class="text-muted mt-3 font-weight-bold font-size-lg">
                <?= $tabData['description'] ?>
            </span>
        </h3>
    </div>
    <div class="card-body pt-7">
        <?= Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'withFilterModel' => true,
            'columns' => [
                'serial' => ['class' => 'yii\grid\SerialColumn'],
                'transaction_type' => [
                    'attribute' => 'transaction_type',
                    'value' => 'transactionTypeName', 
                    'format' => 'raw',
                    'filter' => $model->transactionTypeFilter,
                    'filterInputOptions' => [
                        'name' => 'transaction_type',
                        'class'=>'form-control',
                        'prompt' => '- Select Type -'
                    ]
                ],
               
                'status' => [
                    'attribute' => 'status', 
                    'value' => 'transactionStatusLabel', 
                    'format' => 'raw',
                    'filter' => $model->statusFilter,
                    'filterInputOptions' => [
                        'name' => 'status',
                        'class'=>'form-control',
                        'prompt' => '- Select -'
                    ]
                ],
                'amount' => [
                    'attribute' => 'amount', 
                    'format' => 'raw',
                    'value' => 'formattedAmount',
                    'filter' => Html::input('number', 'amount', $searchModel->amount, [
                        'class'=>'form-control',
                    ]),
                    'filterInputOptions' => [
                        'name' => 'amount',
                        'class'=>'form-control',
                    ]
                ],
                'created_at' => [
                    'attribute' => 'created_at', 
                    'format' => 'fulldate',
                    'filterInputOptions' => [
                        'name' => 'created_at',
                        'class'=>'form-control',
                    ]
                ],
                'last_updated' => [
                    'attribute' => 'updated_at',
                    'label' => 'last updated',
                    'format' => 'ago',
                    'filterInputOptions' => [
                        'name' => 'updated_at',
                        'class'=>'form-control',
                    ]
                ],
                // 'actions' => [
                //     'class' => 'yii\grid\ActionColumn',
                //     'header' => '<span style="color:#3699FF">action</span>',
                //     'headerOptions' => ['class' => 'text-center'],
                //     'contentOptions' => ['class' => 'text-center', 'width' => '70'],
                //     'template' => '{action}',

                //     'buttons' => [
                //         'action' => function($url, $model) {
                //             return \yii\helpers\Html::a('View', '#', [
                //                 'class' => 'btn btn-primary btn-sm btn-view-transaction',
                //                 'data-token' => $model->token
                //             ]);
                //         }
                //     ]
                // ]
            ]
        ]); ?>
    </div>
</div>
