<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\form\transaction\SeniorCitizenIntakeSheetForm;
use app\widgets\ActiveForm;
use app\widgets\TinyMce;

$senior_citizen_intake_sheet = new SeniorCitizenIntakeSheetForm(['transaction_id' => $model->id]);
$createDocument = App::get('create_document');
$createDocument  = ($model->white_card)? 'false': $createDocument;

$this->registerJs(<<< JS
    $('.btn-create-senior-citizen-intake-sheet').click(function() {
        $('#modal-create-senior-citizen-intake-sheet').modal('show');
    });

    $('.btn-update-senior-citizen-intake-sheet').click(function() {
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
                var editor = tinymce.get('seniorcitizenintakesheetform-senior_citizen_intake_sheet'); 
                editor.setContent(`{$model->senior_citizen_intake_sheet}`);
                $('#modal-create-senior-citizen-intake-sheet').modal('show');
                KTApp.unblockPage();
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblockPage();
            }    
        });
    });

    if('{$createDocument}' == 'intake-sheet') {
        $('.btn-create-senior-citizen-intake-sheet').click();
    }
JS);
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => 'Intake Sheet Form',
    'toolbar' => <<< HTML
        <div class="card-toolbar">
            {$model->scisButton}
        </div>
    HTML
]); ?>
    <?= Html::ifElse($model->senior_citizen_intake_sheet, function() use($model) {
        return Tinymce::widget([
            'content' => $model->senior_citizen_intake_sheet,
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
    }, Html::tag('h5', 'No Intake Sheet yet')) ?>
<?php $this->endContent(); ?>


<div class="modal fade" id="modal-create-senior-citizen-intake-sheet" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-create-senior-citizen-intake-sheetLabel">General Intake Sheet Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'transaction-form',
                    'action' => ['transaction/create-senior-citizen-intake-sheet', 'transaction_id' => $model->id]
                ]); ?>
                    <?= TinyMce::widget([
                        'model' => $senior_citizen_intake_sheet,
                        'attribute' => 'senior_citizen_intake_sheet',
                        'toolbar' => 'advlist | autolink | link image | lists charmap | table tabledelete | ',
                        'plugins' =>  'advlist autolink link image lists charmap preview table pagebreak',
                    ]) ?>

                    <div class="mt-10 text-right">
                        <?= Html::submitButton('Save Intake Sheet', [
                            'class' => 'btn btn-success'
                        ]) ?>
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close</button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>