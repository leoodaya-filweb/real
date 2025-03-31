<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\CivilStatus;
use app\models\EducationalAttainment;
use app\models\EventMember;
use app\models\Household;
use app\models\Sex;
use app\widgets\ActiveForm;
use app\widgets\ExportButton;
use app\widgets\Filter;
use app\widgets\Grid;
use yii\helpers\ArrayHelper;

$searchModel->setAge([
    'status' => [EventMember::ATTENDED, EventMember::CLAIMED]
]);
?>


<div class="modal fade" id="modal-receive-assistance-details" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-receive-assistance-detailsLabel">
                    Received Assistance Details
                </h5>
                <button type="button" class="close btn-close" aria-label="Close" data-dismiss="modal">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?= ExportButton::widget([
    'title' => 'Export Beneficiaries',
    'controller' => 'event-member',
    'anchorOptions' =>  [
    'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
        'data-toggle' => 'dropdown',
        'aria-haspopup' => true,
        'aria-expanded' => false
    ],
    'printUrl' => [
        'event-member/print', 
        'event_id' => $model->id,
        'status' => [
            EventMember::CLAIMED,
            EventMember::ATTENDED
        ]
    ],
    'pdfUrl' => [
        'event-member/export-pdf', 
        'event_id' => $model->id,
        'status' => [
            EventMember::CLAIMED,
            EventMember::ATTENDED
        ]
    ],
    'csvUrl' => [
        'event-member/export-csv', 
        'event_id' => $model->id,
        'status' => [
            EventMember::CLAIMED,
            EventMember::ATTENDED
        ]
    ],
    'xlsUrl' => [
        'event-member/export-xls', 
        'event_id' => $model->id,
        'status' => [
            EventMember::CLAIMED,
            EventMember::ATTENDED
        ]
    ],
    'xlsxUrl' => [
        'event-member/export-xlsx', 
        'event_id' => $model->id,
        'status' => [
            EventMember::CLAIMED,
            EventMember::ATTENDED
        ]
    ],
]) ?>
<button class="btn btn-light-primary font-weight-bolder btn-sm ml-1" type="button" data-toggle="modal" data-target="#modal-advanced-filter-claimed">
    <i class="fas fa-filter"></i>
    Advanced Filter
</button>

<?= Grid::widget([
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'layout' => <<< HTML
        <div class="d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap">
                <div class="mr-2">
                    {summary}
                </div>
            </div>
            <div class="">
                <form method="get" action="{$model->viewUrl}">
                    <input type="hidden" name="token" value="{$model->token}">
                    {$searchModel->getAutocompleteInput($model, 'completed')}
                </form>
            </div>
        </div>
        <div class="my-2">
            {items}
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap">
                <div class="mr-2">
                    {summary}
                </div>
            </div>
            <div class="">
                {pager}
            </div>
        </div>
    HTML,
    'columns' => $searchModel->gridColumnsView,
]); ?>


<div class="modal fade" id="modal-advanced-filter-claimed" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-advanced-filterLabel">Advanced Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'form-advanced-filter-claimed', 
                    'method' => 'get',
                    'action' => ['view', 'token' => $model->token]
                ]); ?>
                    <input type="text" name="tab" value="claimed" class="app-hidden">
                    <?= $form->field($searchModel, 'keywords')->textInput([
                        'class' => 'form-control form-control-lg',
                        'name' => 'keywords',
                        'placeholder' => 'Search'
                    ])->label(false) ?>
                    <div class="accordion accordion-solid accordion-toggle-plus" id="accordion-filter">
                        <?= Html::foreach($searchModel->getAdvancedFilterData([
                            'event_id' => $model->id,
                            'status' => [
                                EventMember::CLAIMED,
                                EventMember::ATTENDED,
                            ]
                        ]), function($data, $attribute) use($searchModel, $form) {
                            $filter = Filter::widget([
                                'form' => $form,
                                'model' => $searchModel,
                                'attribute' => $attribute,
                                'title' => false,
                                'data' => $data['data']
                            ]);
                            return count($data['data']) <= 1? '': <<< HTML
                                <div class="card" data-title="{$data['title']}">
                                    <div class="card-header">
                                        <div class="card-title collapsed" data-toggle="collapse" data-target="#{$attribute}-container" aria-expanded="false">
                                            {$data['icon']}
                                            <span>{$data['title']} {$searchModel->totalFilterTag($attribute)}</span>
                                        </div>
                                    </div>
                                    <div id="{$attribute}-container" class="collapse" data-parent="#accordion-filter">
                                        <div class="card-body">
                                            {$filter}
                                        </div>
                                    </div>
                                </div>
                            HTML;
                        }) ?>

                        <div class="card" data-title="Age">
                            <div class="card-header">
                                <div class="card-title collapsed" data-toggle="collapse" data-target="#age-container">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>Age (<?= $searchModel->age_from ?> - <?= $searchModel->age_to ?>)</span>
                                </div>
                            </div>
                            <div id="age-container" class="collapse" data-parent="#accordion-filter">
                                <div class="card-body">
                                    <?= $form->field($searchModel, 'age_from')->dropDownList(
                                        $eventMemberData, [
                                            'prompt' => 'Select Age',
                                            'name' => 'age_from'
                                        ]
                                    ) ?>
                                    <?= $form->field($searchModel, 'age_to')->dropDownList(
                                        $eventMemberData, [
                                            'prompt' => 'Select Age',
                                            'name' => 'age_to'
                                        ]
                                    ) ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="mt-10">
                        <?= Html::a('Reset', ['view', 'token' => $model->token], [
                            'class' => 'btn btn-light-primary font-weight-bold'
                        ]) ?>
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            Confirim Filter
                        </button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>

        </div>
    </div>
</div>