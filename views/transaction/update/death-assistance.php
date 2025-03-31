<?php

use app\helpers\App;
use app\helpers\Html;
use app\helpers\Url;
use app\models\File;
use app\models\Relation;
use app\models\search\TransactionSearch;
use app\widgets\ActiveForm;
use app\widgets\Autocomplete;
use app\widgets\Checkbox;
use app\widgets\Dropzone;
use app\widgets\Reminder;
use app\widgets\Value;
use app\widgets\Webcam;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Update Transaction: Death Assistance';
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => $model->indexUrl];
$this->params['breadcrumbs'][] = 'Update';
$this->params['breadcrumbs'][] = ['label' => 'Death Assistance', 'url' => ['transaction/view', 'token'=>Yii::$app->request->get('token')]];
$this->params['searchModel'] = new TransactionSearch();
$this->params['wrapCard'] = false;
Html::if($withSlug, function() use($member) {
    $this->params['breadcrumbs'][] = ['label' => $member->name, 'url' => $member->viewUrl];
});

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
    .section-document {
        border: 1px dashed #ccc;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
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

    $('#deathassistanceform-name_of_deceased').on('keydown', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }

        $('#deathassistanceform-id_of_deceased').val(0);
    });

    $(document).on('click', '.btn-select-patient', function() {
        let id = $(this).data('id'),
            viewUrlPersonalInformationTab = $(this).data('view-url-personal-tab');

        $('#deathassistanceform-id_of_deceased').val(id);
        $('#modal-duplicate-patient').modal('hide');

        let badge = '<a class="btn btn-sm btn-outline-primary font-weight-bold" href="'+ viewUrlPersonalInformationTab +'" target="_blank" style="padding: 3px 8px;">View Profile</a>';
        $('.span-view-profile').html(badge);
    });
JS);
?>

<div class="container">

