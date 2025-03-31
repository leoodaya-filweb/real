<?php

use app\helpers\App;
use app\helpers\Html;
use app\widgets\ActiveForm;
use app\widgets\DatePicker;
use app\widgets\Dropzone;
use app\widgets\InputList;

$this->registerJs(<<< JS
    $(document).on('click', '.btn-remove-file', function() {
        var self = this;
        Swal.fire({
            title: "Are you sure?",
            text: "You won\"t be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then(function(result) {
            if (result.value) {

                KTApp.block('body', {
                    overlayColor: '#000000',
                    state: 'warning',
                    message: 'Please wait...'
                });
                $.ajax({
                    url: $(self).data('delete-url'),
                    method: 'post',
                    dataType: 'json',
                    success: function(s) {
                        if(s.status == 'success') {
                            $('#table-file').DataTable({
                                destroy: true,
                                pageLength: 3,
                                order: [[0, 'desc']]
                            }).row($(self).closest('tr')).remove().draw();
                            $(document).find('.file-hidden-input-' + $(self).data('token')).remove();
                            Swal.fire({
                                icon: "success",
                                title: "Deleted",
                                text: "Your file has been deleted.",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        else {
                            Swal.fire('Error', s.errors, 'error');
                        }
                        KTApp.unblock('body');
                    },
                    error: function(e) {
                        Swal.fire('Error', e.responseText, 'error');
                        KTApp.unblock('body');
                    },
                })
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
?>

<div class="tab-pane fade show active">
    <?php $form = ActiveForm::begin([
        'id' => 'interview-form', 
        'action' => ['scholarship/save-interview', 'token' => $model->token]
    ]); ?>
        <div class="row">
            <div class="col-md-6">
                <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                    'title' => 'Interview Details',
                    'stretch' => true
                ]) ?>
                    <?= DatePicker::widget([
                        'form' => $form,
                        'model' => $model,
                        'attribute' => 'interview_date',
                    ]) ?>
                    <?= $form->field($model, 'interviewer')->textInput(['maxlength' => true]) ?>

                    
                    <label>Interview Notes</label>
                    <?= InputList::widget([
                        'data' => $model->notes,
                        'name' => 'Scholarship[notes][]',
                        'label' => 'Note',
                    ]) ?>

                    <div class="text-center mt-20">
                        <?= Html::submitButton('Save Interview Details', [
                            'class' => 'btn btn-success font-weight-bold btn-lg'
                        ]) ?>
                    </div>
                <?php $this->endContent() ?>
            </div>
            <div class="col-md-6">
                <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                    'title' => 'Interview Attachments',
                    'stretch' => true
                ]) ?>
                    <?= Dropzone::widget([
                        'tag' => 'Interview Attachments',
                        'model' => $model,
                        'attribute' => 'interview_attachments',
                        'inputName' => 'hidden',
                        'success' => <<< JS
                            this.removeFile(file);
                            $('#table-file').DataTable({
                                destroy: true,
                                pageLength: 3,
                                order: [[0, 'desc']]
                            }).row.add($(s.row)).draw();

                            $.ajax({
                                url: app.baseUrl + 'scholarship/add-interview-attachment',
                                data: {
                                    document: s.file.token,
                                    token: '{$model->token}'
                                },
                                method: 'post',
                                dataType: 'json',
                                success: function(s) {
                                    if (s.status == 'success') {
                                        toastr.success(s.message);
                                    }
                                    else {
                                        toastr.error(s.errorSummary);
                                    }
                                },
                                error: function(e) {
                                    Swal.fire('Error', e.responseText, 'error');
                                }
                            })
                        JS,
                    ]) ?>
                    <div class="my-5"></div>
                    <?php $this->beginContent('@app/views/file/_row-header.php', [
                        'pageLength' => 3
                    ]) ?>
                        <?= App::foreach(
                            $model->interviewAttachmentFiles, 
                            fn($file) => $this->render('/file/_row', [
                                'model' => $file
                            ])
                        ) ?>
                    <?php $this->endContent() ?>
                <?php $this->endContent() ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="modal fade" id="modal-edit-document" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-edit-document">Rename Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>