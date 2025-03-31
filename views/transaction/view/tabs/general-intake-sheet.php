<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\form\transaction\GeneralIntakeSheetForm;
use app\widgets\ActiveForm;
use app\widgets\TinyMce;

$general_intake_sheet = new GeneralIntakeSheetForm(['transaction_id' => $model->id]);
$createDocument = App::get('create_document');
$createDocument  = ($model->general_intake_sheet)? 'false': $createDocument;

$this->registerJs(<<< JS
    $('.btn-create-general-intake-sheet').click(function() {
        $('#modal-create-general-intake-sheet').modal('show');
    });

    $('.btn-update-general-intake-sheet').click(function() {
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
                var editor = tinymce.get('generalintakesheetform-general_intake_sheet'); 
                editor.setContent(`{$model->general_intake_sheet}`);
                $('#modal-create-general-intake-sheet').modal('show');
                KTApp.unblockPage();
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblockPage();
            }    
        });
    });

    if('{$createDocument}' == 'gis') {
        $('.btn-create-general-intake-sheet').click();
    }
JS);
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => "General Intake Sheet Form {$model->generalIntakeSheetActionBtn}",
    'toolbar' => <<< HTML
        <div class="card-toolbar">
            {$model->gisButton}
        </div>
    HTML
]); ?>
    <?= Html::ifElse($model->general_intake_sheet, function() use($model) {
        return Tinymce::widget([
            'content' => $model->general_intake_sheet,
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
    }, Html::tag('h5', 'No General Intake Sheet yet')) ?>
    <div class="mt-5">
        <?= $model->generalIntakeSheetActionBtn ?>
    </div>
<?php $this->endContent(); ?>


<div class="modal fade" id="modal-create-general-intake-sheet" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-create-general-intake-sheetLabel">General Intake Sheet Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'transaction-form',
                    'action' => ['transaction/create-general-intake-sheet', 'transaction_id' => $model->id]
                ]); ?>
                    <?= TinyMce::widget([
                        'model' => $general_intake_sheet,
                        'attribute' => 'general_intake_sheet',
                        'toolbar' => 'advlist | autolink | link image | lists charmap | table tabledelete | pagebreak',
                        'plugins' =>  'advlist autolink link image lists charmap preview table pagebreak',
                    ]) ?>

                    <div class="mt-10 text-right">
                        <?= Html::submitButton('Save General Intake Sheet', [
                            'class' => 'btn btn-success'
                        ]) ?>
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close</button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>