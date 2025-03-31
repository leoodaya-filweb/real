<?php

use app\models\search\MemberSearch;
use app\widgets\ActiveForm;
use app\widgets\Dropzone;

/* @var $this yii\web\View */
/* @var $model app\models\Member */

$this->title = 'Import Member';
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => $member->indexUrl];
$this->params['breadcrumbs'][] = 'Import';
$this->params['searchModel'] = new MemberSearch();
$this->params['showCreateButton'] = true; 
?>
<div class="member-update-page">
    <div class="row">
        <div class="col-md-6">
            <p class="lead font-weight-bold">FILE (CSV) UPLOAD</p>
        	<?php $form = ActiveForm::begin(['id' => 'import-member-form']); ?>
                <?= Dropzone::widget([
                    'tag' => 'Member',
                    'title' => 'Drop file here (csv) or click to upload.',
                    'maxFiles' => 1,
                    'maxFilesize' => 100,
                    'model' => $model,
                    'attribute' => 'file_token',
                    'acceptedFiles' => '.csv',
                    'success' => "
                        KTApp.block('#import-member-form', {
                            overlayColor: '#000000',
                            state: 'warning',
                            message: 'Validating Content...'
                         });
                        $.ajax({
                            url: app.baseUrl + 'member/validate-file',
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
                                KTApp.unblock('#import-member-form');
                            },
                            error: function(e) {
                                alert(e.responseText);
                                KTApp.unblock('#import-member-form');
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