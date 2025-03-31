<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\search\SpecialsurveySearch;
use app\widgets\ActiveForm;
use app\models\form\SpecialsurveyImportForm;
use app\widgets\Dropzone;

/* @var $this yii\web\View */
/* @var $model app\models\Household */

$this->title = 'Import Survey';
$this->params['breadcrumbs'][] = ['label' => 'Survey', 'url' => (new SpecialsurveySearch())->indexUrl];
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = new SpecialsurveySearch();
$this->params['activeMenuLink'] = '/specialsurvey/importcsv';
?>
<div class="specialsurvey-update-page">
    <div class="row">
    	<div class="col-md-6">
    		<div class="mb-3"><strong>Special Notes:</strong></div>
			<div>
				<strong>Criteria 1-5 Color Code</strong><br/>
                <table>
                    <tbody>
    				<?= Html::foreach(App::setting('surveyColor')->survey_color, function($survey) {
                        return <<< HTML
                            <tr>
                                <td>{$survey['id']}</td>
                                <td>= {$survey['label']}</td>
                                <td>
                                    <span style="color:{$survey['color']};background:{$survey['color']};">
                                        222
                                    </span>
                                </td>
                            </tr>
                        HTML;
                    }) ?>
                    </tbody>
                </table>
			</div>
			<div class="mt-3 mb-10">
				<strong>Date Format:</strong> MM/DD/YYYY
			</div>

    	</div>
        <div class="col-md-6">
            <p class="lead font-weight-bold">FILE (CSV) UPLOAD</p>
            <?php $form = ActiveForm::begin(['id' => 'import-specialsurvey-form']); ?>
                <?= Dropzone::widget([
                    'tag' => 'Special Survey',
                    'title' => 'Drop file here (csv) or click to upload.',
                    'maxFiles' => 1,
                    'maxFilesize' => 100,
                    'model' => $model,
                    'attribute' => 'file_token',
                    'acceptedFiles' => array_map(
                        function($val) { 
                            return ".{$val}"; 
                        }, SpecialsurveyImportForm::ALLOWED_EXTENSIONS
                    ),
                    'success' => "
                        KTApp.block('#import-specialsurvey-form', {
                            overlayColor: '#000000',
                            state: 'warning',
                            message: 'Validating Content...'
                         });
                        $.ajax({
                            url: app.baseUrl + 'specialsurvey/validate-file',
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
                                KTApp.unblock('#import-specialsurvey-form');
                            },
                            error: function(e) {
                                alert(e.responseText);
                                KTApp.unblock('#import-specialsurvey-form');
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
    <div class="row">
    	<div class="col-md-12">
			<h3>
				Sample CSV Format 
				<a href="<?= App::baseUrl('default/csv_format_survey.png') ?>" class="btn btn-outline-primary btn-sm font-weight-bolder" target="_blank">
					View
				</a>
			</h3>
			<img style="max-width:100%;" src="<?= App::baseUrl('default/csv_format_survey.png') ?>" class="navber-brand-image img-fluid">
    	</div>
    </div>
</div>