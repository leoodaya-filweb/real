<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\search\EventMemberSearch;
use app\widgets\Autocomplete;
use app\widgets\Grid;

$d = $model->eventMemberData;

$searchModel = $d['searchModel'];
$searchModel->setAge(['event_id' => $model->id]);
$dataProvider = $d['dataProvider'];

$this->registerCss(<<< CSS
    .btn-profile:hover {
        text-decoration: underline;
        cursor: pointer;
    }
CSS);

$this->registerJs(<<< JS
    let viewMember = function(keywords) {
        let modalContainer = '#modal-add-member .modal-body';
        KTApp.block(modalContainer, {
            overlayColor: '#000000',
            state: 'warning',
            message: 'Please wait...'
        });
        $.ajax({
            url: app.baseUrl + 'event/add-member',
            data: {keywords: keywords, token: '{$model->token}'},
            method: 'get',
            dataType: 'json',
            success: function(s) {
                if (s.status == 'success') {
                    $('.member-details-container').html(s.detailView);
                    $('.member-details-container').prepend(s.confirmBtn);
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
    }

    $('input#input-search-member').on('keyup', function(e) {
        if (e.keyCode === 13) {
            viewMember($(this).val());
            e.target.focus();
            e.target.select();
        }
    });

    $('#modal-add-member').on('shown.bs.modal', function() {
        $(this).find('[autofocus]').focus();
    });

    $('.btn-profile').click(function() {
        let qr_id = $(this).data('qr_id');
        KTApp.block('body', {
            overlayColor: '#000000',
            state: 'warning',
            message: 'Please wait...'
        });
        $.ajax({
            url: app.baseUrl + 'member/detail',
            data: {qr_id: qr_id},
            dataType: 'json',
            success: function(s) {
                if (s.status == 'success') {
                    $('#modal-member-profile .modal-body').html(s.detailView);
                    $('#modal-member-profile').modal('show');
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
        });
    });

    $('.event-member-container input[name="selection[]"]').on('change', function() {
        let widgetId = $(this).closest('.table-responsive').attr('id'),
            bulkaction = $('.bulk-action-label');

        var checkedBoxes = $('#' + widgetId).yiiGridView('getSelectedRows');
        if(checkedBoxes.length > 0) {
            bulkaction.show();
        }
        else {
            bulkaction.hide();
        }
    });

    $('.btn-add-member').click(function() {
        $('.member-details-container').html('<div class="text-center mt-10"><h4>Member details will go here...</h4></div>');
        $('#modal-add-member').modal('show');
        $('#input-search-member').val('');
    });

    $('#eventmembersearch-age_from, #eventmembersearch-age_to').change(function() {
        let ageFrom = $('#eventmembersearch-age_from').val(),
            ageTo = $('#eventmembersearch-age_to').val(),
            card = $(this).closest('.card'),
            title = card.data('title');

        if(ageFrom && ageTo) {
            card.find('.card-title span').html(title + "("+ ageFrom +" - "+ ageTo +")");
        }
        else {
            card.find('.card-title span').html(title);
        }
    });

    $('#form-advanced-filter input[type="checkbox"]').change(function() {
        let checkboxes = $(this).closest('.card-body').find('input[type=checkbox]:checked'),
            card = $(this).closest('.card'),
            title = card.data('title');

        if (checkboxes.length > 0) {
           card.find('.card-title span').html(title +" ("+ checkboxes.length +")");
        }
        else {
           card.find('.card-title span').html(title);
        }

    });
JS);
?>


<div class="d-flex justify-content-between">
    <div class="d-flex">
        <h4 class="mb-10 font-weight-bolder text-dark mt-3">
            <?= $tabData['title'] ?>
        </h4>

        <div>
            <button class="btn font-weight-bold btn-success btn-add-member ml-5">
                Add Member
            </button>
        </div>
    </div>

    <div>
        <?= Html::a('Go to Summary', [App::actionID(), 'token' => App::get('token'), 'tab' => 'summary'], [
            'class' => 'btn btn-outline-success font-weight-bolder'
        ]) ?>
    </div>
</div>



<div class="event-member-container">
    <?= Grid::widget([
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'columns' => $searchModel->gridColumnsCreate,
        // 'withFilterModel' => true,
        'withActionColumn' => false,
        'layout' => $this->render('event-member-grid/layout', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'paginations' => App::params('pagination')
        ])
    ]); ?>
    <div class="text-right">
        <?= Html::a('Go to Summary', [App::actionID(), 'token' => App::get('token'), 'tab' => 'summary'], [
            'class' => 'btn btn-outline-success font-weight-bolder'
        ]) ?>
    </div>
</div>



<div class="modal fade" id="modal-member-profile" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Member's Information
                </h5>
                <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" data-scroll="true">
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold btn-close-search-qr-id" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-add-member" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                   Add Member to Event
                </h5>
                <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" data-scroll="true" style="height: 100vh">
                <?= Autocomplete::widget([
                    'input' => Html::input('text', 'keywords', '', [
                        'class' => 'form-control form-control-lg',
                        'id' => 'input-search-member',
                        'placeholder' => 'Type to search',
                        'autofocus' => true
                    ]),
                    'submitOnclickJs' => <<< JS
                        let modalContainer = '#modal-add-member .modal-body';
                        KTApp.block(modalContainer, {
                            overlayColor: '#000000',
                            state: 'warning',
                            message: 'Please wait...'
                        });
                        $.ajax({
                            url: app.baseUrl + 'event/add-member',
                            data: {keywords: inp.value, token: '{$model->token}'},
                            method: 'get',
                            dataType: 'json',
                            success: function(s) {
                                if (s.status == 'success') {
                                    $('.member-details-container').html(s.detailView);
                                    $('.member-details-container').prepend(s.confirmBtn);
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
                    'url' => Url::to(['member/find-by-keywords'])
                ]) ?>

                <div class="member-details-container mt-10">
                    <div class="text-center mt-10">
                        <h4>Member details will go here...</h4>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold btn-close-search-qr-id" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

