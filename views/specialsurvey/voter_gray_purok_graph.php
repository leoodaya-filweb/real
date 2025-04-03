<?php

use app\helpers\App;
use app\models\Specialsurvey;
use yii\helpers\ArrayHelper;

// Get the list of all surveys
$surveys = Specialsurvey::find()
    ->select('survey_name')
    ->distinct()
    ->orderBy(['survey_name' => SORT_ASC])
    ->column();

$queryParams = App::queryParams();
$selectedSurvey = $queryParams['survey_name'] ?? null;
$selectedBarangay = $queryParams['barangay'] ?? null;

if (!$selectedSurvey) {
    $selectedSurvey = "Survey 1";
}

$surveyIndex = array_search($selectedSurvey, $surveys);
$previousSurvey = $surveyIndex > 0 ? $surveys[$surveyIndex - 1] : null;

$previousGrayVotersQuery = [];
if ($previousSurvey !== null) {
    $previousGrayVotersQuery = Specialsurvey::find()
        ->alias('t')
        ->select(['last_name', 'first_name', 'middle_name', 'household_no', 'precinct_no', 'purok', 'criteria1_color_id'])
        ->where(['survey_name' => $previousSurvey])
        ->andWhere(['criteria1_color_id' => 2]) // Only gray voters
        ->andFilterWhere(['barangay' => $selectedBarangay])
        ->asArray()
        ->all();
}

$currentChangedVotersQuery = Specialsurvey::find()
    ->select(['last_name', 'first_name', 'middle_name', 'household_no', 'precinct_no', 'purok', 'criteria1_color_id'])
    ->where(['survey_name' => $selectedSurvey])
    ->andWhere(['in', 'household_no', ArrayHelper::getColumn($previousGrayVotersQuery, 'household_no')])
    ->andWhere(['<>', 'criteria1_color_id', 2])
    ->andFilterWhere(['barangay' => $selectedBarangay])
    ->asArray()
    ->all();

$currentGrayVotersQuery = Specialsurvey::find()
    ->select(['purok', 'criteria1_color_id'])
    ->where(['survey_name' => $selectedSurvey])
    ->andWhere(['criteria1_color_id' => 2])
    ->andFilterWhere(['barangay' => $selectedBarangay])
    ->asArray()
    ->all();

$purok_labels = [];
$series = ['gray' => [], 'black' => [], 'green' => [], 'red' => []];

foreach ($currentChangedVotersQuery as $row) {
    if (!in_array($row['purok'], $purok_labels)) {
        $purok_labels[] = $row['purok'];
    }

    switch ($row['criteria1_color_id']) {
        case 1: $series['black'][$row['purok']] = ($series['black'][$row['purok']] ?? 0) + 1; break;
        case 3: $series['green'][$row['purok']] = ($series['green'][$row['purok']] ?? 0) + 1; break;
        case 4: $series['red'][$row['purok']] = ($series['red'][$row['purok']] ?? 0) + 1; break;
    }
}

foreach ($currentGrayVotersQuery as $row) {
    if (!in_array($row['purok'], $purok_labels)) {
        $purok_labels[] = $row['purok'];
    }
    $series['gray'][$row['purok']] = ($series['gray'][$row['purok']] ?? 0) + 1;
}

$chart_data = [
    ['name' => "Gray Voters", 'data' => []],
    ['name' => "Converted to Black", 'data' => []],
    ['name' => "Converted to Green", 'data' => []],
    ['name' => "Converted to Red", 'data' => []]
];

foreach ($purok_labels as $purok) {
    $chart_data[0]['data'][] = $series['gray'][$purok] ?? 0;
    $chart_data[1]['data'][] = $series['black'][$purok] ?? 0;
    $chart_data[2]['data'][] = $series['green'][$purok] ?? 0;
    $chart_data[3]['data'][] = $series['red'][$purok] ?? 0;
}

$chart_data_json = json_encode($chart_data);
$purok_labels_json = json_encode($purok_labels);

$this->registerWidgetJs('purokGraph', <<<JS
    let data = {$chart_data_json}; 
    let label = {$purok_labels_json}; 
    
    var options = {
        series: data,
        chart: { type: 'bar', height: 650, stacked: true },
        plotOptions: { bar: { horizontal: true, columnWidth: '50%' } },
        stroke: { width: 1, colors: ['#fff'] },
        title: { text: 'Purok Voters Color Breakdown', align: 'left', style: { fontSize: '16px', fontWeight: 'bold' } },
        xaxis: { categories: label },
        tooltip: { y: { formatter: function (val) { return val + ' Voters'; } } },
        fill: { opacity: 1 },
        legend: { position: 'top', horizontalAlign: 'left', offsetX: 40 },
        colors: ['#e4e6ef', '#181c32', '#1bc5bd', '#f64e60'],
    };
    
    var chart = new ApexCharts(document.querySelector("#chart-purok{$id}"), options);
    chart.render();
JS);
?>

<div class="specialsurvey-index-page">
    <div class="mt-10"></div>
    <hr/>
    <div id="chart-purok<?=$id?>"></div>
</div>
