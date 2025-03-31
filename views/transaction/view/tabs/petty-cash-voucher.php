<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\form\transaction\PettyCashVoucherForm;
use app\widgets\ActiveForm;
use app\widgets\TinyMce;

$petty_cash_voucher = new PettyCashVoucherForm(['transaction_id' => $model->id]);
$createDocument = App::get('create_document');
$createDocument  = ($model->petty_cash_voucher)? 'false': $createDocument;

$this->registerJs(<<< JS
    $('.btn-create-petty-cash-voucher').click(function() {
        $('#modal-create-petty-cash-voucher').modal('show');
    });

    $('.btn-update-petty-cash-voucher').click(function() {
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
                var editor = tinymce.get('pettycashvoucherform-petty_cash_voucher'); 
                editor.setContent(`{$model->petty_cash_voucher}`);
                $('#modal-create-petty-cash-voucher').modal('show');
                KTApp.unblockPage();
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblockPage();
            }    
        });
    });

    if('{$createDocument}' == 'pcv') {
        $('.btn-create-petty-cash-voucher').click();
    }
JS);
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => "Petty Cash Voucher {$model->pettyCashVoucherActionBtn}",
    'toolbar' => <<< HTML
        <div class="card-toolbar">
            {$model->pcvButton}
        </div>
    HTML
]); ?>
    <?= Html::ifElse($model->petty_cash_voucher, function() use($model) {
        return Tinymce::widget([
            'content' => $model->petty_cash_voucher,
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
    }, Html::tag('h5', 'No Petty Cash Voucher yet')) ?>
    <div class="mt-5">
        <?= $model->pettyCashVoucherActionBtn ?>
    </div>
<?php $this->endContent(); ?>


<div class="modal fade" id="modal-create-petty-cash-voucher" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-create-petty-cash-voucherLabel">Petty Cash Voucher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'transaction-form',
                    'action' => ['transaction/create-petty-cash-voucher', 'transaction_id' => $model->id]
                ]); ?>
                    <?= TinyMce::widget([
                        'model' => $petty_cash_voucher,
                        'attribute' => 'petty_cash_voucher',
                        'toolbar' => 'advlist | autolink | link image | lists charmap | table tabledelete | pagebreak',
                        'plugins' =>  'advlist autolink link image lists charmap preview table pagebreak',
                    ]) ?>

                    <div class="mt-10 text-right">
                        <?= Html::submitButton('Save Petty Cash Voucher', [
                            'class' => 'btn btn-success'
                        ]) ?>
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close</button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>