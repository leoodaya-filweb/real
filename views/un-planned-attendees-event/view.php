<?php

use app\helpers\Url;
use app\helpers\Html;
use app\models\Member;
use app\widgets\Detail;
use app\widgets\Anchors;
use app\widgets\AppIcon;
use app\widgets\Reminder;
use app\models\EventMember;
use app\widgets\Autocomplete;
use app\models\UnPlannedAttendeesEvent;
use app\models\search\UnPlannedAttendeesEventSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = 'Open Event: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Open Event', 'url' => (new UnPlannedAttendeesEvent())->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new UnPlannedAttendeesEventSearch();
$this->params['activeMenuLink'] = '/open-event';
$this->params['wrapCard'] = false;

$eventMemberData = EventMember::filter('age', ['event_id' => $model->id]);

$this->registerCss(<<< CSS
    .detail-view th {
        white-space: nowrap;
    }
    .detail-view label.badge {
        cursor: pointer;
    }
    .btn-profile:hover {
        text-decoration: underline;
        cursor: pointer;
    }
    .app-iconbox {
        box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px 0 !important;
    }
CSS);

$this->registerJs(<<< JS
    $('.detail-view label.badge').click(function() {
        let name = $(this).data('name');
        const url = new URL(window.location);
        url.searchParams.set('keywords', name);
        window.history.pushState({}, '', url);
        location.reload();
    });

    $(document).on('click', '.btn-profile', function() {
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
                    $('#modal-member-profile').modal('show')
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

    $(document).on('click', '.btn-event-member-detail', function() {
        let qr_id = $(this).data('qr_id');
        KTApp.blockPage({
            overlayColor: '#000000',
            state: 'warning',
            message: 'Please wait...'
        });
        $.ajax({
            url: app.baseUrl + 'member/detail',
            data: {
                qr_id: qr_id,
                template: '_receive-assistance-defail',
                event_id: {$model->id}
            },
            dataType: 'json',
            success: function(s) {
                if(s.status == 'success') {
                    $('#modal-receive-assistance-details .modal-body').html(s.detailView);
                    $('#modal-receive-assistance-details').modal('show');
                }
                else {
                    Swal.fire("Error", s.error, "error");
                }
                KTApp.unblockPage();
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblockPage();
            }
        });
    });

    let loadMember = function(qr_id) {
        KTApp.blockPage({
            overlayColor: '#000000',
            state: 'warning',
            message: 'Please wait...'
        });

        $.ajax({
            url: app.baseUrl + 'member/detail',
            data: {
                qr_id: qr_id,
                template: '_receive-assistance-form',
                event_id: {$model->id}
            },
            dataType: 'json',
            success: function(s) {
                if(s.status == 'success') {
                    $('#modal-receive-assistance .modal-body').html(s.detailView);
                    $('#modal-receive-assistance').modal('show');
                }
                else {
                    Swal.fire("Error", s.error, "error");
                }
                KTApp.unblockPage();
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblockPage();
            }
        });
    }
    let showScanForm = function() {
        Swal.fire({
            title: "Scan QR Code",
            text: "Write QR ID Here!",
            input: 'text',
        }).then((result) => {
            if (result.value) {
               loadMember(result.value);
            }
        });
    }
 
    $(document).on('click', '.btn-scan-qr', function() {
        showScanForm();
    });

    $(document).on('click', '.btn-claim', function() {
        loadMember($(this).data('qr_id'));
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

    $('.modal input[type="checkbox"]').change(function() {
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

    $('.btn-summary').click(function() {
        KTApp.block('body', {
            overlayColor: '#000000',
            state: 'warning',
            message: 'Loading...'
        });
        $.ajax({
            url: app.baseUrl + 'un-planned-attendees-event/summary-report',
            data: {token: '{$model->token}'},
            dataType: 'json',
            method: 'get',
            success: function(s) {
                if(s.status == 'success') {
                    $('#modal-summary-report .modal-body').html(s.report);
                    $('#modal-summary-report').modal('show');
                }
                KTApp.unblock('body');
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('body');
            }
        });
    });

    let popupCenter = (url, title='Print Report', w=1000, h=700) => {
        // Fixes dual-screen position                             Most browsers      Firefox
        const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        const dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;
        const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
        const systemZoom = width / window.screen.availWidth;
        const left = (width - w) / 2 / systemZoom + dualScreenLeft
        const top = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow = window.open(url, title, 
          `
          scrollbars=yes,
          width=(w/systemZoom), 
          height=(h/systemZoom), 
          top=top, 
          left=left
          `
        )
        if (window.focus) newWindow.print();
    }
JS);

$this->params['headerButtons'] = implode(" ", [
    Html::a('Create Post Activity Report', ['post-activity-report/create-by-open-event', 'token' => $model->token], [
        'class' => 'btn btn-outline-success btn-summary font-weight-bold',
    ]),
    Html::a('View Summary', '#', [
        'class' => 'btn btn-success btn-summary font-weight-bold',
    ]),
    Html::a('Documents & Photos', '#modal-documents', [
        'data-toggle' => 'modal',
        'class' => 'btn btn-secondary font-weight-bold',
    ]) ,
    Anchors::widget([
        'names' => ['update', 'delete', 'log'], 
        'model' => $model,
    ])
]);
?>

<div class="event-view-page">
    <div class="row mt-7" data-sticky-container>
        <div class="col-md-4">
            <div <?= $dataProvider->totalCount > 20 ? 'data-sticky="true"': '' ?>  data-margin-top="100">
                <div class="card card-custom gutter-b ">
                    <div class="card-header" style="height: 6.5em;">
                        <div class="card-title">
                            <h3 class="card-label">Change Status</h3>
                        </div>
                        <div class="card-toolbar">
                            <?= $model->eventStatus ?>
                        </div>
                    </div>
                </div>
                <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                    'title' => 'Event Details',
                ]); ?>
                    <?= Detail::widget(['model' => $model]) ?>
                <?php $this->endContent(); ?>
            </div>
        </div>
        <div class="col-md-8">
            <div class="container-total mb-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="wave wave-animate-slower app-iconbox card card-custom card-stretch">
                            <div class="card-body">
                                <div class="d-flex align-items-center p-5">
                                    <div class="mr-6 icon-content">
                                        <div class="svg-icon svg-icon-primary svg-icon-4x">
                                            <?= AppIcon::widget(['icon' => 'user']) ?>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="#" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">
                                            Male Attendees
                                            <span class="text-primary">
                                                (<?= Html::number($model->totalAttended['Male'] ?? 0) ?>)
                                            </span>
                                        </a>
                                        <div class="text-dark-75">
                                            Total number of male attended
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="wave wave-animate-slower app-iconbox card card-custom card-stretch">
                            <div class="card-body">
                                <div class="d-flex align-items-center p-5">
                                    <div class="mr-6 icon-content">
                                        <div class="svg-icon svg-icon-danger svg-icon-4x">
                                            <?= AppIcon::widget(['icon' => 'user']) ?>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="#" class="text-dark text-hover-danger font-weight-bold font-size-h4 mb-3">
                                            Female Attendees
                                            <span class="text-danger">
                                                (<?= Html::number($model->totalAttended['Female'] ?? 0) ?>)
                                            </span>
                                        </a>
                                        <div class="text-dark-75">
                                            Total number of female attended
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => "{$model->completedTabName} Beneficiaries",
                'stretch' => false,
                'toolbar' => <<< HTML
                    <div class="card-toolbar">
                        <a href="#modal-attend" class="btn btn-outline-primary font-weight-bolder" data-toggle="modal">
                            Add Attendees
                        </a>
                    </div>
                HTML
            ]); ?>
                <?= $this->render('_beneficiaries', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'eventMemberData' => $eventMemberData,
                ]) ?>
            <?php $this->endContent(); ?>
        </div>
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

<div class="modal fade" id="modal-documents" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Documents
                </h5>
                <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" data-scroll="true">
                <?= $this->render('form/documents', [
                    'model' => $model,
                    'withTitle' => false,
                    'tabData' => $model::STEP_FORM[2]
                ]) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold btn-close-search-qr-id" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-summary-report" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-default modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Summary Report
                </h5>
                <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" data-scroll="true">

            </div>
            <div class="modal-footer">
                <a class="btn btn-success" href="#!" 
                    onclick="popupCenter('<?= Url::to([
                        'event/export-summary', 
                        'token' => $model->token,
                        'type' => 'print',
                        'template' => 'un-planned-attendees-event'
                    ]) ?>')">
                    <i class="fas fa-print"></i>
                    Print
                </a>
                <button type="button" class="btn btn-light-danger font-weight-bold btn-close-search-qr-id" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-attend" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl  modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Event Attendance
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
                        'placeholder' => 'Type name, household or QR ID',
                        'autofocus' => true
                    ]),
                    'submitOnclickJs' => <<< JS
                        let modalContainer = '#modal-attend .modal-body';
                        KTApp.block(modalContainer, {
                            overlayColor: '#000000',
                            state: 'warning',
                            message: 'Please wait...'
                        });
                        $.ajax({
                            url: app.baseUrl + 'event/add-member',
                            data: {
                                keywords: inp.value, 
                                token: '{$model->token}',
                                member_id: '',
                                template: '_receive-assistance-form'
                            },
                            method: 'get',
                            dataType: 'json',
                            success: function(s) {
                                if (s.status == 'success') {
                                    $('.member-details-container').html(s.detailView);
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
                    'url' => Url::to(['member/find-name-by-keywords'])
                ]) ?>
                <p class="font-weight-bold mt-2">
                    Member not found ? Create <?= Html::a('here!', (new Member())->createUrl, [
                        'target' => '_blank'
                    ]) ?>
                </p>

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
