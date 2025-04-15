<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Specialsurvey;
use app\widgets\Anchors;
use app\widgets\BulkAction;
use app\widgets\DateRange;
use app\widgets\ExportButton;
use app\widgets\FilterColumn;
use app\widgets\Grid;
use app\widgets\Mapbox;
use app\widgets\TinyMce;
use app\widgets\PieChart;
use yii\helpers\Url;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SpecialsurveySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Socio Economic Survey';
$this->params['breadcrumbs'][] = $this->title;
$this->params['searchModel'] = $searchModel; 
$this->params['showCreateButton'] = true; 
$this->params['showExportButton'] = false;//true;
$this->params['activeMenuLink'] = '/specialsurvey';
$this->params['createTitle'] = 'Create Survey';


// FilterColumn::widget(['searchModel' => $searchModel]);


$this->params['headerButtons'] = implode(' ', [
   
    Html::a('Import CSV', ['specialsurvey/importcsv'],[
        'class' => "font-weight-bold btn btn-primary font-weight-bolder font-size-sm btn-create ml-1",
    ]),
    Html::a('Print Survey Form', '#', [
        'data-toggle' => 'modal',
        'data-target' => '#modal-survey-form',
        'class' => 'btn btn-bg-white btn-text-dark-50 btn-hover-text-primary btn-icon-primary font-weight-bolder font-size-sm px-5 mr-3',
        'style'=>'border: 1px solid #ccc;'
    ]),
     ExportButton::widget([]),
     FilterColumn::widget(['searchModel' => $searchModel])
]);




?>


<?php if ($cra === true): ?>
    <div class="table-responsive mb-5 mt-3">
        <table class="table table-hover table-striped-columns table-striped  rounded-3">
            <thead >
                <tr class="text-center text-primary ">
                    <th scope="col">Survey Name</th>
                    <th scope="col">Total Records</th> <!-- New column -->
                    <th scope="col">Converted Voters</th>
                    <th scope="col">Conversion Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($surveyLabels as $index => $surveyName): ?>
                    <tr class="text-center">
                        <td><?= Html::encode($surveyName) ?></td>
                        <td ><?= Html::encode($surveyRecordCounts[$surveyName] ?? 0) ?></td> <!-- Display total records -->
                        <td><?= Html::encode($conversionPerSurvey[$surveyName] ?? 0) ?></td>
                        <td>
                            <?php 
                                $surveyTotal = isset($conversionPerSurvey[$surveyName]) ? $conversionPerSurvey[$surveyName] : 0;
                                $surveyPercentage = $totalVoters > 0 ? round(($surveyTotal / $surveyRecordCounts[$surveyName]) * 100, 2) : 0;
                            ?>
                            <?= Html::encode($surveyPercentage) ?>%
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h2 class="mb-5 ">List of Converted Voters</h2>
<?php endif; ?>





<?= Html::beginForm(['bulk-action'], 'post'); ?>
        <?= BulkAction::widget(['searchModel' => $searchModel]) ?>
        <?= Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]); ?>
<?= Html::endForm(); ?> 
    

    
    



