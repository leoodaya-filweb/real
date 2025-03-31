<?php

use app\helpers\Html;
use app\helpers\Url;
use app\models\Member;
use app\models\search\MemberSearch;
use app\widgets\ActiveForm;
use app\widgets\Anchors;
use app\widgets\Autocomplete;
use app\widgets\Detail;
use app\widgets\Map;
use app\widgets\QRCode;

/* @var $this yii\web\View */
/* @var $model app\models\Member */

$this->title = 'Member: ' . $model->fullname; //$model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new MemberSearch();
// $this->params['showCreateButton'] = false; 
$this->params['wrapCard'] = false;

$formModel = $model->transferToExistingHouseholdModel;

// $this->registerJsFile((new Map())->getApi(), ['async' => true, 'defer' => true, 'position' => yii\web\View::POS_BEGIN]);

$this->registerJs(<<< JS

    $('.btn-transfer').click(function() {
        if($(this).data('to') == 'new') {
            KTApp.block('.member-view-page', {
                overlayColor: '#000000',
                state: 'primary',
                message: 'Please wait...'
            });
            $.ajax({
                url: app.baseUrl + 'member/transfer-to-new-household',
                data: {member_id: {$model->id}},
                method: 'get',
                dataType: 'json',
                success: function(s) {
                    if (s.status == 'success') {
                        $('#modal-transfer-to-new-household .modal-body').html(s.form);
                        $('#modal-transfer-to-new-household').modal('show');
                    }
                    else {
                        Swal.fire('Error', s.errorSummary, 'error');
                    }
                    KTApp.unblock('.member-view-page');
                },
                error: function(e) {
                    Swal.fire('Error', e.responseText, 'error');
                    KTApp.unblock('.member-view-page');
                }
            });
        }
        else {
            $('#modal-search-household').modal('show');
        }
    });

    $('#modal-search-household').on('shown.bs.modal', function () {
        $('#input-household-no').trigger('focus');
    })

    
    $('.btn-confirm-household').on('click', function() {
        KTApp.block('#modal-search-household .modal-content', {
            overlayColor: '#000',
            state: 'primary',
            message: 'Please wait...'
        });
        setTimeout(function() {
            KTApp.unblock('#modal-search-household .modal-content');
        }, 2000);

        return true;
    })
JS);

$this->params['headerButtons'] = implode(" ", [
    Html::a(<<< HTML
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
        </span>New Transaction
    HTML, $model->createTransactionLink, [
        'class' => 'btn btn-primary font-weight-bolder font-size-sm'
    ]),
    Html::if($model->canTransferToExistingHousehold || $model->canTransferToNewHousehold, 
        function() use($model) {
        $existing = $model->canTransferToExistingHousehold ? Html::a('Existing Household', '#', [
            'class' => 'dropdown-item btn-transfer',
            'data-to' => 'existing'
        ]): '';

        $new = $model->canTransferToNewHousehold ? Html::a('New Household', '#', [
            'class' => 'dropdown-item btn-transfer',
            'data-to' => 'new'
        ]): '';

        return <<< HTML
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-facebook font-weight-bold dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Transfer to:
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    {$new}
                    {$existing}
                </div>
            </div>
        HTML;
    }),
    Anchors::widget([
        'names' => ['update', 'duplicate', 'delete', 'log'], 
        'model' => $model
    ])
]);
?>
<div class="member-view-page">

    <div class="d-flex flex-row mt-5">
        <div class="flex-row-auto offcanvas-mobile w-300px w-xl-325px" id="kt_profile_aside">
            <?= $this->render('_view/tabs', [
                'model' => $model,
                'tab' => $tab,
                'tabData' => $tabData,
            ]) ?>
        </div>

        <div class="flex-row-fluid ml-lg-8">
            <?= $this->render("_view/{$tab}", [
                'model' => $model,
                'tabData' => $tabData,
                'searchModel' => $searchModel ?? '',
                'dataProvider' => $dataProvider ?? '',
            ]) ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-transfer-to-new-household" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-transfer-to-new-householdLabel">
                    Transfer to new Household : <?= $model->fullname ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                
                <button type="button" class="btn btn-primary font-weight-bold">Save</button>
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-entry-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" data-scroll="true">
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-search-household" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Search Household</h5>
                <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" data-scroll="true" style="height: 100vh">
                <?= Autocomplete::widget([
                    'input' => Html::input('text', 'keywords', '', [
                        'class' => 'form-control form-control-lg',
                        'id' => 'input-household-no',
                        'placeholder' => 'Type Household No',
                        'autofocus' => true
                    ]),
                    'submitOnclickJs' => <<< JS
                        let modalContainer = '#modal-search-household .modal-body';
                        KTApp.block(modalContainer, {
                            overlayColor: '#000000',
                            state: 'warning',
                            message: 'Please wait...'
                        });
                        $.ajax({
                            url: app.baseUrl + 'member/find-household-no',
                            data: {keywords: inp.value, html: true},
                            method: 'get',
                            cache: false,
                            dataType: 'json',
                            success: function(s) {
                                if (s.status == 'success') {
                                    $('#household-container').html(s.html);
                                    $('#transfertoexistinghouseholdform-household_id').val(s.model.id);
                                }
                                else {
                                    Swal.fire("Error", s.error, "error");
                                }
                                KTApp.unblock(modalContainer);
                            },
                            error: function(e) {
                                Swal.fire("Error", e.responseText, "error");
                                KTApp.unblock(modalContainer);
                            }
                        });
                    JS,
                    'url' => Url::to(['member/find-household-no'])
                ]) ?>

                <div id="household-container" class="my-10">
                    <div class="text-center">
                        <h3>Search Household</h3>
                        <p class="lead">
                            Household Details will go here.
                        </p>
                    </div>
                </div>
                <?php $form = ActiveForm::begin([
                    'action' => ['member/transfer-to-existing-household', 'member_id' => $model->id]
                ]);  ?>

                    <?= $form->field($formModel, 'head')->radioList([
                        Member::FAMILY_HEAD_YES => 'Head',
                        Member::FAMILY_HEAD_NO => 'Member',
                    ])->label('Family Position') ?>

                    <?= $form->field($formModel, 'member_id')->hiddenInput()->label(false) ?>
                    <?= $form->field($formModel, 'household_id')->hiddenInput()->label(false) ?>
                    <div class="text-right">
                        <button type="submit" class="btn btn-success btn-lg font-weight-bold btn-confirm-household">Confirm Household</button>
                        <?= Html::a('Cancel', '#', [
                            'class' => 'btn btn-light-danger btn-lg font-weight-bold btn-close-modal',
                            'data-dismiss' => 'modal'
                        ]) ?>
                    </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>