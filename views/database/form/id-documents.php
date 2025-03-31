<?php

use app\helpers\Html;
use app\models\File;
use app\widgets\Dropzone;
use app\widgets\Webcam;

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
                    url: app.baseUrl + 'file/delete?token=' + $(self).data('token'),
                    method: 'post',
                    dataType: 'json',
                    success: function(s) {
                        let tableId = $(self).closest('table').attr('id');

                        if(s.status == 'success') {
                            $('#' + tableId).DataTable({
                                destroy: true,
                                pageLength: 5,
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

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
    <section>
    	<div class="row">
    		<div class="col-md-12">
    			<h3 class="card-label">Identification Cards</h3>
    			<hr/>
    		</div>
    	</div>
    	<div class="row">
            <div class="col-md-4">
                <div class="id-container-holder"></div>
                <?= Webcam::widget([
                    'tag' => 'Database',
                    'withInput' => false,
                    'model' => $model,
                    'attribute' => 'id_cards[]',
                    'ajaxSuccess' => <<< JS
                        $('#id-cards-table').DataTable({
                            destroy: true,
                            pageLength: 5,
                            order: [[0, 'desc']]
                        }).row.add($(s.row)).draw();
                        $('.id-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="Database[id_cards][]" value="'+ s.file.token +'">'); 
                    JS
                ]) ?>

                <div class="mt-5"></div>
                <?= Dropzone::widget([
                    'tag' => 'Database',
                    'title' => 'Drop Identification Cards here.',
                    'model' => $model,
                    'attribute' => 'id_cards',
                    'inputName' => 'hidden',
                    'success' => <<< JS
                        this.removeFile(file);
                        $('#id-cards-table').DataTable({
                            destroy: true,
                            pageLength: 5,
                            order: [[0, 'desc']]
                        }).row.add($(s.row)).draw();
                        $('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="Database[id_cards][]" value="'+ s.file.token +'">'); 
                    JS,
                    'acceptedFiles' => array_map(
                        function($val) { 
                            return ".{$val}"; 
                        }, File::EXTENSIONS['image']
                    )
                ]) ?>
            </div>
            <div class="col-md-8">
                <?php $this->beginContent('@app/views/file/_row-header.php', [
                    'tableId' => 'id-cards-table'
                ]); ?>
                    <?= Html::foreach($model->identificationCards, function($file) {
                        return $this->render('/file/_row', [
                            'model' => $file
                        ]) . Html::input('text', 'Database[id_cards][]', $file->token,
                            ['class' => "app-hidden file-hidden-input-{$file->token}"]
                        );
                    }) ?>
                <?php $this->endContent(); ?>
            </div>
        </div>
    </section>
<?php $this->endContent() ?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php') ?>
    <section>
        <div class="row">
            <div class="col-md-12">
                <h3 class="card-label">Other Documents</h3>
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="document-container-holder"></div>
                <?= Webcam::widget([
                    'tag' => 'Database',
                    'withInput' => false,
                    'model' => $model,
                    'attribute' => 'documents[]',
                    'ajaxSuccess' => <<< JS
                        $('#table-file').DataTable({
                            destroy: true,
                            pageLength: 5,
                            order: [[0, 'desc']]
                        }).row.add($(s.row)).draw();
                        $('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="Database[documents][]" value="'+ s.file.token +'">'); 
                    JS
                ]) ?>

                <div class="mt-5"></div>
               <?= Dropzone::widget([
                    'tag' => 'Database',
                    'title' => 'Drop Other Documents here.',
                    'model' => $model,
                    'attribute' => 'documents',
                    'inputName' => 'hidden',
                    'success' => <<< JS
                        this.removeFile(file);
                        $('#table-file').DataTable({
                            destroy: true,
                            pageLength: 5,
                            order: [[0, 'desc']]
                        }).row.add($(s.row)).draw();
                        $('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="Database[documents][]" value="'+ s.file.token +'">'); 
                    JS,
                ]) ?>
            </div>
            <div class="col-md-8">
                <?php $this->beginContent('@app/views/file/_row-header.php'); ?>
                    <?= Html::foreach($model->imageFiles, function($file) {
                        return $this->render('/file/_row', [
                            'model' => $file
                        ]) . Html::input('text', 'Database[documents][]', $file->token,
                            ['class' => "app-hidden file-hidden-input-{$file->token}"]
                        );
                    }) ?>
                <?php $this->endContent(); ?>
            </div>
        </div> 
    </section>
<?php $this->endContent() ?>

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