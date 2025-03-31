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

// Get the list of all surveys
$surveys = Specialsurvey::find()
    ->select('survey_name')
    ->distinct()
    ->orderBy(['survey_name' => SORT_ASC])
    ->column();

// Get the index of the current survey
$surveyIndex = array_search($selectedSurvey, $surveys);
$previousSurvey = $surveyIndex > 0 ? $surveys[$surveyIndex - 1] : null;

// Step 1: Get the voters from the previous survey who are gray (criteria1_color_id = 2)
$previousGrayVotersQuery = Specialsurvey::find()
    ->select(['last_name', 'first_name', 'middle_name', 'household_no', 'precinct_no', 'barangay', 'criteria1_color_id'])
    ->where(['survey_name' => $previousSurvey]) // Previous survey
    ->andWhere(['criteria1_color_id' => 2]) // Only gray voters
    ->asArray()
    ->all();

// Step 2: Get current voters who changed their criteria1_color_id from 2 to any other color
$currentChangedVotersQuery = Specialsurvey::find()
    ->select(['last_name', 'first_name', 'middle_name', 'household_no', 'precinct_no', 'barangay', 'criteria1_color_id'])
    ->where(['survey_name' => $selectedSurvey]) // Current survey
    ->andWhere(['in', 'household_no', ArrayHelper::getColumn($previousGrayVotersQuery, 'household_no')]) // Only from households with gray voters in previous survey
    ->andWhere(['<>', 'criteria1_color_id', 2]) // Exclude gray voters
    ->asArray()
    ->all();

// Step 3: Get current gray voters who didn't change their criteria1_color_id (still gray)
$currentGrayVotersQuery = Specialsurvey::find()
    ->select(['last_name', 'first_name', 'middle_name', 'household_no', 'precinct_no', 'barangay', 'criteria1_color_id'])
    ->where(['survey_name' => $selectedSurvey]) // Current survey
    ->andWhere(['criteria1_color_id' => 2]) // Only gray voters
    ->asArray()
    ->all();

// Debugging output for current gray voters
Yii::debug("Current Gray Voters Query:", 'debug');
Yii::debug($currentGrayVotersQuery, 'debug');

// Step 4: Prepare the data for the graph
$barangay_labels = [];
$series = [
    'gray' => [],
    'black' => [],
    'green' => [],
    'red' => []
];

// Step 5: Process gray voters from the previous survey and count them
foreach ($previousGrayVotersQuery as $row) {
    // Match the voter by name and household_no
    $matchedVoters = array_filter($currentChangedVotersQuery, function($currentVoter) use ($row) {
        return $currentVoter['last_name'] === $row['last_name']
            && $currentVoter['first_name'] === $row['first_name']
            && $currentVoter['middle_name'] === $row['middle_name']
            && $currentVoter['household_no'] === $row['household_no']
            && $currentVoter['precinct_no'] === $row['precinct_no'];
    });

    // If a match is found, process the converted voter
    if (!empty($matchedVoters)) {
        foreach ($matchedVoters as $matchedVoter) {
            // Add barangay to labels only once
            if (!in_array($row['barangay'], $barangay_labels)) {
                $barangay_labels[] = $row['barangay'];
            }

            // Count the number of converted voters per color
            switch ($matchedVoter['criteria1_color_id']) {
                case 1: // Black voters
                    if (!isset($series['black'][$row['barangay']])) {
                        $series['black'][$row['barangay']] = 0;
                    }
                    $series['black'][$row['barangay']]++;
                    break;
                case 3: // Green voters
                    if (!isset($series['green'][$row['barangay']])) {
                        $series['green'][$row['barangay']] = 0;
                    }
                    $series['green'][$row['barangay']]++;
                    break;
                case 4: // Red voters
                    if (!isset($series['red'][$row['barangay']])) {
                        $series['red'][$row['barangay']] = 0;
                    }
                    $series['red'][$row['barangay']]++;
                    break;
            }
        }
    } else {
        // If the gray voter did not change color, add them to gray
        if (!isset($series['gray'][$row['barangay']])) {
            $series['gray'][$row['barangay']] = 0;
        }
        // $series['gray'][$row['barangay']]++;
    }
}

// Step 6: Add current gray voters (those who haven't changed) to the series
foreach ($currentGrayVotersQuery as $row) {
    // Ensure barangay is included in labels, but do it only once
    if (!in_array($row['barangay'], $barangay_labels)) {
        $barangay_labels[] = $row['barangay'];
    }

    // Ensure the current gray voter is counted only once per barangay
    if (!isset($series['gray'][$row['barangay']])) {
        $series['gray'][$row['barangay']] = 0;
    }
    $series['gray'][$row['barangay']]++;

    // Debugging output for current gray counts
    Yii::debug("Adding to current gray series: " . json_encode($row), 'debug');
}

// Step 7: Prepare the data to pass to the chart
$chart_data = [];
foreach ($barangay_labels as $barangay) {
    $chart_data[] = [
        'name' => "Current Gray Voters",
        'data' => isset($series['gray'][$barangay]) ? [$series['gray'][$barangay]] : [0]
    ];

    $chart_data[] = [
        'name' => "Converted to Black",
        'data' => isset($series['black'][$barangay]) ? [$series['black'][$barangay]] : [0]
    ];

    $chart_data[] = [
        'name' => "Converted to Green",
        'data' => isset($series['green'][$barangay]) ? [$series['green'][$barangay]] : [0]
    ];

    $chart_data[] = [
        'name' => "Converted to Red",
        'data' => isset($series['red'][$barangay]) ? [$series['red'][$barangay]] : [0]
    ];
}

// Prepare barangay data for passing to the voter_gray_purok_graph view
$barangayData = [];
foreach ($barangay_labels as $barangay) {
    $barangayData[] = [
        'barangay' => $barangay,
        'gray' => $series['gray'][$barangay] ?? 0,
        'black' => $series['black'][$barangay] ?? 0,
        'green' => $series['green'][$barangay] ?? 0,
        'red' => $series['red'][$barangay] ?? 0,
    ];
}



$chart_data_json = json_encode($chart_data);
$barangay_labels_json = json_encode($barangay_labels);
$chartTitle = "Gray Voter Data and Conversions ({$selectedSurvey})";

// Step 8: Register the widget for rendering the chart
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
        <?php foreach ($barangayData as $key => $row) { ?>
            <div class="col-md-6">
                <?= $this->render('voter_gray_purok_graph', ['row' => $row, 'id' => $key + 1, 'queryParams' => $queryParams]) ?>
            </div>
        <?php } ?>
    </div>
</div>
