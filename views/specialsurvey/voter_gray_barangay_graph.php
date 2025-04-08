<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Specialsurvey;
use app\helpers\Url;
use yii\web\View;
use yii\helpers\ArrayHelper;

$queryParams = App::queryParams();
$selectedSurvey = $queryParams['survey_name'] ?? null;
$selectedBarangay = $queryParams['barangay'] ?? null;
$criteria=!$criteria?1:$criteria;



// Get the list of all surveys
$surveys = Specialsurvey::find()
    ->select('survey_name')
    ->distinct()
    ->orderBy(['survey_name' => SORT_ASC])
    ->column();

$surveyIndex = array_search($selectedSurvey, $surveys);
$previousSurvey = $surveyIndex > 0 ? $surveys[$surveyIndex - 1] : null;

// if (!$selectedSurvey) {
//     $selectedSurvey = $surveys[0];
// }
$selectedSurveyCondition = $selectedSurvey ? ['survey_name' => $selectedSurvey] : [];

// Get barangays based on the selected barangay or all barangays if none is selected
$barangaysQuery = Specialsurvey::find()
    ->select('barangay')
    ->distinct()
    ->orderBy(['barangay' => SORT_ASC]);

if ($selectedBarangay) {
    $barangaysQuery->andWhere(['barangay' => $selectedBarangay]);
}

$barangays = $barangaysQuery->column();
$series = ['gray' => [], 'blackX' => [], 'blackY' => [], 'blackU' => [], 'blue' => []];
$barangay_labels = [];

foreach ($barangays as $barangay) {

    $previousGrayVotersQuery = [];

    if ($previousSurvey) {
        $previousGrayVotersQuery = Specialsurvey::find()
            ->select(['last_name', 'first_name', 'middle_name', 'household_no', 'precinct_no', 'barangay',  'criteria'.$criteria.'_color_id'])
            ->where(['survey_name' => $previousSurvey]) // Ensure it's from the previous survey
            ->andWhere(['criteria'.$criteria.'_color_id' => 2, 'barangay' => $barangay])
            ->asArray()
            ->all();
    }
    

    $currentChangedVotersQuery = [];

    if ($previousSurvey) {
        $currentChangedVotersQuery = Specialsurvey::find()
            ->select(['last_name', 'first_name', 'middle_name', 'household_no', 'precinct_no', 'barangay',  'criteria'.$criteria.'_color_id'])
            ->where(['survey_name' => $selectedSurvey, 'barangay' => $barangay])
            ->andWhere(['in', 'household_no', ArrayHelper::getColumn($previousGrayVotersQuery, 'household_no')])
            ->andWhere(['in', 'last_name', ArrayHelper::getColumn($previousGrayVotersQuery, 'last_name')])
            ->andWhere(['in', 'first_name', ArrayHelper::getColumn($previousGrayVotersQuery, 'first_name')])
            ->andWhere(['in', 'middle_name', ArrayHelper::getColumn($previousGrayVotersQuery, 'middle_name')])
            ->andWhere(['in', 'precinct_no', ArrayHelper::getColumn($previousGrayVotersQuery, 'precinct_no')])
            ->andWhere(['<>', 'criteria'.$criteria.'_color_id', 2])
            ->andWhere(['barangay' => $barangay])
            ->asArray()
            ->all();
    }

    
    $currentGrayVotersQuery = Specialsurvey::find()
        ->where($selectedSurveyCondition)
        ->andWhere(['criteria'.$criteria.'_color_id' => 2, 'barangay' => $barangay])
        ->count();

    
    $barangay_labels[] = $barangay;
    $convertedCounts = [
     
        'blue' => 0,
        'blackX' => 0,
        'blackY' => 0,
        'blackU' => 0,
    ];
    
    foreach ($currentChangedVotersQuery as $matchedVoter) {
        switch ($matchedVoter['criteria'.$criteria.'_color_id']) {
            case 1:
                $convertedCounts['blue']++;
                break;
            case 3:
                $convertedCounts['blackX']++;
                break;
            case 4:
                $convertedCounts['blackY']++;
                break;
            case 5:
                $convertedCounts['blackU']++;
                break;
        }
    }
    
    $series['gray'][] = $currentGrayVotersQuery;
    $series['blue'][] = $convertedCounts['blue'];
    $series['blackX'][] = $convertedCounts['blackX'];
    $series['blackY'][] = $convertedCounts['blackY'];
    $series['blackU'][] = $convertedCounts['blackU'];
}

$chart_data = [
    ['name' => "Current Gray Voters", 'data' => $series['gray']],
    ['name' => "Converted to Blue", 'data' => $series['blue']],
    ['name' => "Converted to BlackX", 'data' => $series['blackX']],
    ['name' => "Converted to BlackY", 'data' => $series['blackY']],
    ['name' => "Converted to BlackU", 'data' => $series['blackU']],
];

$chart_data_json = json_encode($chart_data);
$barangay_labels_json = json_encode($barangay_labels);
$chartTitle = $selectedSurvey ? "Gray Voter Data and Conversions ({$selectedSurvey})" : "Gray Voter Data and Conversions (All Surveys)";

$this->registerWidgetJs('brgygraph', <<<JS
    let data = {$chart_data_json};
    let label = {$barangay_labels_json};
    var options = {
        series: data,
        chart: { toolbar: { show: false }, type: 'bar', height: 650, stacked: true },
        plotOptions: { bar: { horizontal: false } },
        stroke: { width: 1, colors: ['#fff'] },
        title: { 
            text: '{$chartTitle}',
            align: 'center', 
            style: { fontSize: '20px', fontWeight: 'bold' } 
        },
        xaxis: { categories: label },
        tooltip: { y: { formatter: function (val) { return val + " Voters"; } } },
        fill: { opacity: 1 },
        legend: { position: 'top', horizontalAlign: 'left', offsetX: 40 },
        colors: ['#e4e6ef', '#000000', '#5096f2', '#404040', '#808080'], // Gray, BlackX, Blue, BlackY, BlackU
    };
    var chart = new ApexCharts(document.querySelector("#chart-barangay"), options);
    chart.render();
JS);
?>
<div class="specialsurvey-index-page">
    <div class="mt-10"></div>
    <div id="chart-barangay"></div>
    <div class="row">

    
        <div class="col-md-12">
            <?= $this->render('voter_gray_purok_graph', ['queryParams' => $queryParams, 'criteria' => $criteria]) ?>
        </div>
    </div>
</div>
