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
$criteria=!$criteria?1:$criteria;

$selectedSurveyCondition = $selectedSurvey ? ['survey_name' => $selectedSurvey] : [];

$surveyIndex = array_search($selectedSurvey, $surveys);
$previousSurvey = $surveyIndex > 0 ? $surveys[$surveyIndex - 1] : null;

// Fetch data for the previous survey if available
$previousGrayVotersQuery = [];
if ($previousSurvey !== null) {
    $previousGrayVotersQuery = Specialsurvey::find()
        ->alias('t')
        ->select(['last_name', 'first_name', 'middle_name', 'household_no', 'precinct_no', 'purok', 'criteria'.$criteria.'_color_id'])
        ->where(['survey_name' => $previousSurvey])
        ->andWhere(['criteria'.$criteria.'_color_id' => 2]) // Only gray voters
        ->andFilterWhere(['barangay' => $selectedBarangay])
        ->asArray()
        ->all();
}

// Query for current changed voters, handling the case if no barangay is selected
$currentChangedVotersQuery = Specialsurvey::find()
    ->select(['last_name', 'first_name', 'middle_name', 'household_no', 'precinct_no', 'purok', 'criteria'.$criteria.'_color_id', 'barangay'])
    ->where(['survey_name' => $selectedSurvey])
    ->andWhere(['in', 'household_no', ArrayHelper::getColumn($previousGrayVotersQuery, 'household_no')])
    ->andWhere(['<>', 'criteria'.$criteria.'_color_id', 2]) // Changed from gray
    ->andFilterWhere(['barangay' => $selectedBarangay ?: null])
    ->asArray()
    ->all();

// Query for current gray voters, handling the case if no barangay is selected
$currentGrayVotersQuery = Specialsurvey::find()
    ->select(['purok', 'criteria'.$criteria.'_color_id', 'barangay'])
    ->where($selectedSurveyCondition)
    ->andWhere(['criteria'.$criteria.'_color_id' => 2]) // Only gray voters
    ->andFilterWhere(['barangay' => $selectedBarangay ?: null])
    ->asArray()
    ->all();

$series = ['gray' => [], 'black' => [], 'green' => [], 'red' => []];
$barangays = [];

// Sanitize and process the data for each query result
foreach ($currentChangedVotersQuery as $row) {
    if (!in_array($row['barangay'], $barangays)) {
        $barangays[] = $row['barangay'];
    }

    switch ($row['criteria'.$criteria.'_color_id']) {
        case 1: $series['black'][$row['barangay']][$row['purok']] = ($series['black'][$row['barangay']][$row['purok']] ?? 0) + 1; break;
        case 3: $series['green'][$row['barangay']][$row['purok']] = ($series['green'][$row['barangay']][$row['purok']] ?? 0) + 1; break;
        case 4: $series['red'][$row['barangay']][$row['purok']] = ($series['red'][$row['barangay']][$row['purok']] ?? 0) + 1; break;
    }
}

foreach ($currentGrayVotersQuery as $row) {
    if (!in_array($row['barangay'], $barangays)) {
        $barangays[] = $row['barangay'];
    }

    $series['gray'][$row['barangay']][$row['purok']] = ($series['gray'][$row['barangay']][$row['purok']] ?? 0) + 1;
}

// Prepare chart data for each barangay
$chart_data = [];
$barangays = array_unique($barangays);
sort($barangays);
foreach ($barangays as $barangay) {
    // Merge the arrays for gray, black, green, and red voters for this barangay
    $purok_labels = array_keys(array_merge(
        $series['gray'][$barangay] ?? [],
        $series['black'][$barangay] ?? [],
        $series['green'][$barangay] ?? [],
        $series['red'][$barangay] ?? []
    ));

    $barangayChartData = [
        ['name' => "Gray Voters", 'data' => []],
        ['name' => "Converted to Black", 'data' => []],
        ['name' => "Converted to Green", 'data' => []],
        ['name' => "Converted to Red", 'data' => []]
    ];

    foreach ($purok_labels as $purok) {
        $barangayChartData[0]['data'][] = $series['gray'][$barangay][$purok] ?? 0;
        $barangayChartData[1]['data'][] = $series['black'][$barangay][$purok] ?? 0;
        $barangayChartData[2]['data'][] = $series['green'][$barangay][$purok] ?? 0;
        $barangayChartData[3]['data'][] = $series['red'][$barangay][$purok] ?? 0;
    }

    $chart_data[$barangay] = json_encode($barangayChartData);
    $purok_labels_json = json_encode($purok_labels);

    // Sanitize the barangay name for safe use as a JS variable and element ID
    $safeBarangayName = str_replace([' ', '-', '.', ',', '(', ')', '\\'], '_', $barangay);

    $this->registerWidgetJs("purokGraph" . $safeBarangayName, <<<JS
        let data = {$chart_data[$barangay]};
        let label = {$purok_labels_json};
        
        var options = {
            series: data,
            chart: { type: 'bar', height: 650, stacked: true },
            plotOptions: { bar: { horizontal: true} },
            stroke: { width: 1, colors: ['#fff'] },
            title: { text: 'Purok Voters Color Breakdown for {$barangay}', align: 'left', style: { fontSize: '16px', fontWeight: 'bold' } },
            xaxis: { categories: label },
            tooltip: { y: { formatter: function (val) { return val + ' Voters'; } } },
            fill: { opacity: 1 },
            legend: { position: 'top', horizontalAlign: 'left', offsetX: 40 },
            colors: ['#e4e6ef', '#181c32', '#1bc5bd', '#f64e60'],
        };
        
        var chart = new ApexCharts(document.querySelector("#chart-purok{$safeBarangayName}"), options);
        chart.render();
    JS);

    $this->registerCss(<<< CSS
        
        /* Ensures that the row wraps the items properly */
        .specialsurvey-index-page .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0;  /* Remove extra margin from row */
        }

        /* Define columns to be 50% on medium screens and full-width on mobile */
        .specialsurvey-index-page .col-md-6 {
            width: 50%; /* Two charts per row */
            padding: 0 10px; /* Small padding to separate the charts */
            box-sizing: border-box;
        }

        /* Full width for smaller screens */
        @media (max-width: 767px) {
            .specialsurvey-index-page .col-md-6 {
                width: 100%; /* Full width for smaller screens */
                padding: 0;
            }
        }

        /* Make sure the chart container is full width of the column */
        .specialsurvey-index-page .chart-container {
            width: 100%; /* Ensure chart container takes the full width */
            height: 650px; /* You can adjust this height as needed */
            margin-bottom: 20px; /* Space between the charts */
        }

        /* Optional: if you're facing issues with layout due to other styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        
    CSS, ['type' => "text/css"]);



}

?>

<div class="specialsurvey-index-page">
    <div class="mt-10"></div>
    
    <div class="row">
        <?php foreach ($barangays as $barangay): ?>
           
            <div class="col-12 col-md-6 mb-4"> <!-- Flex column taking 50% of the row on medium screens -->
                <hr/>
                <div id="chart-purok<?= str_replace([' ', '-', '.', ',', '(', ')', '\\'], '_', $barangay) ?>" class="chart-container"></div>
            
            </div>
        <?php endforeach; ?>
    </div>
</div>