<?php $form = ActiveForm::begin(['id' => 'transaction-form']); ?>
    <div class="row">
        <div class="col-md-7">
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Transaction Form'
            ]); ?>
                <div class="form-group">
                    <label>Relation to Patient</label>
                    <div class="radio-list">
                        <?= Html::foreach(App::keyMapParams('patient_relation_types'), function($label, $id) use($model) {
                            if ($id == 1) {return ;}
                            $checked = $id == $model->relation_type ? 'checked="checked"': '';
                            return <<< HTML
                                <label class="radio">
                                    <input class="relation-type" type="radio" {$checked} name="DeathAssistanceForm[relation_type]" value="{$id}">
                                    <span></span>{$label}
                                </label>
                            HTML;
                        }) ?>
                    </div>
                </div>

                <?= Autocomplete::widget([
                    'input' => $form->field($model, 'name_of_deceased', [
                        'template' => '
                            {label} <span class="span-view-profile">'. $model->btnPatientProfile .'</span>
                            {input} 
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
                                        $('#deathassistanceform-id_of_deceased').val(s.model.id);

                                        let badge = '<a class="btn btn-sm btn-outline-primary font-weight-bold" href="'+ s.model.viewUrlPersonalInformationTab +'" target="_blank" style="padding: 3px 8px;">View Profile</a>';
                                        
                                        $('.span-view-profile').html(badge);
                                    }
                                }
                                else {
                                    // Swal.fire('Error', s.error, 'error');
                                    $('#deathassistanceform-id_of_deceased').val('');
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

                <datalist id="relation">
                    <?= Html::foreach(Relation::dropdown('label', 'label'), function($s) {
                        return "<option value='{$s}'>";
                    }) ?>
                </datalist>

                <?= $form->field($model, 'relation_to_patient')->textInput([
                    'maxlength' => true,
                    'list' => 'relation',
                ]) ?>

                <?= $form->field($model, 'caused_of_death')->textInput([
                    'maxlength' => true
                ]) ?>

                <?= $form->field($model, 'id_of_deceased')->textInput([
                    'maxlength' => true,
                    'class' => 'app-hidden'
                ])->label(false) ?>

                <label class="control-label">Client Category</label>
                <?= Checkbox::widget([
                    'data' => App::keyMapParams('client_categories', 'label'),
                    'name' => 'DeathAssistanceForm[client_category][]',
                    'inputClass' => 'checkbox client_category',
                    'checkedFunction' => function($key, $value) use ($model) {
                        return isset($model->client_category) && is_array($model->client_category) && in_array($key, $model->client_category) ? 'checked': '';
                    }
                ]) ?>

                <div class="mt-10"></div>

                <?= $form->field($model, 'amount')->textInput([
                    'type' => 'number'
                ]) ?>

                <?= $form->field($model, 'remarks')->textarea([
                    'rows' => 10
                ]) ?>
                
          
            
            
            
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
                                    $('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="DeathAssistanceForm[files][]" value="'+ s.file.token +'">'); 
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

                                    $('.document-container-holder').prepend('<input class="app-hidden file-hidden-input-'+ s.file.token +'" type="text" name="DeathAssistanceForm[files][]" value="'+ s.file.token +'">'); 
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
                                    ]) . Html::input('text', 'DeathAssistanceForm[files][]', $file->token,
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
            <?php $this->beginContent('@app/views/layouts/_card_wrapper.php', [
                'title' => 'Requirements'
            ]); ?>
                <div>
                    <?= Value::widget([
                        'label' => '1) Three (3) of the following ID as proof of residency in Real, Quezon1.',
                        'content' => <<< HTML
                            <ul>
                                <li> a. Voter’s ID (Photocopy) </li>
                                <li> b. Community Tax Certificate <em class="font-weight-bolder">(Commission on Elections)</em> </li> 
                                <li> c. Birth Certificate (1 Photocopy) <em class="font-weight-bolder">(Barangay Hall/ Municipal Treasurer’s Office)</em> </li>
                                <li> d. Income Tax Return (ITR) <em class="font-weight-bolder">(Local Civil Registrar/Philippine Statistics Authority)</em> </li>
                                <li> e. Certification from MPDO that the deceased
                                    belongs to the Community Based Monitoring
                                    System (CBMS) database <em class="font-weight-bolder">(Bureau of Internal Revenue (BIR) MPWDO)</em> </li>
                                <li> f. Postal I.D. <em class="font-weight-bolder">(Post Office)</em></li>
                                <li> g. Barangay I.D. <em class="font-weight-bolder">(Barangay Hall)</em></li>
                                <li> h. Certification from a Representative of Pantawid
                                    Pamilyang Pilipino Program  <em class="font-weight-bolder">(4Ps Office)</em></li>
                            </ul>
                        HTML
                    ]) ?>
                    <div class="my-3"></div>
                    <?= Value::widget([
                        'label' => '2) Barangay Clearance of the claimant (1 Original)',
                        'content' => <<< HTML
                            <em>Where to secure: </em>
                            Barangay where the claimant/ beneficiary is residing
                        HTML
                    ]) ?>
                    <div class="my-3"></div>
                    <?= Value::widget([
                        'label' => '3) Proof of relationship to the deceased such as but not limited to birth certificate, marriage contract, and baptismal. (1 Photocopy)',
                        'content' => <<< HTML
                            <em>Where to secure: </em>
                            Local Civil Registry Office/ Philippine Statistics Authority/ Church
                        HTML
                    ]) ?>
                    <div class="my-3"></div>
                    <?= Value::widget([
                        'label' => '4) Certificate of Residency of the deceased (1 Original)',
                        'content' => <<< HTML
                            <em>Where to secure: </em>
                            Barangay where the deceased resided
                        HTML
                    ]) ?>
                    <div class="my-3"></div>
                    <?= Value::widget([
                        'label' => '5) Certified True Copy of Death Certificate LCR Copy (1 Photocopy)',
                        'content' => <<< HTML
                            <em>Where to secure: </em>
                            Local Civil Registry Office
                        HTML
                    ]) ?>
                    <div class="my-3"></div>
                    <?= Value::widget([
                        'label' => '6) If the deceased is a Senior Citizen, documents such as Certification from the Senior Citizens Association President of the respective Barangay are required as proof that the deceased is a registered Senior Citizen in the Barangay. (1 Original)',
                        'content' => <<< HTML
                            <em>Where to secure: </em>
                            Senior Citizens Association President of the respective Barangay
                        HTML
                    ]) ?>
                </div>

                

            <?php $this->endContent(); ?> 
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