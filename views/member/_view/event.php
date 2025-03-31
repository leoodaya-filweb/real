<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\EventCategory;
use app\models\Transaction;
use app\widgets\Anchor;
use app\widgets\CertificateOfIndigency;
use app\widgets\Grid;

$data = $model->eventMember(App::queryParams());

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

    $('.filter-category-type').change(function() {
        $(this).closest('form').submit();
    });
JS);
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
        <form action="<?= Url::current() ?>" method="get" class="d-flex justify-content-between">
            <div>
                <input type="hidden" name="tab" value="<?= App::get('tab') ?>">
                <?= Html::dropDownList('event_category', App::get('event_category'), EventCategory::dropdown(), [
                    'class' => 'form-control filter-category-type mb-5',
                    'prompt' => '- Select Category Type -',
                ]) ?>
            </div>
            <div>
                <input placeholder="Search" type="text" name="keywords" value="<?= App::get('keywords') ?>" class="form-control">
            </div>
        </form>
      
        <?= Grid::widget([
            'options' => ['class' => 'certificate-table'],
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
                'searial' => ['class' => 'yii\grid\SerialColumn'],
                'id' => [
                    'attribute' => 'id', 
                    'label' => 'photo',
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::image($model->photo, ['w' => 50, 'quality' => 90]);
                    }
                ],
                'eventName' => [
                    'attribute' => 'eventName', 
                    'label' => 'Event name',
                    'format' => 'raw',
                    'value' => function($model) {
                        return Anchor::widget([
                            'title' => $model->eventName,
                            'link' => $model->event->viewUrl,
                            'text' => true,
                            'options' => [
                                'target' => '_blank'
                            ]
                        ]);
                    }
                ],
                'date' => [
                    'attribute' => 'created_at', 
                    'label' => 'date',
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->fulldate;
                    }
                ],

                'status' => [
                    'attribute' => 'status', 
                    'label' => 'status',
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model->statusBadge;
                    }
                ],

                'actions' => [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '<span style="color:#3699FF">status</span>',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center', 'width' => '70'],
                    'template' => '{status}',
                    'buttons' => [
                        'status' => function($url, $model) {
                            return Html::a('Details', '#', [
                                'class' => 'btn btn-light-success btn-sm btn-event-member-detail font-weight-bolder',
                                'data-qr_id' => $model->qrId,
                                'data-event_id' => $model->event_id
                            ]);
                        }
                    ]
                ]
            ]
        ]); ?>
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