<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\File;
use app\models\Relation;
use app\models\Transaction;
use app\models\search\TransactionSearch;
use app\widgets\ActiveForm;
use app\widgets\Autocomplete;
use app\widgets\BootstrapSelect;
use app\widgets\Checkbox;
use app\widgets\Dropzone;
use app\widgets\Reminder;
use app\widgets\Value;
use app\widgets\Webcam;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = "Update Transaction: {$member->name}";
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['searchModel'] = new TransactionSearch();
$this->params['wrapCard'] = false;
$this->params['breadcrumbs'][] = ['label' => 'Emergency Welfare Program', 'url' => ['transaction/view', 'token'=>Yii::$app->request->get('token')]];

//echo $model->token;


$AICS_MEDICAL = Transaction::AICS_MEDICAL;
$AICS_MEDICAL_MEDICINE = Transaction::AICS_MEDICAL_MEDICINE;
$AICS_LABORATORY_REQUEST = Transaction::AICS_LABORATORY_REQUEST;
$BALIK_PROBINSYA_PROGRAM = Transaction::BALIK_PROBINSYA_PROGRAM;
$CLIENT_IS_PATIENT = Transaction::CLIENT_IS_PATIENT;

$MEDICAL_ASSISTANCE_CASH = Transaction::MEDICAL_ASSISTANCE_CASH;
$BURIAL_ASSISTANCE = Transaction::BURIAL_ASSISTANCE;
$TRANSPORTATION_ASSISTANCE = Transaction::TRANSPORTATION_ASSISTANCE;
$MEDICAL_ASSISTANCE_MEDICINE = Transaction::MEDICAL_ASSISTANCE_MEDICINE;

$MEDICAL_ASSISTANCE_LAB_REQUEST = Transaction::MEDICAL_ASSISTANCE_LAB_REQUEST;
$OTHER_RSA = Transaction::OTHER_RSA;

$this->registerCss(<<< CSS
    .webcam-input-group {
        max-width: 400px;
        margin: 0 auto;
    }
    .medicines-container,
    .medical-container, 
    .destination-container, 
    .section-document,
    .requirements, 
    .section-document {
        border: 1px dashed #ccc;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
    }
    .field-emergencywelfareprogramform-relation_to_patient,
    .field-emergencywelfareprogramform-patient_name,
    .field-emergencywelfareprogramform-medical_procedure_requested,
    .field-emergencywelfareprogramform-laboratory_procedure_requested,
    .field-emergencywelfareprogramform-referral_to,
    .field-emergencywelfareprogramform-other_rsa,
    .medicines-container, 
    .medical-container, 
    .destination-container,
    .field-emergencywelfareprogramform-patient_id {
        display: none;
    }
CSS);

