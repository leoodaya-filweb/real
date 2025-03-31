<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\search\DatabaseSearch;
use app\widgets\ActiveForm;
use app\widgets\Dropzone;
use app\models\form\DatabaseImportForm;
use app\widgets\Reminder;

/* @var $this yii\web\View */
/* @var $model app\models\Household */

$this->title = 'Import Database';
$this->params['breadcrumbs'][] = ['label' => 'Database: Priority Sectors', 'url' => (new DatabaseSearch())->indexUrl];
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = new DatabaseSearch();
$this->params['activeMenuLink'] = '/database';

$sampleImportFile = DatabaseSearch::SAMPLE_IMPORT_FILE;

$this->registerJs(<<< JS
    $.ajax({
        type: "GET",
        url: app.baseUrl + '{$sampleImportFile}',
        dataType: "text",
        success: function(text){
            var rows=text.split(/\\r?\\n/);
            var cols = [];
            for (var i = 0; i <= rows.length - 1; i++) {
                cols.push(rows[i].split(','));
            }

            let headHtml = '';
            for (var i = 0; i <= cols[0].length - 1; i++) {
                headHtml += '<th>'+ cols[0][i] +'</th>'
            }
            $('.sample-format thead tr').html(headHtml);

            let rowHtml = '';
            for (var i = 0; i <= cols.length - 1; i++) {
                if (i > 0 && i <= 2) {
                    rowHtml += '<tr>'
                    for (var b = 0; b <= cols[i].length - 1; b++) {
                        rowHtml += '<td>'+ cols[i][b] +'</td>'
                    }
                    rowHtml += '</tr>'
                }
            }
            $('.sample-format tbody').html(rowHtml);
        }
    });
JS);
?>
<div class="database-import-page">

    <div class="row">
        <div class="col-md-6">
            <p class="lead font-weight-bold">FILE (CSV) UPLOAD</p>
            <?php $form = ActiveForm::begin(['id' => 'import-database-form']); ?>
                <?= Dropzone::widget([
                    'tag' => 'Database',
                    'title' => 'Drop file here (csv) or click to upload.',
                    'maxFiles' => 1,
                    'maxFilesize' => 100,
                    'model' => $model,
                    'attribute' => 'file_token',
                    'acceptedFiles' => array_map(
                        function($val) { 
                            return ".{$val}"; 
                        }, DatabaseImportForm::ALLOWED_EXTENSIONS
                    ),
                    'success' => "
                        KTApp.block('#import-database-form', {
                            overlayColor: '#000000',
                            state: 'warning',
                            message: 'Validating Content...'
                         });
                        $.ajax({
                            url: app.baseUrl + 'database/validate-file',
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
                                KTApp.unblock('#import-database-form');
                            },
                            error: function(e) {
                                alert(e.responseText);
                                KTApp.unblock('#import-database-form');
                            }
                        })
                    "
                ]) ?>
                <div class="form-group mt-5">
                    <?= ActiveForm::buttons() ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-md-6">
            <?= Reminder::widget([
                'head' => 'Priority Sector Guide',
                'message' => Html::foreach($prioritySector->data, function($prioritySector) {
                    $tr = <<< HTML
                        <tr>
                            <th>{$prioritySector['id']}</th>
                            <td>= {$prioritySector['label']}</td>
                            <td> ({$prioritySector['code']})</td>
                        </tr>
                    HTML;

                    return <<< HTML
                        <table>
                            <tbody>{$tr}</tbody>
                        </table>
                    HTML;
                }) . <<< HTML
                    <p class="lead font-weight-bold mt-5">DATE FORMAT</p>
                    <ul>
                        <li>YYYY-MM-DD</li>
                        <li>DD/MM/YYYY</li>
                    </ul>
                HTML,
                'type' => 'info',
                'withDot' => false,
                'withClose' => false
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <p class="lead font-weight-bold mt-10">SAMPLE FORMAT
                <a target="_blank" href="<?= App::baseUrl($sampleImportFile) ?>" class="btn btn-sm btn-info font-weight-bold">
                    Download Sample CSV
                </a>
            </p>
            <div class="table-responsive sample-format">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>