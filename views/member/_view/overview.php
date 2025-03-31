<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\widgets\AppIcon;
use app\widgets\Iconbox;
use app\widgets\Reminder;

$household = $model->household;

$this->registerCss(<<< CSS
    .badge-facebook {
        color: #ffffff;
        background-color: #3b5998;
        border-color: #3b5998;
    }
    .badge-twitter {
        color: #ffffff;
        background-color: #1da1f2;
        border-color: #1da1f2;
    }

    #table-transactions_filter,
    #table-events_filter {
        text-align: right;
    }
    #table-transactions_filter label,
    #table-transactions_length label,
    #table-events_filter label,
    #table-events_length label {
        display: inline-flex;
    }
    #table-transactions_filter input,
    #table-events_filter input {
        margin-top: -5px;
        margin-left: 5px;
    }
    #table-transactions_length select,
    #table-events_length select {
        margin-top: -5px;
        margin-left: 5px;
        margin-right: 5px;
    }
    #table-transactions_paginate,
    #table-events_paginate {
        float: right;
    }
    .icon-box .card-body {
        padding: 0 !important;
    }
CSS);

$this->registerJs(<<< JS
    $(document).on('click', '.btn-event-member-detail', function() {
        let qr_id = $(this).data('qr_id'),
            event_id = $(this).data('event_id');
        KTApp.blockPage({
            overlayColor: '#000000',
            state: 'warning',
            message: 'Please wait...'
        });
        $.ajax({
            url: app.baseUrl + 'member/detail',
            data: {
                qr_id: qr_id,
                template: '_receive-assistance-detail-event',
                event_id: event_id
            },
            dataType: 'json',
            success: function(s) {
                if(s.status == 'success') {
                    $('#modal-receive-assistance-details .modal-title').html(
                        'Received Assistance Details: ' + s.event.name
                    );
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
    $('#table-transactions').DataTable();
    $('#table-events').DataTable();
JS);
?>
<?= Reminder::widget([
    'head' => 'Last Updated',
    'type' => 'info',
    'withDot' => false,
    'message' => $model->updateLogMessage
]) ?>
<div class="card card-custom gutter-b">
    <div class="card-header border-0 pt-6">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label font-weight-bolder font-size-h4 text-dark-75">
                <?= $tabData['title'] ?>
            </span>
            <span class="text-muted mt-3 font-weight-bold font-size-lg">
                <?= $model->subcategoriesTag ?>
            </span>
        </h3>
    </div>
    <div class="card-body pt-7">
        <div class="row">
            <div class="col-md-4">
                <?= Iconbox::widget([
                    'title' =>'Total Family Members',
                    'url' => Url::current(['tab' => 'family-composition']),
                    'iconContent' => Html::tag(
                        'div', AppIcon::widget(['icon' => 'add-user']), [
                        'class' => 'svg-icon svg-icon-warning svg-icon-4x',
                    ]) . $model->household->totalMembersTag,
                    'content' => 'Total numbers of this household members.',
                    'wrapperClass' => 'wave wave-animate-slower'
                ]) ?>
            </div>
            <div class="col-md-4">
                <?= Iconbox::widget([
                    'title' => 'Total Transactions',
                    'url' => Url::current(['tab' => 'transactions']),
                    'iconContent' => Html::tag(
                        'div', AppIcon::widget(['icon' => 'chart']), [
                        'class' => 'svg-icon svg-icon-primary svg-icon-4x',
                    ]) . $model->totalTransactionsTag,
                    'content' => 'Overall transaction recorded with this household member.',
                    'wrapperClass' => 'wave wave-animate-slow wave-primary'
                ]) ?>
            </div>
            <div class="col-md-4">
                <?= Iconbox::widget([
                    'title' => 'Total Assistance',
                    'url' => Url::current(['tab' => 'transactions']),
                    'iconContent' => Html::tag(
                        'div', AppIcon::widget(['icon' => 'money']), [
                        'class' => 'svg-icon svg-icon-success svg-icon-4x',
                    ]) . $model->totalAmountTransactionsTag,
                    'content' => 'Total cash assistance recorded with this household member.',
                    'wrapperClass' => 'wave wave-animate-slower wave-success'
                    // 'wrapperClass' => 'wave wave-animate-fast wave-success'
                ]) ?>
            </div>
        </div>

        <div class="row mt-7">
            <div class="col">

                <div class="card card-custom app-iconbox">
                    <div class="card-header card-header-tabs-line">
                        <div class="card-title">
                            <h3 class="card-label">
                                <span class="card-label font-weight-bold font-size-h4 text-dark-75">
                                    Recent Transactions (<?= $model->totalRecentTransactions ?>)
                                    <span class="text-warning">
                                        Last 6 Months
                                    </span>
                                </span>
                            </h3>
                        </div>
                        <div class="card-toolbar">
                            <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_1_3">
                                        <span class="nav-icon">
                                            <i class="flaticon2-chat-1"></i>
                                        </span>
                                        <span class="nav-text">Transactions</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " data-toggle="tab" href="#kt_tab_pane_2_3">
                                        <span class="nav-icon">
                                            <i class="flaticon2-drop"></i>
                                        </span>
                                        <span class="nav-text">Events</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade  active show" id="kt_tab_pane_1_3" role="tabpanel" aria-labelledby="kt_tab_pane_1_3">
                                <table class="table table-bordered table-head-solid" id="table-transactions">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>transaction type</th>
                                            <th width="100">status</th>
                                            <th class="text-right" width="100">date</th>
                                            <th class="text-center" width="100">action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?= Html::if(($transactions = $model->recentTransactions) != null, function() use($transactions) {

                                            return Html::foreach($transactions, function($transaction, $key) {
                                                $serial = ($key + 1);
                                                return <<< HTML
                                                    <tr>
                                                        <td>{$serial}</td>
                                                        <td>{$transaction->transactionTypeTag}</td>
                                                        <td>{$transaction->statusBadge}</td>
                                                        <td class="text-right">{$transaction->date}</td>
                                                        <td class="text-center">{$transaction->viewBtn}</td>
                                                    </tr>
                                                HTML;
                                            });
                                        }) ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="kt_tab_pane_2_3" role="tabpanel" aria-labelledby="kt_tab_pane_2_3">
                                <table class="table table-bordered table-head-solid" id="table-events">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Event</th>
                                            <th>category</th>
                                            <th width="100">status</th>
                                            <th class="text-right" width="100">date</th>
                                            <th class="text-center" width="100">action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?= Html::if(($eventMembers = $model->recentEventMembers) != null, function()use ($eventMembers) {

                                            return Html::foreach($eventMembers, function($eventMember, $key) {
                                                $serial = ($key + 1);
                                                return <<< HTML
                                                    <tr>
                                                        <td>{$serial}</td>
                                                        <td>{$eventMember->eventName}</td>
                                                        <td>{$eventMember->categoryLabel}</td>
                                                        <td>{$eventMember->statusBadge}</td>
                                                        <td class="text-right">{$eventMember->date}</td>
                                                        <td class="text-center">
                                                            <button class="btn btn-light-primary btn-event-member-detail btn-sm font-weight-bolder" data-qr_id="{$eventMember->qrId}" data-event_id="{$eventMember->event_id}">
                                                                View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                HTML;
                                            });
                                        }) ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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