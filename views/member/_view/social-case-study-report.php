<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\Grid;

$data = $model->socialCaseStudyReport(App::queryParams());
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
        <div class="card-toolbar">
            <?= Html::a(<<< HTML
                <span class="svg-icon svg-icon-md svg-icon-white">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"></rect>
                        <rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13" rx="1.5"></rect>
                        <rect fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8" rx="1.5"></rect>
                        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"></path>
                        <rect fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6" rx="1.5"></rect>
                    </g>
                </svg>
                </span>New Certificate
            HTML, $model->getCreateTransactionLink('social-case-study-report'), [
                'class' => 'btn btn-primary font-weight-bolder font-size-sm'
            ]) ?>
        </div>
    </div>
    <div class="card-body pt-7">
      
        <?= Grid::widget([
            'options' => ['class' => 'social-case-study-report-table'],
            'dataProvider' => $data['dataProvider'],
            'searchModel' => $data['searchModel'],
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
                // 'searial' => ['class' => 'yii\grid\SerialColumn'],
                'date' => [
                    'attribute' => 'created_at', 
                    'label' => 'date',
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->fulldate;
                    }
                ],

                'createdByEmail' => [
                    'attribute' => 'createdByEmail', 
                    'label' => 'created by',
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->createdByEmail;
                    }
                ],

                'actions' => [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '<span style="color:#3699FF">action</span>',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center', 'width' => '70'],
                    'template' => '{action}',

                    'buttons' => [
                        'action' => function($url, $model) {
                            return \yii\helpers\Html::a('View', '#', [
                                'class' => 'btn btn-primary btn-sm btn-view-transaction',
                                'data-token' => $model->token
                            ]);
                        }
                    ]
                ]
            ]
        ]); ?>
    </div>
</div>
