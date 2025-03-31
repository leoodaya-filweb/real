<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\CivilStatus;
use app\models\EducationalAttainment;
use app\models\PwdType;
use app\models\Relation;
use app\models\Sex;
use app\widgets\BootstrapSelect;
use app\widgets\Dropzone;
use app\widgets\ImageGallery;
use app\widgets\Webcam;

$this->registerCss(<<< CSS
    .hide {display: none;}
    .show {display: block;}
    .image-gallery-container .button-container {
        text-align: center;
    }
CSS);

$this->registerJs(<<< JS
    function pensioner(el) {
        $(el).on('change', function() {
            var value = $(this).val();

            if(value == 1) {
                $('.pensioner_from_container').show();
                $('.pension_amount_container').show();
            }
            else {
                $('.pensioner_from_container').hide();
                $('.pension_amount_container').hide();
            }
        });
    }
    pensioner('#member-pensioner');

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
                        if(s.status == 'success') {
                            $('#table-file').DataTable({
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
<div class="member-fields-container">
    <div class="text-center">
        <?= Html::image($model->photo, ['w' => 200], [
            'class' => 'user-photo img-thumbnail mw-200'
        ]) ?>
    </div>

    <div class="mt-5">
        <div class="image-gallery-container">
            <?= ImageGallery::widget([
                'tag' => 'Member',
                'buttonTitle' => 'Choose Profile Photo',
                'model' => $model,
                'attribute' => 'photo',
                'ajaxSuccess' => "
                    if(s.status == 'success') {
                        $('.user-photo').attr('src', s.src);
                    }
                ",
            ]) ?> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= Html::if(! $model->isHead, function() use($model, $form) {
                $input = BootstrapSelect::widget([
                    'form' => $form,
                    'model' => $model,
                    'attribute' => 'relation',
                    'label' => 'Relation',
                    'data' => Relation::dropdown(),
                ]);
                return <<< HTML
                    <div data-trigger="hover" data-toggle="popover" title="Family Head Relation" data-html="true" data-content="The relation of this member to <span class='label label-inline font-weight-bold label-light-primary'>family head</span>. <code>eg: Son, Daughter</code>">
                        {$input}
                    </div>
                HTML;
            }) ?>
            
        </div>
    </div>
    
    <div class="row">
        <div class="col">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col"> 
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col"> 
            <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?= BootstrapSelect::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'sex',
                'data' => Sex::dropdown(),
            ]) ?>
        </div>
        <div class="col">
            <?= BootstrapSelect::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'civil_status',
                'data' => CivilStatus::dropdown(),
            ]) ?>
        </div>
        <div class="col">
            <?= BootstrapSelect::widget([
                'form' => $form,
                'model' => $model,
                'attribute' => 'educational_attainment',
                'data' => EducationalAttainment::dropdown(),
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'birth_date')->textInput([
                'datepicker' => 'true',
                'autocomplete' => 'off'
            ]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'birth_place')->textInput(['maxlength' => true]) ?>
        </div>
        
    </div>

    <section class="mt-5">
        <p class="lead font-weight-bold">Contact</p>
        <div class="row">
            <div class="col">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'contact_no')
                    ->textInput(['maxlength' => true])
                    ->label('Mobile Number') ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'telephone_no')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </section>

    <section class="mt-5">
        <p class="lead font-weight-bold">Occupation</p>
        <div class="row">
            <div class="col">
                <?= $form->field($model, 'occupation')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'income')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'source_of_income')->textInput([
                    'maxlength' => true,
                    'list' => 'source_of_incomes'
                ]) ?>
                <datalist id="source_of_incomes">
                    <?= Html::foreach(App::keyMapParams('source_of_incomes'), function($s) {
                        return "<option value='{$s}'>";
                    }) ?>
                </datalist>
            </div>
        </div>
    </section>



    <section class="mt-5">
        <p class="lead font-weight-bold">Pension</p>
        <div class="row">
            <div class="col-md-4">
                <?= BootstrapSelect::widget([
                    'form' => $form,
                    'model' => $model,
                    'prompt' => false,
                    'attribute' => 'pensioner',
                    'data' => App::keyMapParams('pensioners'),
                ]) ?>
            </div>
            <div class="col pensioner_from_container <?= ($model->isPensioner)? 'show': 'hide' ?>">
                <?= $form->field($model, 'pensioner_from')->textInput([
                    'maxlength' => true,
                    'list' => 'pensioner_from'
                ]) ?>
                <datalist id="pensioner_from">
                    <?= Html::foreach(App::keyMapParams('pensioner_from'), function($s) {
                        return "<option value='{$s}'>";
                    }) ?>
                </datalist>
            </div>
            <div class="col pension_amount_container <?= ($model->isPensioner)? 'show': 'hide' ?>">
                <?= $form->field($model, 'pension_amount')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </section>


     <section class="mt-5">
        <p class="lead font-weight-bold">PWD / Solo Parent</p>
        <div class="row">
            <div class="col-md-4">
                <?= BootstrapSelect::widget([
                    'form' => $form,
                    'model' => $model,
                    'prompt' => false,
                    'attribute' => 'solo_parent',
                    'data' => App::keyMapParams('solo_parent'),
                    'prompt' => 'Select'
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
               <?= BootstrapSelect::widget([
                    'form' => $form,
                    'model' => $model,
                    'prompt' => false,
                    'attribute' => 'pwd',
                    'data' => App::keyMapParams('pwd'),
                    'prompt' => 'Select'
                ]) ?>
            </div>
            <div class="col-md-4">
                <?= BootstrapSelect::widget([
                    'form' => $form,
                    'model' => $model,
                    'prompt' => false,
                    'attribute' => 'pwd_type',
                    'data' => PwdType::dropdown('value', 'label'),
                    'prompt' => 'Select'
                ]) ?>
            </div>
        </div>
    </section>

    <section class="mt-5">
        <p class="lead font-weight-bold">Documents</p>
        <div class="row">
            <div class="col-md-4">
                <div class="document-container-holder"></div>
                <?= Webcam::widget([
                    'tag' => 'Member',
                    'withInput' => false,
                    'model' => $model,
                    'attribute' => 'documents[]',
                    'ajaxSuccess' => <<< JS
                        $('#table-file').DataTable({
                            destroy: true,
                            pageLength: 5,
                            order: [[0, 'desc']]
                        }).row.add($(s.row)).draw();
                        $('.document-container-holder').prepend('<input class="file-hidden-input-'+ s.file.token +'" type="text" name="Member[documents][]" value="'+ s.file.token +'">'); 
                    JS
                ]) ?>
                <hr>

               <?= Dropzone::widget([
                    'tag' => 'Member',
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
                        $('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="Member[documents][]" value="'+ s.file.token +'">'); 
                    JS,
                ]) ?>
            </div>
            <div class="col">
                <?php $this->beginContent('@app/views/file/_row-header.php'); ?>
                    <?= Html::foreach($model->imageFiles, function($file) {
                        return $this->render('/file/_row', [
                            'model' => $file
                        ]) . Html::input('text', 'Member[documents][]', $file->token,
                            ['class' => "app-hidden file-hidden-input-{$file->token}"]
                        );
                    }) ?>
                <?php $this->endContent(); ?>
            </div>
        </div> 
    </section>
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