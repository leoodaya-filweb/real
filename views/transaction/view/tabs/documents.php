<?php

use app\helpers\Html;
use app\models\File;
use app\widgets\Dropzone;
use app\widgets\Webcam;


$this->registerJs(<<< JS
    $(document).on('click', '.btn-remove-file', function() {
        let self = this,
            token = $(this).data('token');

        Swal.fire({
            title: "Are you sure?",
            text: "You won\"t be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then(function(result) {
            if(result.isConfirmed) {
                
                KTApp.blockPage({
                    overlayColor: '#000000',
                    state: 'warning', // a bootstrap color
                    message: 'Please wait...'
                });

                $.ajax({
                    url: app.baseUrl + 'transaction/remove-document',
                    data: {
                        id: {$model->id},
                        token: token
                    },
                    method: 'post',
                    dataType: 'json',
                    success: function(s) {
                        if(s.status == 'success') {
                            Swal.fire({
                                icon: "success",
                                title: s.message,
                                showConfirmButton: false,
                                timer: 1000
                            });
                            $('#table-file').DataTable({
                                destroy: true,
                                pageLength: 5,
                                order: [[0, 'desc']]
                            }).row($(self).closest('tr')).remove().draw();
                        }
                        else {
                            Swal.fire('Error', s.error, 'error');
                        }
                        KTApp.unblockPage();
                    },
                    error: function(e) {
                        Swal.fire('Error', e.responseText, 'error');
                        KTApp.unblockPage();
                    }
                });
            }
        });
    });

    $(document).on('click', '.btn-edit-file', function() {
        let file = $(this);

        KTApp.block('.files-container', {
            state: 'warning', // a bootstrap color
            message: 'Please wait...',
        });

        $.ajax({
            url: app.baseUrl + 'file/view',
            method: 'get',
            data: {
                token: file.data('token'),
                template: '_form-ajax',
            },
            dataType: 'json',
            success: function(s) {
                if(s.status == 'success') {
                    $('#modal-edit-document .modal-body').html(s.form);
                    $('#modal-edit-document').modal('show');
                }
                else {
                    Swal.fire('Error', s.error, 'error');
                }
                KTApp.unblock('.files-container');
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('.files-container');
            }
        });
    });
JS);


$this->registerCss(<<< CSS
    .webcam-input-group {
        max-width: 400px;
        margin: 0 auto;
    }
CSS);
?>

<div class="document-container">
    <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
        'title' => 'Documents',
    ]); ?>
        <div class="row">
            <div class="col-md-5">
                <?= Webcam::widget([
                    'tag' => 'Transaction',
                    'withInput' => false,
                    'model' => $model,
                    'attribute' => 'files[]',
                    'ajaxSuccess' => <<< JS
                        KTApp.block('body', {
                            overlayColor: '#000',
                            state: 'warning',
                            message: 'Please wait...'
                        })
                        $.ajax({
                            url: app.baseUrl + 'transaction/add-document',
                            data: {
                                id: {$model->id},
                                token: s.file.token
                            },
                            method: 'post',
                            dataType: 'json',
                            success: function(s) {
                                if(s.status == 'success') {
                                    Swal.fire({
                                        icon: "success",
                                        title: s.message,
                                        showConfirmButton: false,
                                        timer: 1000
                                    });
                                    $('#table-file').DataTable({
                                        destroy: true,
                                        pageLength: 5,
                                        order: [[0, 'desc']]
                                    }).row.add($(s.row)).draw();
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
                ]) ?>
               
                <hr>
                <?= Dropzone::widget([
                    'tag' => 'Transaction',
                    'model' => $model,
                    'attribute' => 'files',
                    'inputName' => 'hidden',
                    'success' => <<< JS
                        var self = this;
                        KTApp.block('body', {
                            overlayColor: '#000',
                            state: 'warning',
                            message: 'Please wait...'
                        })
                        $.ajax({
                            url: app.baseUrl + 'transaction/add-document',
                            data: {
                                id: {$model->id},
                                token: s.file.token
                            },
                            method: 'post',
                            dataType: 'json',
                            success: function(s) {
                                if(s.status == 'success') {
                                    Swal.fire({
                                        icon: "success",
                                        title: s.message,
                                        showConfirmButton: false,
                                        timer: 1000
                                    });
                                    self.removeFile(file);
                                    $('#table-file').DataTable({
                                        destroy: true,
                                        pageLength: 5,
                                        order: [[0, 'desc']]
                                    }).row.add($(s.row)).draw();
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
                    JS,
                    'maxFiles' => 10,
                    'acceptedFiles' => array_map(
                        function($val) { 
                            return ".{$val}"; 
                        }, File::EXTENSIONS['file']
                    )
                ]) ?>
            </div>

            <div class="col-md-7">
                <?php $this->beginContent('@app/views/file/_row-header.php'); ?>
                    <?= Html::foreach($model->imageFiles, function($file) {
                        return $this->render('/file/_row', [
                            'model' => $file
                        ]);
                    }) ?>
                <?php $this->endContent(); ?>
            </div>
        </div>
    <?php $this->endContent(); ?>
</div>

<div class="modal fade" id="modal-edit-document" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-edit-document">Rename File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>