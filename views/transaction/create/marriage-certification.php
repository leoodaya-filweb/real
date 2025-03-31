<?php

use app\helpers\Html;
use app\models\search\TransactionSearch;
use app\widgets\ActiveForm;
use app\widgets\Reminder;
use app\widgets\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = "Create Transaction: {$member->name}";
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => 'Update Profile', 'url' => ['transaction/update-profile', 'qr_id' => $member->qr_id, 'transaction_type' => $type]];
$this->params['breadcrumbs'][] = 'Create';
$this->params['breadcrumbs'][] = 'Certification';
$this->params['searchModel'] = new TransactionSearch();
$this->params['wrapCard'] = false;

$this->registerCss(<<< CSS
    label.option:hover {
        cursor: pointer;
        border: 1px solid #ccc;
        box-shadow: rgb(0 0 0 / 30%) 0px 1px 2px 0 !important;
    }
CSS);

$this->registerJs(<<< JS
    let printBtn = function() {
        $(".tox-toolbar-overlord").removeClass('tox-tbtn--disabled');
        $(".tox-toolbar-overlord").attr( 'aria-disabled', 'false' );

        // And activate ALL BUTTONS styles
        $(".tox-toolbar__group button").removeClass('tox-tbtn--disabled');
        $(".tox-toolbar__group button").attr( 'aria-disabled', 'false' );
    }
    $(document).on('click', '.btn-view-transaction', function() {
        printBtn();

        KTApp.blockPage({
            overlayColor: '#000000',
            state: 'warning', // a bootstrap color
            message: 'Please wait...'
        });
        let token = $(this).data('token');

        $.ajax({
            url: app.baseUrl + 'transaction/view',
            data: {token: token},
            method: 'get',
            dataType: 'json',
            success: function(s) {
                var editor = tinymce.get('tinymce-textarea-id'); 
                editor.setContent(s.model.content);
                $('#modal-transaction-detail .modal-title').html(s.model.transactionTypeName + ': ' + s.model.fulldate);
                $('#modal-transaction-detail').modal('show');
                KTApp.unblockPage();
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblockPage();
            }
        });
    });

    $('input[name="MarriageCertificationForm[transaction_type]"]').click(function() {
        KTApp.blockPage({
            overlayColor: '#000000',
            state: 'warning', // a bootstrap color
            message: 'Please wait...'
        });
        let transaction_type = $(this).val();

        $.ajax({
            url: app.baseUrl + 'transaction/transaction-type',
            data: {
                member_id: {$model->member_id},
                transaction_type: transaction_type
            },
            method: 'get',
            dataType: 'json',
            success: function(s) {
                var editor = tinymce.get('marriagecertificationform-content'); 
                editor.setContent(s.model.content);
                KTApp.unblockPage();
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblockPage();
            }
        });
    });

    $('#transaction-form').on('beforeSubmit', function(e) {
        KTApp.blockPage({
            overlayColor: '#000000',
            state: 'warning', // a bootstrap color
            message: 'Please wait...'
        });

        e.preventDefault();
        let form = $(this);

        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            method: 'post',
            dataType: 'json',
            success: function(s) {
                if(s.status == 'success') {
                    Swal.fire({
                        icon: "success",
                        title: "Certification saved!",
                        showConfirmButton: false,
                        timer: 1000
                    });
                    window.location.href = s.transaction.viewUrl;
                    // $('.certification-grid .card-body').html(s.grid);
                    // var editor = tinymce.get('tinymce-textarea-id'); 
                    // editor.setContent(s.model.content);
                    // printBtn();
                    // $('#modal-transaction-detail .modal-title').html('Certification');
                    // $('#modal-transaction-detail').modal('show');
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
        })

        return false;
    });
JS);
?>

<div class="container">
<?php $form = ActiveForm::begin(['id' => 'transaction-form']); ?>
    <div class="transaction-create-page">
        
        <div class="row">
            <div class="col-md-12">
                <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                    'title' => 'Certification Form',
                ]); ?>

                    <ul class="nav nav-tabs nav-bold nav-tabs-line" style="margin-top: -20px;">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-form">
                                <span class="nav-icon">
                                    <i class="flaticon2-chat-1"></i>
                                </span>
                                <span class="nav-text">Form</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-member-profile">
                                <span class="nav-icon">
                                    <i class="flaticon2-drop"></i>
                                </span>
                                <span class="nav-text">Member Profile</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active pt-5" id="tab-form" role="tabpanel" aria-labelledby="tab-form">
                            <div class="row mb-5">
                                <div class="col">
                                    <div class="form-group m-0">
                                        <label>Choose Certificate Type:</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="option">
                                                    <span class="option-control">
                                                        <span class="radio">
                                                            <input type="radio" name="MarriageCertificationForm[transaction_type]" value="<?= $model->comc['id'] ?>" <?= ($model->comc['id'] == $model->transaction_type)? 'checked': '' ?>>
                                                            <span></span>
                                                        </span>
                                                    </span>
                                                    <span class="option-label">
                                                        <span class="option-head">
                                                            <span class="option-title">
                                                                <?= $model->comc['label'] ?>
                                                            </span>
                                                        </span>
                                                        <span class="option-body">
                                                            <?= $model->comc['objective'] ?>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="option">
                                                    <span class="option-control">
                                                        <span class="radio">
                                                            <input type="radio" name="MarriageCertificationForm[transaction_type]" value="<?= $model->coc['id'] ?>" <?= ($model->coc['id'] == $model->transaction_type)? 'checked': '' ?>>
                                                            <span></span>
                                                        </span>
                                                    </span>
                                                    <span class="option-label">
                                                        <span class="option-head">
                                                            <span class="option-title">
                                                                <?= $model->coc['label'] ?>
                                                            </span>
                                                        </span>
                                                        <span class="option-body">
                                                            <?= $model->coc['objective'] ?>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?= TinyMce::widget([
                                'model' => $model,
                                'attribute' => 'content',
                                'size' => 'A4',
                                'height' => '400mm',
                                'toolbar' => 'advlist | autolink | link image | lists charmap | table tabledelete | pagebreak',
                                'plugins' =>  'advlist autolink link image lists charmap preview table pagebreak',
                            ]) ?>

                            <?= Html::submitButton('Save', [
                                'class' => 'btn btn-success font-weight-bolder btn-lg mt-7'
                            ]) ?>
                        </div>
                        <div class="tab-pane fade pt-5" id="tab-member-profile" role="tabpanel" aria-labelledby="tab-member-profile">
                            <?= Html::a('Update Profile', $member->updateUrl, [
                                'class' => 'btn btn-outline-primary font-weight-bolder mb-5'
                            ]) ?>
                            <?= $member->getDetailView(false) ?>
                        </div>
                    </div>
                    
                <?php $this->endContent(); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                    'title' => 'History',
                    'class' => 'certification-grid'
                ]); ?>
                    <?= $model->grid() ?>
                <?php $this->endContent(); ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>

</div>

