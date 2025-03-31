<?php
use Yii;
use app\helpers\Html;
use app\models\search\TransactionSearch;
use app\widgets\ActiveForm;
use app\widgets\Dropzone;
use app\widgets\Grid;
use app\widgets\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
if(Yii::$app->request->get('dup')){
   $duptitle='Duplicate and '; 
}

$this->title = $duptitle."Create Transaction: {$member->name}";
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = ['label' => 'Update Profile', 'url' => ['transaction/update-profile', 'qr_id' => $member->qr_id, 'transaction_type' => $type]];
$this->params['breadcrumbs'][] = 'Create';
$this->params['breadcrumbs'][] = 'Social Case Study Report';
$this->params['searchModel'] = new TransactionSearch();
$this->params['wrapCard'] = false;


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
                $('#modal-transaction-detail .modal-title').html('Social Case Study Report: ' + s.model.fulldate);
                $('#modal-transaction-detail').modal('show');
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
                        title: "Social case study report saved!",
                        showConfirmButton: false,
                        timer: 1000
                    });
                    window.location.href = s.transaction.viewUrl;

                    // $('.social-case-study-report-grid .card-body').html(s.grid);

                    // var editor = tinymce.get('tinymce-textarea-id'); 
                    // editor.setContent(s.model.content);
                    // printBtn();
                    // $('#modal-transaction-detail .modal-title').html('Social Case Study Report');
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
<?php $form = ActiveForm::begin(['id' => 'transaction-form']); ?>
    <div class="transaction-create-page container">
        <div class="row">
            <div class="col-md-12">
                <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                    'title' => 'Social Case Study Report Form',
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

                            <?= TinyMce::widget([
                                'model' => $model,
                                'attribute' => 'content',
                                'height' => '500mm',
                                'toolbar' => 'advlist | autolink | link image | lists charmap | table tabledelete | pagebreak',
                                'plugins' =>  'advlist autolink link image lists charmap preview table pagebreak',
                            ]) ?>

                            <?= Html::submitButton('Save', [
                                'class' => 'btn btn-success font-weight-bolder btn-lg mt-7'
                            ]) ?>
                        </div>
                        <div class="tab-pane fade pt-5" id="tab-member-profile" role="tabpanel" aria-labelledby="tab-member-profile">
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
                    'class' => 'social-case-study-report-grid'
                ]); ?>
                    <?= $model->grid() ?>
                <?php $this->endContent(); ?>
            </div>
        </div>

    </div>
<?php ActiveForm::end(); ?>

