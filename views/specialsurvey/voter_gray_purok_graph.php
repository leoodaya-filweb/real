<?php

use app\helpers\App;
use app\models\Specialsurvey;
use yii\web\View;

$criteria = !$criteria ? 1 : $criteria;
$barangay_name = $row['barangay'];

$selectedSurvey = $queryParams['survey_name'] ?? 'Survey 1';

if (!$selectedSurvey) {
    $selectedSurvey = "Survey 1";
}

$surveys = Specialsurvey::find()
    ->select('survey_name')
    ->distinct()
    ->orderBy(['survey_name' => SORT_ASC])
    ->column();

$surveyIndex = array_search($selectedSurvey, $surveys);
$previousSurvey = $surveyIndex > 0 ? $surveys[$surveyIndex - 1] : null;

$purokQuery = Specialsurvey::find()
    ->alias('t')
    ->select([ 
        't.purok', 
        'SUM(CASE WHEN t.survey_name = :selectedSurvey AND t.criteria' . $criteria . '_color_id = 2 THEN 1 ELSE 0 END) AS gray_voters', 
        'SUM(CASE WHEN t.survey_name = :previousSurvey AND t.criteria' . $criteria . '_color_id = 2 THEN 1 ELSE 0 END) AS previous_gray_voters'
    ])
    ->where(['t.barangay' => $barangay_name])
    ->andWhere(['not in', 't.purok', ['', '0', '-', ' - ']]) // Filter out invalid purok values
    ->groupBy('t.purok')
    ->orderBy(['t.purok' => SORT_ASC])
    ->params(['selectedSurvey' => $selectedSurvey, 'previousSurvey' => $previousSurvey]);

$purokData = $purokQuery->asArray()->all();
$purok_labels = [];
$series_data = ['name' => "Gray Voters ({$selectedSurvey})", 'data' => []];

$series_loss = [
    1 => ['name' => "Converted to Black", 'data' => []],
    3 => ['name' => "Converted to Green", 'data' => []],
    4 => ['name' => "Converted to Red", 'data' => []]
];

$lossBreakdown = [];

if ($previousSurvey) {
    $lossGrayQuery = Specialsurvey::find()
        ->alias('t')
        ->select(['t.purok', 't.criteria' . $criteria . '_color_id', 'COUNT(*) AS count'])
        ->where(['t.survey_name' => $selectedSurvey])
        ->andWhere(['not', ['t.criteria' . $criteria . '_color_id' => 2]]) 
        ->andWhere(['t.barangay' => $barangay_name])
        ->groupBy(['t.purok', 't.criteria' . $criteria . '_color_id'])
        ->asArray()
        ->all();

    foreach ($lossGrayQuery as $loss) {
        $lossBreakdown[$loss['purok']][$loss['criteria' . $criteria . '_color_id']] = (int) $loss['count'];
    }
}

foreach ($purokData as $row) {
    $purok_labels[] = $row['purok'];
    $gray_count = (int) $row['gray_voters'];

    $series_data['data'][] = $gray_count;

    if ($previousSurvey) {
        $prev_gray_count = (int) ($row['previous_gray_voters'] ?? 0);
        $total_lost_gray = max(0, $prev_gray_count - $gray_count);

        foreach ([1 => 'black', 3 => 'green', 4 => 'red'] as $criteriaId => $color) {
            $loss_count = $lossBreakdown[$row['purok']][$criteriaId] ?? 0;
            $loss_count = min($loss_count, $total_lost_gray);
            $series_loss[$criteriaId]['data'][] = $loss_count;
            $total_lost_gray -= $loss_count;
        }
    }
}

foreach ($series_loss as $criteriaId => &$data) {
    while (count($data['data']) < count($series_data['data'])) {
        $data['data'][] = 0;
    }
}

$final_series = [$series_data]; 

foreach ([1 => 'Black', 3 => 'Green', 4 => 'Red'] as $criteriaId => $color) {
    $final_series[] = $series_loss[$criteriaId] ?? ['name' => '', 'data' => []];
}

$chart_data_json = json_encode($final_series);

$purok_labels_json = json_encode($purok_labels);

$this->registerWidgetJs('purokGraph', <<<JS
    let data = {$chart_data_json}; 
    let label = {$purok_labels_json}; 
    
    var options = {
        series: data,
        chart: { 
            toolbar: { show: false }, 
            type: 'bar', 
            height: 650, 
            stacked: true 
        },
        plotOptions: { bar: { horizontal: true } }, // Make bars horizontal
        stroke: { width: 1, colors: ['#fff'] },
        title: { 
            text: '{$barangay_name} Voters Color Per Purok', 
            align: 'left', 
            style: { fontSize: '16px', fontWeight: 'bold' } 
        },
        xaxis: { categories: label },
        tooltip: { 
            y: { 
                formatter: function (val) { return val + ' Voters'; } 
            } 
        },
        fill: { opacity: 1 },
        legend: { 
            position: 'top', 
            horizontalAlign: 'left', 
            offsetX: 40 
        },
        colors: ['#e4e6ef', '#181c32', '#1bc5bd', '#f64e60'], // Gray, Black, Green, Red
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
