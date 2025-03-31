<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\CivilStatus;
use app\models\EducationalAttainment;
use app\models\EventMember;
use app\models\Household;
use app\models\Member;
use app\models\Sex;
use app\widgets\ActiveForm;
use app\widgets\Filter;
use yii\helpers\ArrayHelper;

$url = Url::to(['event/remove-members', 'token' => $model->token]);

$this->registerJs(<<< JS
    $('.bulk-action-label').click(function() {
        let widgetId = $('.table-responsive').attr('id');
        var checkedBoxes = $('#' + widgetId).yiiGridView('getSelectedRows');

        Swal.fire({
            title: "Are you sure?",
            text: "You are going to remove " + checkedBoxes.length + ' member(s).',
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, remove it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then(function(result) {
            if (result.value) {
                KTApp.block('body', {
                    overlayColor: '#000',
                    state: 'warning',
                    message: 'Please wait ....'    
                })
                $.ajax({
                    url: '{$url}',
                    data: {member_ids: checkedBoxes},
                    method: 'post',
                    dataType: 'json',
                    success: function(s) {
                        if(s.status == 'success') {
                            Swal.fire({
                                icon: "success",
                                title: "Removed Successfully",
                                showConfirmButton: false,
                                timer: 3000
                            });

                            window.location.reload();
                        }
                        else {
                            Swal.fire('Error', s.error, 'error');
                        }
                        KTApp.unblock('body');
                    },
                    error: function(e) {
                        Swal.fire('Error', e.responseText, 'error');
                        KTApp.unblock('body');
                    }
                })
            } 
        });
    });
JS);
?>
<div class="d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
    <div class="d-flex align-items-center flex-wrap">
        <div class="mr-2">
            {summary}
        </div>
        <?= Html::if($dataProvider->totalCount > $searchModel->pagination,
            function() use($searchModel, $paginations) {
                return $this->render('show', [
                    'paginations' => $paginations,
                    'searchModel' => $searchModel,
                ]);
            }
        ) ?>
        &nbsp;
        <button class="bulk-action-label btn btn-outline-secondary btn-sm" style="display: none;">
            Remove
        </button>
    </div>
    <div class="">
        <form action="<?= Url::current() ?>" method="get">
            <input type="hidden" name="tab" value="create-list">
            <input type="hidden" name="token" value="<?= $model->token ?>">
            <div class="input-group">
                <?= $searchModel->getAutocompleteInput($model) ?>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modal-advanced-filter">
                        Advanced
                    </button>
                </div>
            </div>
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

<div class="modal fade" id="modal-advanced-filter" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
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
                    'id' => 'form-advanced-filter', 
                    'method' => 'get',
                    'action' => ['create', 'token' => App::get('token'), 'tab' => 'create-list']
                ]); ?>
                    <?= $form->field($searchModel, 'keywords')->textInput([
                        'class' => 'form-control form-control-lg',
                        'name' => 'keywords',
                        'placeholder' => 'Search'
                    ])->label(false) ?>
                    <div class="accordion accordion-solid accordion-toggle-plus" id="accordion-filter">
                        <?= Html::foreach($searchModel->getAdvancedFilterData([
                            'event_id' => $model->id,
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
                                    <?= Html::if(($ages = EventMember::filter('age')) != null, implode('', [
                                            $form->field($searchModel, 'age_from')->dropDownList(
                                                $ages, [
                                                    'prompt' => 'Select Age',
                                                    'name' => 'age_from'
                                                ]
                                            ),
                                            $form->field($searchModel, 'age_to')->dropDownList(
                                                $ages, [
                                                    'prompt' => 'Select Age',
                                                    'name' => 'age_to'
                                                ]
                                            )
                                    ])) ?>
                                </div>
                            </div>
                        </div>



                    </div>
                    
                    <div class="mt-10">
                        <?= Html::a('Reset', [App::actionID(), 'token' => App::get('token'), 'tab' => 'create-list'], [
                            'class' => 'btn btn-light-primary font-weight-bold'
                        ]) ?>
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            Confirm Filter
                        </button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>

        </div>
    </div>
</div>