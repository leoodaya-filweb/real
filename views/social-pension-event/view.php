<?php

use app\helpers\Html;
use app\helpers\Url;
use app\models\EventMember;
use app\models\SocialPensionEvent;
use app\models\search\SocialPensionEventSearch;
use app\widgets\Anchors;
use app\widgets\AppIcon;
use app\widgets\Detail;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = 'Social Pension Event: ' . $model->mainAttribute;
$this->params['breadcrumbs'][] = ['label' => 'Social Pension Event', 'url' => (new SocialPensionEvent())->indexUrl];
$this->params['breadcrumbs'][] = $model->mainAttribute;
$this->params['searchModel'] = new SocialPensionEventSearch();
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
        let slug = $(this).data('slug');
        KTApp.block('body', {
            overlayColor: '#000000',
            state: 'warning',
            message: 'Please wait...'
        });
        $.ajax({
            url: app.baseUrl + 'masterlist/view',
            data: {slug: slug},
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
    // Html::a('View Summary', '#modal-documents', [
    //     'class' => 'btn btn-success btn-summary font-weight-bold',
    // ]),
    Html::a('Create Post Activity Report', ['post-activity-report/create-by-social-pension-event', 'token' => $model->token], [
        'class' => 'btn btn-outline-success btn-summary font-weight-bold',
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
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Beneficiaries',
                'stretch' => false,
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