$this->registerJs(<<< JS
    $('#emergencywelfareprogramform-recommended_services_assistance').on('change', function() {
        let value = $(this).val(),
            rsa = app.params.recommended_services_assistance;

        if(value == {$MEDICAL_ASSISTANCE_LAB_REQUEST}) {
            $('.field-emergencywelfareprogramform-referral_to').show();
        }
        else {
            $('.field-emergencywelfareprogramform-referral_to').hide();
        }

        if(value == {$OTHER_RSA}) {
            $('.field-emergencywelfareprogramform-other_rsa').show();
        }
        else {
            $('.field-emergencywelfareprogramform-other_rsa').hide();
        }
    });
    $('#emergencywelfareprogramform-recommended_services_assistance').trigger('change');

    $('#emergencywelfareprogramform-emergency_welfare_program').change(function() {
        let value = $(this).val(),
            params = app.params.emergency_welfare_programs[value],
            html = '<p class="lead font-weight-bold">Requirements</p>';

        for (const key in params['requirements']) {
            html += '<div class="l-border mb-3">';
                html += '<label class="font-weight-bolder">'+ (parseInt(key) + 1) + ') ' + params['requirements'][key].name +'</label>';
                html += '<p class="value"><em>Where to secure:</em> '+ params['requirements'][key].where_to_secure +'</p>';
            html += '</div>';
        }
        $('.requirements').html(html);

        if(value == {$AICS_MEDICAL_MEDICINE}) {
            $('.medicines-container').show();
        }
        else {
            $('.medicines-container').hide();
        }

        if(value == {$BALIK_PROBINSYA_PROGRAM}) {
            $('.medical-container').hide();
            $('.destination-container').show();
        }
        else {
            $('.medical-container').show();
            $('.destination-container').hide();
        }

        if(value == {$AICS_MEDICAL}) {
            $('.field-emergencywelfareprogramform-medical_procedure_requested').show();
        }
        else {
            $('.field-emergencywelfareprogramform-medical_procedure_requested').hide();
        }

        if(value == {$AICS_LABORATORY_REQUEST}) {
            $('.field-emergencywelfareprogramform-laboratory_procedure_requested').show();
        }
        else {
            $('.field-emergencywelfareprogramform-laboratory_procedure_requested').hide();
        }
    });

    $('#emergencywelfareprogramform-emergency_welfare_program').trigger('change');

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

    $(document).on('click', '.btn-edit-medicine', function() {
        KTApp.block('.medicines-container', {
            state: 'warning', // a bootstrap color
            message: 'Loading Form...',
        });

        let id = $(this).data('id');
        $.ajax({
            url: app.baseUrl + 'medicine/update',
            data: {id: id},
            dataType: 'json',
            method: 'get',
            success: function(s) {
                if(s.status == 'success') {
                    $('#modal-medicine .modal-body').html(s.form);
                    $('#modal-medicine .modal-title').html('Edit Medicine');
                    $('#modal-medicine').modal('show');
                }
                else {
                    Swal.fire('Error', s.error, 'error');
                }
                KTApp.unblock('.medicines-container');
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('.medicines-container');
            }
        });
    });

    $(document).on('click', '.btn-delete-medicine', function() {
        let self = this,
            id = $(self).data('id');
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
                KTApp.block('.medicines-container', {
                    state: 'warning', // a bootstrap color
                    message: 'Loading...',
                });
                $.ajax({
                    url: app.baseUrl + 'medicine/delete/' + id,
                    data: {id: id},
                    method: 'post',
                    dataType: 'json',
                    success: function(s) {
                        if(s.status == 'success') {
                            $(self).closest('tr').remove();
                            Swal.fire("Removed", "Medicine Deleted", "success");
                        }
                        else {
                            Swal.fire('Error', s.error, 'error');
                        }
                        KTApp.unblock('.medicines-container');
                    },
                    error: function(e) {
                        Swal.fire('Error', e.responseText, 'error');
                        KTApp.unblock('.medicines-container');
                    }
                });
            } 
        });
    });

    $('.btn-add-medicine').click(function() {

        KTApp.block('.medicines-container', {
            state: 'warning', // a bootstrap color
            message: 'Loading Form...',
        });

        let id = $(this).data('id');
        $.ajax({
            url: app.baseUrl + 'medicine/create',
            data: {transaction_id: {$model->transaction_id}},
            dataType: 'json',
            method: 'get',
            success: function(s) {
                if(s.status == 'success') {
                    $('#modal-medicine .modal-body').html(s.form);
                    
                    $('#modal-medicine .modal-title').html('Add Medicine');
                    $('#modal-medicine').modal('show');
                }
                else {
                    Swal.fire('Error', s.error, 'error');
                }
                KTApp.unblock('.medicines-container');
            },
            error: function(e) {
                Swal.fire('Error', e.responseText, 'error');
                KTApp.unblock('.medicines-container');
            }
        });
    });

    let checkRelation = function(value) {
        if(value == {$CLIENT_IS_PATIENT}) {
            $('.field-emergencywelfareprogramform-relation_to_patient').hide();
            $('.field-emergencywelfareprogramform-patient_name').hide();
            $('.field-emergencywelfareprogramform-patient_id').hide();
            $('.span-client-view-profile').show();
        }
        else {
            $('.field-emergencywelfareprogramform-relation_to_patient').show();
            $('.field-emergencywelfareprogramform-patient_name').show();
            $('.field-emergencywelfareprogramform-patient_id').show();
            $('.span-client-view-profile').hide();
        }
    }

    $('.relation-type').on('change', function() {
        checkRelation($(this).val());
    });

    checkRelation({$model->relation_type});

    $(document).on('click', '.btn-select-patient', function() {
        let id = $(this).data('id'),
            viewUrlPersonalInformationTab = $(this).data('view-url-personal-tab');

        $('#emergencywelfareprogramform-patient_id').val(id);
        $('#modal-duplicate-patient').modal('hide');

        let badge = '<a class="btn btn-sm btn-outline-primary font-weight-bold" href="'+ viewUrlPersonalInformationTab +'" target="_blank" style="padding: 3px 8px;">View Profile</a>';
        $('.span-view-profile').html(badge);
    });

    $('#emergencywelfareprogramform-patient_name').on('input', function() {
        $('#emergencywelfareprogramform-patient_id').val('');
        $('.span-view-profile').html('');
    });
