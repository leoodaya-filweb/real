<?php

use app\models\search\HouseholdSearch;
use app\widgets\ActiveForm;
use app\widgets\Dropzone;

/* @var $this yii\web\View */
/* @var $model app\models\Household */

$this->title = 'Import Household';
$this->params['breadcrumbs'][] = ['label' => 'Households', 'url' => $household->indexUrl];
$this->params['breadcrumbs'][] = 'Import';
$this->params['searchModel'] = new HouseholdSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="household-update-page">
    <div class="row">
        <div class="col-md-6">
            <p class="lead font-weight-bold">FILE (CSV) UPLOAD</p>
            <?php $form = ActiveForm::begin(['id' => 'import-household-form']); ?>
                <?= Dropzone::widget([
                    'tag' => 'Household',
                    'title' => 'Drop file here (csv) or click to upload.',
                    'maxFiles' => 1,
                    'maxFilesize' => 100,
                    'model' => $model,
                    'attribute' => 'file_token',
                    'acceptedFiles' => '.csv',
                    'success' => "
                        KTApp.block('#import-household-form', {
                            overlayColor: '#000000',
                            state: 'warning',
                            message: 'Validating Content...'
                         });
                        $.ajax({
                            url: app.baseUrl + 'household/validate-file',
                            method: 'get',
                            data: {file_token: s.file.token},
                            dataType: 'json',
                            success: function(s) {
                                if(s.status == 'success') {
                                    Swal.fire('Validated', s.message, 'success');
                                }
                                else {
                                    Swal.fire('Error', s.errorSummary, 'error');
                                    myDropzone.removeFile(file);
                                }
                                KTApp.unblock('#import-household-form');
                            },
                            error: function(e) {
                                alert(e.responseText);
                                KTApp.unblock('#import-household-form');
                            }
                        })
                    "
                ]) ?>
                <div class="form-group mt-5">
                    <?= ActiveForm::buttons() ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>