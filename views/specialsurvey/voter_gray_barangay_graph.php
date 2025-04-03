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

if (!$selectedSurvey) {
    $selectedSurvey = "Survey 1";
}

if (!$selectedBarangay) {
    return; // Do not display anything if no barangay is selected
}

// Get the list of all surveys
$surveys = Specialsurvey::find()
    ->select('survey_name')
    ->distinct()
    ->orderBy(['survey_name' => SORT_ASC])
    ->column();

$surveyIndex = array_search($selectedSurvey, $surveys);
$previousSurvey = $surveyIndex > 0 ? $surveys[$surveyIndex - 1] : null;

$previousGrayVotersQuery = Specialsurvey::find()
    ->select(['last_name', 'first_name', 'middle_name', 'household_no', 'precinct_no', 'barangay', 'criteria1_color_id'])
    ->where(['survey_name' => $previousSurvey]) // Previous survey
    ->andWhere(['criteria1_color_id' => 2]) // Only gray voters
    ->andWhere(['barangay'=> $selectedBarangay])
    ->asArray()
    ->all();

$currentChangedVotersQuery = Specialsurvey::find()
    ->select(['last_name', 'first_name', 'middle_name', 'household_no', 'precinct_no', 'barangay', 'criteria1_color_id'])
    ->where(['survey_name' => $selectedSurvey]) 
    ->andWhere(['in', 'household_no', ArrayHelper::getColumn($previousGrayVotersQuery, 'household_no')]) // Only from households with gray voters in previous survey
    ->andWhere(['<>', 'criteria1_color_id', 2])
    ->andWhere(['barangay' => $selectedBarangay])
    ->asArray()
    ->all();

$currentGrayVotersQuery = Specialsurvey::find()
    ->select(['last_name', 'first_name', 'middle_name', 'household_no', 'precinct_no', 'barangay', 'criteria1_color_id'])
    ->where(['survey_name' => $selectedSurvey]) 
    ->andWhere(['criteria1_color_id' => 2]) 
    ->andWhere(['barangay' => $selectedBarangay])
    ->asArray()
    ->all();

$barangay_labels = [$selectedBarangay];
$series = ['gray' => 0, 'black' => 0, 'green' => 0, 'red' => 0];

foreach ($previousGrayVotersQuery as $row) {
    $matchedVoters = array_filter($currentChangedVotersQuery, function($currentVoter) use ($row) {
        return $currentVoter['household_no'] === $row['household_no'];
    });

    if (!empty($matchedVoters)) {
        foreach ($matchedVoters as $matchedVoter) {
            switch ($matchedVoter['criteria1_color_id']) {
                case 1: // Black voters
                    $series['black']++;
                    break;
                case 3: // Green voters
                    $series['green']++;
                    break;
                case 4: // Red voters
                    $series['red']++;
                    break;
            }
        }
    }
}

$series['gray'] = count($currentGrayVotersQuery);

$chart_data = [
    ['name' => "Current Gray Voters", 'data' => [$series['gray']]],
    ['name' => "Converted to Black", 'data' => [$series['black']]],
    ['name' => "Converted to Green", 'data' => [$series['green']]],
    ['name' => "Converted to Red", 'data' => [$series['red']]],
];

$chart_data_json = json_encode($chart_data);
$barangay_labels_json = json_encode($barangay_labels);
$chartTitle = "Gray Voter Data and Conversions ({$selectedSurvey})";

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
        colors: ['#e4e6ef', '#181c32', '#1bc5bd', '#f64e60'] // Gray, Black, Green, Red
    };
    var chart = new ApexCharts(document.querySelector("#chart-barangay"), options);
    chart.render();
JS);
?>
<div class="specialsurvey-index-page">
    <div class="mt-10"></div>
    <div id="chart-barangay"></div>
    <div class="row">
        <div class="col-md-6">
            <?= $this->render('voter_gray_purok_graph', ['queryParams' => $queryParams]) ?>
        </div>
    </div>
</div>
