<?php

use app\helpers\App;
use app\helpers\Url;
use app\models\File;
use app\helpers\Html;
use app\widgets\Value;
use app\widgets\Webcam;
use app\widgets\Dropzone;
use app\widgets\ActiveForm;
use yii\widgets\DetailView;
use app\widgets\MemberDetail;
use app\models\search\TransactionSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = "Create Transaction: {$member->name}";
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => 'Update Profile', 'url' => ['transaction/update-profile', 'qr_id' => $member->qr_id, 'transaction_type' => $type]];
$this->params['breadcrumbs'][] = 'Create';
$this->params['breadcrumbs'][] = 'Senior Citizen ID Application';
$this->params['searchModel'] = new TransactionSearch();
$this->params['wrapCard'] = false;


$this->registerCss(<<< CSS
    .webcam-input-group {
        max-width: 400px;
        margin: 0 auto;
    }
    .eligibility-notice {
        background: #fff;
        padding: 10px;
    }
    .eligibility-notice .mb-9 {
        margin-bottom: 0px !important;
    }
    section.section {
        border: 1px dashed #ccc;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
    }
    section table th {
        width: 50%;
    }
CSS);

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

<?php $form = ActiveForm::begin(['id' => 'transaction-form']); ?>
    
    <?= Html::activeInput('text', $model, 'member_id', ['class' => 'app-hidden']) ?>
    <div class="row">
        <div class="col-md-6">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Member Profile',
                'toolbar' => <<< HTML
                    <div class="card-toolbar">
                        <a href="{$member->viewUrl}" class="btn btn-light-primary font-weight-bolder" target="_blank">
                            View Full Profile
                        </a>
                    </div>
                HTML
            ]); ?>

                <?= MemberDetail::widget([
                    'model' => $member,
                    'template' => 'simple'
                ]) ?> 
                     
            <?php $this->endContent(); ?> 
        </div>
        <div class="col-md-6">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Requirements'
            ]); ?>
                <section>
                    <?= Value::widget([
                        'label' => '1) Birth Certificate, LCR/PSA Copy (1 Photocopy)',
                        'content' => <<< HTML
                            <em>Where to secure: </em> Local Civil Registrar/Philippine Statistics Authority
                            <p>
                                If the applicant has no Birth Certificate, he/she must
                                secure 2 valid ID’s such as: Voter’s ID (COMELEC),
                                Driver’s License, NBI Clearance, Police Clearance,
                                Passport, Postal ID as a proof of correctness of name,
                                date of birth and address.
                            </p>
                        HTML
                    ]) ?>
                    <div class="my-3"></div>
                    <?= Value::widget([
                        'label' => '2) Membership Application form',
                        'content' => <<< HTML
                            <em>Where to secure: </em>
                            Office of the Senior Citizen’s Affairs (OSCA)
                        HTML
                    ]) ?>
                    <div class="my-3"></div>
                    <?= Value::widget([
                        'label' => '3) (2) copies of recent 1x1 picture',
                        'content' => <<< HTML
                            <em>Where to secure: </em>
                            Senior Citizen Applicant
                        HTML
                    ]) ?>
                </section>

                <hr>
                <section class="mt-10">
                    <p class="lead font-weight-bold">Upload Documents</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="document-container-holder"></div>
                            <?= Webcam::widget([
                                'tag' => 'Transaction',
                                'withInput' => false,
                                'model' => $model,
                                'attribute' => 'files[]',
                                'ajaxSuccess' => <<< JS
                                    $('#table-file').DataTable({
                                        destroy: true,
                                        pageLength: 5,
                                        order: [[0, 'desc']]
                                    }).row.add($(s.row)).draw();
                                    $('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="SeniorCitizenIdForm[files][]" value="'+ s.file.token +'">'); 
                                JS
                            ]) ?>
                        </div>
                    </div>
                    <div class="row mt-10">
                        <div class="col-md-12">
                            <?= Dropzone::widget([
                                'tag' => 'Transaction',
                                'model' => $model,
                                'attribute' => 'files',
                                'inputName' => 'hidden',
                                'success' => <<< JS
                                    this.removeFile(file);
                                    $('#table-file').DataTable({
                                        destroy: true,
                                        pageLength: 5,
                                        order: [[0, 'desc']]
                                    }).row.add($(s.row)).draw();

                                    $('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="SeniorCitizenIdForm[files][]" value="'+ s.file.token +'">'); 
                                JS,
                                'acceptedFiles' => array_map(
                                    function($val) { 
                                        return ".{$val}"; 
                                    }, File::EXTENSIONS['image']
                                )
                            ]) ?>
                        </div>
                    </div>
                    <div class="row mt-10">
                        <div class="col-md-12">
                            <?php $this->beginContent('@app/views/file/_row-header.php'); ?>
                            <?php $this->endContent(); ?>
                        </div>
                    </div>
                </section>
            <?php $this->endContent(); ?> 
        </div>
    </div>
    <div class="mt-5">
        <?= ActiveForm::buttons('lg') ?>
    </div>
<?php ActiveForm::end(); ?>

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