JS);
?>

<div class="container">
<?php $form = ActiveForm::begin(['id' => 'transaction-form']); ?>

    <div class="row">
        <div class="col-md-7">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Transaction Form',
            ]); ?>
                <?= $form->field($model, 'emergency_welfare_program')->dropDownList(
                    App::keyMapParams('emergency_welfare_programs'), [
                        'prompt' => '- Select -',
                        'class' => 'kt-selectpicker form-control'
                    ]
                ) ?>
                

                <?= $form->field($model, 'medical_procedure_requested')->textInput([
                    'maxlength' => true,
                ]) ?>

                <?= $form->field($model, 'laboratory_procedure_requested')->textInput([
                    'maxlength' => true,
                ]) ?>

                <div class="destination-container">
                    <p class="lead font-weight-bold">
                        Destination
                    </p>
                    <?= $form->field($model, 'destination_province')->textInput(['maxlength' => true, ]) ?>
                    <?= $form->field($model, 'destination_municipality')->textInput(['maxlength' => true, ]) ?>
                </div>

                <div class="medical-container">
                    <p class="lead font-weight-bold">
                        Patient 
                        <span class="span-client-view-profile">
                            <?= $model->btnClientProfile ?>
                        </span>
                    </p>
                    <div class="form-group">
                        <label>Relation to Patient</label>
                        <div class="radio-list">
                            <?= Html::foreach(App::keyMapParams('patient_relation_types'), function($label, $id) use($model) {
                                $checked = ($id == $model->relation_type)? 'checked="checked"': '';
                                return <<< HTML
                                    <label class="radio">
                                        <input class="relation-type" type="radio" {$checked} name="EmergencyWelfareProgramForm[relation_type]" value="{$id}">
                                        <span></span>{$label}
                                    </label>
                                HTML;
                            }) ?>
                        </div>
                    </div>

                    <?= Autocomplete::widget([
                        'input' => $form->field($model, 'patient_name', [
                            'template' => '
                                {label} <span class="span-view-profile">'. $model->btnPatientProfile .'</span>
                                {input} '. '<div class="mt-2">Patient not found? ' . Html::a('Create here!', $member->createUrl, ['target' => '_blank']) . '</div>
                                {error}
                            '
                        ])->textInput([
                            'maxlength' => true,
                            'prevent-default' => 'enter'
                        ]),
                        'url' => Url::to(['member/find-by-name']),
                        'submitOnclickJs' => <<< JS
                            KTApp.block('body', {
                                overlayColor: '#fff',
                                state: 'primary',
                                message: 'Please wait...'
                            });
                            $.ajax({
                                url: app.baseUrl + 'member/find-by-name',
                                method: 'get',
                                data: {
                                    keywords: inp.value,
                                    type: 'html',
                                },
                                dataType: 'json',
                                success: function(s) {
                                    if(s.status == 'success') {
                                        if(s.multiple) {
                                            $('#emergencywelfareprogramform-patient_id').val('');

                                            let html = '';
                                            html += '<table class="table table-bordered">';
                                                html += '<thead>';
                                                    html += '<tr>';
                                                        html += '<th>#</th>';
                                                        html += '<th>FULLNAME</th>';
                                                        html += '<th>BIRTHDATE</th>';
                                                        html += '<th>DETAILS</th>';
                                                        html += '<th>ACTION</th>';
                                                    html += '</tr>';
                                                html += '</thead>';
                                                html += '<tbody>';
                                                    for (const model in s.models) {
                                                        html += '<tr>';
                                                            html += '<td>'+ (parseInt(model) + 1) +'</td>';
                                                            html += '<td>'+ s.models[model].fullname +'</td>';
                                                            html += '<td>'+ s.models[model].birth_date +'</td>';
                                                            html += '<td>'+ s.models[model].widgetTags +'</td>';
                                                            html += '<td><button type="button" data-id="'+ s.models[model].id +'" data-view-url-personal-tab="'+ s.models[model].viewUrlPersonalInformationTab +'" class="btn btn-sm btn-outline-success font-weight-bold btn-select-patient">Select</button> <a href="'+ s.models[model].viewUrlPersonalInformationTab +'" class="btn btn-sm btn-outline-info font-weight-bold" target="_blank">View Profile</a></td>';
                                                        html += '</tr>';
                                                    }
                                                html += '</tbody>';

                                            html += '</table>';

                                            $('#modal-duplicate-patient .modal-body').html(html);
                                            $('#modal-duplicate-patient').modal('show');
                                        }
                                        else {
                                            $('#emergencywelfareprogramform-patient_id').val(s.model.id);
                                            let badge = '<a class="btn btn-sm btn-outline-primary font-weight-bold" href="'+ s.model.viewUrlPersonalInformationTab +'" target="_blank" style="padding: 3px 8px;">View Profile</a>';
                                            
                                            $('.span-view-profile').html(badge);
                                        }
                                    }
                                    else {
                                        // Swal.fire('Error', s.error, 'error');
                                    }
                                    KTApp.unblock('body');
                                },
                                error: function(e) {
                                    Swal.fire('Error', e.responseText, 'error');
                                    KTApp.unblock('body');
                                }
                            });
                        JS
                    ]) ?>

                    <?= $form->field($model, 'patient_id')->textInput([
                        'type' => 'number',
                        'class' => 'app-hidden'
                    ])->label(false) ?>

                    <datalist id="relation">
                        <?= Html::foreach(Relation::dropdown('label', 'label'), function($s) {
                            return "<option value='{$s}'>";
                        }) ?>
                    </datalist>

                    <?= $form->field($model, 'relation_to_patient')->textInput([
                        'maxlength' => true,
                        'list' => 'relation',
                    ]) ?>
                    <?= $form->field($model, 'diagnosis')->textInput(['maxlength' => true])->label('Medical Problem') ?>
                </div>

                <label class="control-label">Client Category</label>
                <?= Checkbox::widget([
                    'data' => App::keyMapParams('client_categories', 'label'),
                    'name' => 'EmergencyWelfareProgramForm[client_category][]',
                    'inputClass' => 'checkbox client_category',
                    'checkedFunction' => function($key, $value) use ($model) {
                        return isset($model->client_category) && is_array($model->client_category) && in_array($key, $model->client_category) ? 'checked': '';
                    }
                ]) ?>

                <div class="mt-10"></div>

                <div class="medicines-container">
                    <p class="lead font-weight-bold">
                        Medicines
                        <button type="button" class="btn btn-light-primary btn-sm font-weight-bolder btn-add-medicine">
                            Add
                        </button>
                    </p>
                    <?= $this->render('/transaction/_medicines', [
                        'model' => $model
                    ]) ?>

                    <datalist id="units">
                        <?= Html::foreach(App::keyMapParams('units'), function($s) {
                            return "<option value='{$s}'>";
                        }) ?>
                    </datalist>
                </div>

                <?= BootstrapSelect::widget([
                    'attribute' => 'recommended_services_assistance',
                    'model' => $model,
                    'form' => $form,
                    'data' => App::keyMapParams('recommended_services_assistance'),
                ]) ?>

                <?= $form->field($model, 'amount')->textInput([
                    'type' => 'number'
                ]) ?>

                <?= $form->field($model, 'referral_to')->textInput([
                    'maxlength' => true
                ]) ?>
                <?= $form->field($model, 'other_rsa')->textarea([
                    'rows' => 8
                ])->label('Specify') ?>
        






                <section class="mt-10 section-document">
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
                                    $('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="EmergencyWelfareProgramForm[files][]" value="'+ s.file.token +'">'); 
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
                                    $('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="EmergencyWelfareProgramForm[files][]" value="'+ s.file.token +'">'); 
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
                                <?= Html::foreach($model->imageFiles, function($file) {
                                    return $this->render('/file/_row', [
                                        'model' => $file
                                    ]) . Html::input('text', 'EmergencyWelfareProgramForm[files][]', $file->token,
                                        ['class' => "app-hidden file-hidden-input-{$file->token}"]
                                    );
                                }) ?>
                            <?php $this->endContent(); ?>
                        </div>
                    </div>
                </section>

                
                <div class="mt-5 text-right">
                <?= ActiveForm::buttons('lg') ?>
                 </div> 
                
            <?php $this->endContent(); ?> 
            
  
            
        </div>
        <div class="col-md-5">
            
            <div class="requirements"></div>
            
        </div>
    </div>

 
    
  <?php ActiveForm::end(); ?>
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

<div class="modal fade" id="modal-medicine" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Medicine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>

