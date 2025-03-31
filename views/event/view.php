<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\CivilStatus;
use app\models\EducationalAttainment;
use app\models\EventMember;
use app\models\Sex;
use app\models\search\EventSearch;
use app\widgets\Anchors;
use app\widgets\AppIcon;
use app\widgets\Detail;
use app\widgets\ExportButton;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = 'Event: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new EventSearch();
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
    $('.li-tab').click(function() {
        let tab = $(this).data('tab');

        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        
        window.history.pushState({}, '', url);
    });

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
            url: app.baseUrl + 'event/summary-report',
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
    Html::a('Create Post Activity Report', ['post-activity-report/create-by-event', 'token' => $model->token], [
        'class' => 'btn btn-outline-success btn-summary font-weight-bold',
    ]),
    Html::a('View Summary', '#modal-documents', [
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
            <div <?= $u_dataProvider->totalCount > 20 ? 'data-sticky="true"': '' ?>  data-margin-top="100">
                <div class="card card-custom gutter-b ">
                    <div class="card-header">
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
            <div class="container-total mb-7">
                <div class="row">
                    <div class="col">
                        <div class="wave wave-animate-slower app-iconbox card card-custom card-stretch">
                            <div class="card-body">
                                <div class="d-flex align-items-center p-5">
                                    <div class="mr-6 icon-content">
                                        <div class="svg-icon svg-icon-success svg-icon-4x">
                                            <?= AppIcon::widget(['icon' => 'user']) ?>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="#" class="text-dark text-hover-success font-weight-bold font-size-h4 mb-3">
                                            <?= $model->completedTabName ?> 
                                            <span class="text-success">
                                                (<?= Html::number($model->totalCompleted) ?>)
                                            </span>
                                        </a>
                                        <div class="text-dark-75">
                                            Total number of beneficiaries <?= $model->completedTabName ?> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="wave wave-animate-slower app-iconbox card card-custom card-stretch">
                            <div class="card-body">
                                <div class="d-flex align-items-center p-5">
                                    <div class="mr-6 icon-content">
                                        <div class="svg-icon svg-icon-warning svg-icon-4x">
                                            <?= AppIcon::widget(['icon' => 'user']) ?>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="#" class="text-dark text-hover-warning font-weight-bold font-size-h4 mb-3">
                                            <?= $model->pendingTabName ?> 
                                            <span class="text-warning">
                                                (<?= Html::number($model->totalPending) ?>)
                                            </span>
                                        </a>
                                        <div class="text-dark-75">
                                            Total number of beneficiaries <?= $model->pendingTabName ?> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Beneficiaries',
                'stretch' => false,
                'toolbar' => <<< HTML
                    <div class="card-toolbar">
                        <ul class="nav nav nav-pills nav-pills-sm nav-secondary">
                            <li class="nav-item ml-0 li-tab" data-tab="unclaim">
                                <a class="nav-link py-2 px-4 font-weight-bolder font-size-sm {$unclaimClass}" data-toggle="tab" href="#unclaim">
                                    {$model->pendingTabName}
                                </a>
                            </li>
                            <li class="nav-item ml-0 li-tab" data-tab="claimed">
                                <a class="nav-link py-2 px-4 font-weight-bolder font-size-sm {$claimClass}" data-toggle="tab" href="#claim">
                                    {$model->completedTabName}
                                </a>
                            </li>
                        </ul>
                    </div>
                HTML
            ]); ?>
            <div class="tab-content">
                <div class="tab-pane fade <?= ($tab == 'unclaim')? 'show active': '' ?>" id="unclaim" role="tabpanel" aria-labelledby="unclaim">
                    <?= $this->render('_beneficiaries', [
                        'searchModel' => $u_searchModel,
                        'dataProvider' => $u_dataProvider,
                        'model' => $model,
                        'eventMemberData' => $eventMemberData,
                    ]) ?>
                </div>
                <div class="tab-pane fade <?= ($tab == 'claimed')? 'show active': '' ?>" id="claim" role="tabpanel" aria-labelledby="claim">
                    <div class="claimed-grid-container">
                        <?= $this->render('_claimed', [
                            'searchModel' => $c_searchModel,
                            'dataProvider' => $c_dataProvider,
                            'model' => $model,
                            'eventMemberData' => $eventMemberData,
                        ]) ?>
                    </div>
                </div>
            </div>

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
                        'type' => 'print'
                    ]) ?>')">
                    <i class="fas fa-print"></i>
                    Print
                </a>
                <button type="button" class="btn btn-light-danger font-weight-bold btn-close-search-qr-id" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

