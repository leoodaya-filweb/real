<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Specialsurvey;
use app\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SpecialsurveySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$queryParams = App::queryParams();


if (isset($queryParams['criteria1_color_id'])) {
    unset($queryParams['criteria1_color_id']);
    $criteria = $criteria ?: 1;
}
if (isset($queryParams['criteria2_color_id'])) {
    unset($queryParams['criteria2_color_id']);
    $criteria = $criteria ?: 2;
}
if (isset($queryParams['criteria3_color_id'])) {
    unset($queryParams['criteria3_color_id']);
    $criteria = $criteria ?: 3;
}
if (isset($queryParams['criteria4_color_id'])) {
    unset($queryParams['criteria4_color_id']);
    $criteria = $criteria ?: 4;
}
if (isset($queryParams['criteria5_color_id'])) {
    unset($queryParams['criteria5_color_id']);
    $criteria = $criteria ?: 5;
}

$criteria = $criteria ?: 1;
$color_survey = $queryParams['color_survey'];

if($color_survey){
    $color_survey = explode(',', $color_survey);
}


// $barangay = trim($queryParams['barangay'] ?? '');
// $purok = trim($queryParams['purok'] ?? '');
// $surveyName = trim($queryParams['survey_name'] ?? '');
// $colorSurvey = !empty($queryParams['color_survey']) ? explode(',', $queryParams['color_survey']) : [];


$query = (new \yii\db\Query())
    ->select([
        'b.name AS barangay_name',
        'COUNT(s.id) AS total_surveys',
        'SUM(CASE WHEN m.voter = 1 THEN 1 ELSE 0 END) AS registered_voters',
        'SUM(CASE WHEN m.voter IN (0, 2) THEN 1 ELSE 0 END) AS unregistered_voters'
    ])
    ->from(['s' => 'tbl_specialsurvey'])
    ->innerJoin(['m' => 'tbl_members'],
        'LOWER(TRIM(m.last_name)) = LOWER(TRIM(s.last_name)) AND ' .
        'LOWER(TRIM(m.first_name)) = LOWER(TRIM(s.first_name)) AND ' .
        'LOWER(TRIM(m.middle_name)) = LOWER(TRIM(s.middle_name))'
    )
    ->innerJoin(['h' => 'tbl_households'], 'm.household_id = h.id')
    ->innerJoin(['b' => 'tbl_barangays'], 'h.barangay_id = b.no')
    ->where(['m.voter' => [0, 1, 2]])
    ->andFilterWhere(['s.barangay' => $queryParams['barangay']])
    ->andFilterWhere(['s.purok' => $queryParams['purok']])
    ->andFilterWhere(['s.survey_name' => $queryParams['survey_name']])
    ->andFilterWhere(['s.criteria' . $criteria . '_color_id' => $color_survey]);

if (empty($queryParams['barangay'])) {
    $query->groupBy(['b.name'])
          ->orderBy(['b.name' => SORT_ASC]);
}

$data = $query->all();




$barangayLabels = [];
$registeredData = [];
$unregisteredData = [];

foreach ($data as $row) {
    $barangayLabels[] = $row['barangay_name'];
    $registeredData[] = (int)$row['registered_voters'];
    $unregisteredData[] = (int)$row['unregistered_voters'];
}

$chartData = [
    ['name' => 'Registered Voters', 'data' => $registeredData],
    ['name' => 'Unregistered Voters', 'data' => $unregisteredData]
];


$chartData = json_encode($chartData);
$barangayLabels = json_encode($barangayLabels);


$this->registerJs(<<<JS

    $(document).ready(function () {
        renderChart();
    });


    function renderChart(){
        const data ={$chartData};
        const label = {$barangayLabels};
        // let label = <?= Json::encode($barangayLabels) ?>;
        // let data = <?= Json::encode($chartData) ?>;
    
      
        

        let options = {
            series: data,
            chart: {    
                type: 'bar',
                height: 650,
                stacked: true,
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    borderRadius: 5
                }
            },
            stroke: { width: 1, colors: ['#fff'] },
            title: {
                text: 'Registered Vs Unregistered Voters',
                align: 'center',
                style: {
                    fontSize: '26px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            },
            xaxis: {
                categories: label,
                labels: {
                    style: { fontSize: '14px', colors: '#333' }
                },
                axisBorder: { show: true, color: '#ddd' },
                axisTicks: { show: true, color: '#ddd' }
            },
            yaxis: {
                labels: {
                    style: { fontSize: '13px', colors: '#555' }
                },
                title: {
                    text: 'Number of Voters',
                    style: { fontSize: '16px', fontWeight: 'bold', color: '#333' }
                }
            },
            tooltip: {
                theme: 'dark',
                y: { formatter: function (val) { return val + " Voters"; } }
            },
            fill: { opacity: 0.9 },
            grid: {
                borderColor: '#e0e0e0',
                strokeDashArray: 4,
                padding: { left: 10, right: 10, top: 20, bottom: 20 }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '14px',
                markers: { radius: 4 },
                labels: { colors: '#333' }
            },
            colors: ['#1B98E0', '#D72638'],
            dataLabels: {
                enabled: true,
                style: { fontSize: '13px', fontWeight: 'bold', colors: ['#fff'] },
                formatter: function (val, opts) {
                    return val;
                }
            }
        };

        document.querySelector("#registered-vs-unregistered-graph").innerHTML = "";

        var chart = new ApexCharts(document.querySelector("#registered-vs-unregistered-graph"), options);
        chart.render();
    }
    
    renderChart();

JS);


?>


<div class="specialsurvey-index-page" >
    
    <div class="mt-10"></div>
     <hr/>
    <div id="registered-vs-unregistered-graph"></div>
   
</div>
