<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\form\transaction\WhiteCardForm;
use app\widgets\ActiveForm;
use app\widgets\TinyMce;
use app\widgets\Webcam;

$whitecard = new WhiteCardForm(['transaction_id' => $model->id]);
$createDocument = App::get('create_document');
$createDocument  = ($model->white_card)? 'false': $createDocument;

$whitecardFile = $model->whitecardFile;

$this->registerJs(<<< JS
    $('.btn-create-white-card').click(function() {
        $('#modal-create-white-card').modal('show');
    });

    $('.btn-update-white-card').click(function() {
        KTApp.blockPage({
            overlayColor: '#000000',
            state: 'warning', // a bootstrap color
            message: 'Please wait...'
        });
        $.ajax({
            url: app.baseUrl + 'transaction/view',
            data: {token: '{$model->token}'},
            dataType: 'json',
            method: 'get',
            success: function(s) {
                var editor = tinymce.get('whitecardform-white_card'); 
                editor.setContent(`{$model->white_card}`);
                $('#modal-create-white-card').modal('show');
                KTApp.unblockPage();
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblockPage();
            }    
        });
    });

    if('{$createDocument}' == 'white-card') {
        $('.btn-create-white-card').click();
    }
JS);
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => "White Card {$model->whiteCardActionBtn}",
     'toolbar' => <<< HTML
        <div class="card-toolbar">
            {$model->whiteCardButton}
        </div>
    HTML
]); ?>

    <ul class="nav nav-tabs nav-bold nav-tabs-line">
        
         <li class="nav-item" data-tab="main">
            <a class="nav-link active" data-toggle="tab" href="#tab-create">
                <span class="nav-icon"><i class="fa fa-edit"></i></span>
                <span class="nav-text">White Card Form</span>
            </a>
        </li>
        
        <li class="nav-item" data-tab="role-access">
            <a class="nav-link " data-toggle="tab" href="#tab-upload">
                <span class="nav-icon"><i class="fas fa-upload"></i></span>
                <span class="nav-text">Upload White Card</span>
            </a>
        </li>
    
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active pt-10" id="tab-create" role="tabpanel" aria-labelledby="tab-create">
            <?php //echo  $model->whiteCardButton ?>
            <div class="pt-10">
                <?= Html::ifElse($model->white_card, function() use($model) {
                    return Tinymce::widget([
                        'content' => $model->white_card,
                        'menubar' => false,
                        'toolbar' => 'print',
                        'plugins' =>  'print pagebreak',
                        'readonly' => true,
                        'setup' => <<< JS
                            editor.on('SkinLoaded', function() {
                                $(".tox-toolbar-overlord").removeClass('tox-tbtn--disabled');
                                $(".tox-toolbar-overlord").attr( 'aria-disabled', 'false' );
                                // And activate ALL BUTTONS styles
                                $(".tox-toolbar__group button").removeClass('tox-tbtn--disabled');
                                $(".tox-toolbar__group button").attr( 'aria-disabled', 'false' );
                            });
                        JS
                    ]);
                }, Html::tag('h5', 'No White Card yet')) ?>
            </div>
            <div class="mt-5">
                <?= $model->whiteCardActionBtn ?>
            </div>
        </div>
        <div class="tab-pane fade pt-10" id="tab-upload" role="tabpanel" aria-labelledby="tab-upload">
            <div class="row">
                <div class="col-md-5">
                    <?= Html::if(
                        App::identity()->can('add-whitecard-file'), 
                        Webcam::widget([
                            'tag' => 'Transaction',
                            'model' => $model,
                            'inputValue' => 'Whitecard',
                            'attribute' => 'whitecard_file',
                            'ajaxSuccess' => <<< JS
                                KTApp.block('body', {
                                    overlayColor: '#000',
                                    state: 'warning',
                                    message: 'Please wait...'
                                })
                                $.ajax({
                                    url: app.baseUrl + 'transaction/add-whitecard-file',
                                    data: {
                                        id: {$model->id},
                                        token: s.file.token
                                    },
                                    method: 'post',
                                    dataType: 'json',
                                    success: function(r) {
                                        if(r.status == 'success') {
                                            Swal.fire({
                                                icon: "success",
                                                title: r.message,
                                                showConfirmButton: false,
                                                timer: 1000
                                            });

                                            location.reload();
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
                            JS
                        ])
                    ) ?>
                </div>
                <div class="col-md-7 text-center">
                    <?= Html::image($model->whitecard_file, ['w' => 400], [
                        'class' => 'img-fluid',
                        'id' => 'img-white-card'
                    ]) ?>

                    <div class="pt-5">
                        <?= Html::if($whitecardFile, function() use($whitecardFile) {
                            return Html::a('Download', $whitecardFile->downloadUrl, [
                                'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
                                'id' => 'download-whitecard'
                            ]);
                        }) ?>

                        <?= Html::if($whitecardFile, function() use($whitecardFile) {
                            return Html::a('View', $whitecardFile->viewerUrl, [
                                'class' => 'btn btn-light-primary font-weight-bolder btn-sm',
                                'id' => 'view-whitecard',
                                'target' => '_blank'
                            ]);
                        }) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $this->endContent(); ?>


<div class="modal fade" id="modal-create-white-card" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-create-white-cardLabel">White Card Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'transaction-form',
                    'action' => ['transaction/create-white-card', 'transaction_id' => $model->id]
                ]); ?>
                    <?= TinyMce::widget([
                        'model' => $whitecard,
                        'attribute' => 'white_card',
                        'toolbar' => 'advlist | autolink | link image | lists charmap | table tabledelete | pagebreak',
                        'plugins' =>  'advlist autolink link image lists charmap preview table pagebreak',
                    ]) ?>

                    <div class="mt-10 text-right">
                        <?= Html::submitButton('Save White Card', [
                            'class' => 'btn btn-success'
                        ]) ?>
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close</button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>