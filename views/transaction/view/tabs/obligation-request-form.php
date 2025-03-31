<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\form\transaction\ObligationRequestForm;
use app\widgets\ActiveForm;
use app\widgets\TinyMce;

$obligation_request = new ObligationRequestForm(['transaction_id' => $model->id]);
$createDocument = App::get('create_document');
$createDocument  = ($model->obligation_request)? 'false': $createDocument;

$this->registerJs(<<< JS
    $('.btn-create-obligation-request').click(function() {
        $('#modal-create-obligation-request').modal('show');
    });

    $('.btn-update-obligation-request').click(function() {
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
                var editor = tinymce.get('obligationrequestform-obligation_request'); 
                editor.setContent(`{$model->obligation_request}`);
                $('#modal-create-obligation-request').modal('show');
                KTApp.unblockPage();
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblockPage();
            }    
        });
    });

    if('{$createDocument}' == 'orf') {
        $('.btn-create-obligation-request').click();
    }
JS);
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => "Obligation Request Form {$model->obligationRequestFormActionBtn}",
    'toolbar' => <<< HTML
        <div class="card-toolbar">
            {$model->orfButton}
        </div>
    HTML
]); ?>
    <?= Html::ifElse($model->obligation_request, function() use($model) {
        return Tinymce::widget([
            'content' => $model->obligation_request,
            'menubar' => false,
            'toolbar' => 'print',
            // 'plugins' =>  'print pagebreak',
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
    }, Html::tag('h5', 'No Obligation Request yet')) ?>
    <div class="mt-5">
        <?= $model->obligationRequestFormActionBtn ?>
    </div>
<?php $this->endContent(); ?>


<div class="modal fade" id="modal-create-obligation-request" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-create-obligation-requestLabel">Obligation Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'transaction-form',
                    'action' => ['transaction/create-obligation-request', 'transaction_id' => $model->id]
                ]); ?>
                    <?= TinyMce::widget([
                        'model' => $obligation_request,
                        'attribute' => 'obligation_request',
                        'toolbar' => 'advlist | autolink | link image | lists charmap | table tabledelete | pagebreak',
                        'plugins' =>  'advlist autolink link image lists charmap preview table pagebreak',
                    ]) ?>

                    <div class="mt-10 text-right">
                        <?= Html::submitButton('Save Obligation Request', [
                            'class' => 'btn btn-success'
                        ]) ?>
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close</button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>