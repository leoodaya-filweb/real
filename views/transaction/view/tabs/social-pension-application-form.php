<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\form\transaction\SocialPensionApplicationForm;
use app\widgets\ActiveForm;
use app\widgets\TinyMce;

$social_pension_application_form = new SocialPensionApplicationForm(['transaction_id' => $model->id]);
$createDocument = App::get('create_document');
$createDocument  = ($model->general_intake_sheet)? 'false': $createDocument;

$this->registerJs(<<< JS
    $('.btn-create-social-pension-application-form').click(function() {
        $('#modal-create-social-pension-application-form').modal('show');
    });

    $('.btn-update-social-pension-application-form').click(function() {
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
                var editor = tinymce.get('socialpensionapplicationform-social_pension_application_form'); 
                editor.setContent(`{$model->social_pension_application_form}`);
                $('#modal-create-social-pension-application-form').modal('show');
                KTApp.unblockPage();
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblockPage();
            }    
        });
    });

    if('{$createDocument}' == 'spaf') {
        $('.btn-create-social-pension-application-form').click();
    }
JS);
?>

<?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
    'title' => 'Social Pension Application Form',
    'toolbar' => <<< HTML
        <div class="card-toolbar">
            {$model->spafButton}
        </div>
    HTML
]); ?>
    <?= Html::ifElse($model->social_pension_application_form, function() use($model) {
        return Tinymce::widget([
            'content' => $model->social_pension_application_form,
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


<div class="modal fade" id="modal-create-social-pension-application-form" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-create-social-pension-application-formLabel">Social Pension Application Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'transaction-form',
                    'action' => ['transaction/create-social-pension-application-form', 'transaction_id' => $model->id]
                ]); ?>
                    <?= TinyMce::widget([
                        'model' => $social_pension_application_form,
                        'attribute' => 'social_pension_application_form',
                        'toolbar' => 'advlist | autolink | link image | lists charmap | table tabledelete | pagebreak',
                        'plugins' =>  'advlist autolink link image lists charmap preview table pagebreak',
                    ]) ?>

                    <div class="mt-10 text-right">
                        <?= Html::submitButton('Save Application Form', [
                            'class' => 'btn btn-success'
                        ]) ?>
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close</button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>