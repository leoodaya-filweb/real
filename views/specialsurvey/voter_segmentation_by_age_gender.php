<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Specialsurvey;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SpecialsurveySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $ageSegmentationData array */

$this->title = 'Voter Segmentation By Age and Genders';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('https://cdn.jsdelivr.net/npm/apexcharts@3.32.1/dist/apexcharts.min.js', [
    'position' => View::POS_HEAD,
]);

// Extract the unique age range values from the provided data (ageSegmentationData)
$ageRanges = array_map(fn($item) => $item['ageRange'], $ageSegmentationData);

// Initialize data arrays for male and female counts
$maleData = [];
$femaleData = [];

// Process data for each age range in the original ageSegmentationData
foreach ($ageSegmentationData as $item) {
    // Negative values for male counts to make them appear on the left
    $maleData[] = -(int)($item['maleCount'] ?? 0); // Cast to int to avoid any unexpected string behavior
    // Positive values for female counts to make them appear on the right
    $femaleData[] = (int)($item['femaleCount'] ?? 0); // Cast to int to ensure proper behavior
}

// Convert to JSON for JavaScript
$encodedAgeLabels = json_encode($ageRanges);
$encodedMaleData = json_encode($maleData);
$encodedFemaleData = json_encode($femaleData);

$this->registerJs(<<<JS

function renderChart() {
    const ageLabels = $encodedAgeLabels;
    const maleCounts = $encodedMaleData;
    const femaleCounts = $encodedFemaleData;

    var options = {
        series: [
            {
                name: 'Male',
                data: maleCounts // Keep male data as positive values
            },
            {
                name: 'Female',
                data: femaleCounts // Keep female data as positive values
            }
        ],
        chart: {
            type: 'bar',
            height: 440,
            stacked: true, // Stacked bars so both bars (Male & Female) appear together
            toolbar: {
                show: false // Hide toolbar for cleaner UI
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 1000,
                animateGradually: {
                    enabled: true,
                    delay: 200
                }
            }
        },
        colors: ['#008FFB', '#FF4560'], // Use clean and modern colors (Blue for Male, Red for Female)
        plotOptions: {
            bar: {
                horizontal: true, // Horizontal bars for better space utilization
                barHeight: '80%', // Adjust bar height for a sleek look
                borderRadius: 10, // Rounded corners for a modern look
                borderRadiusWhenStacked: 'all', // Apply rounding to all stacked bars
                distributed: true, // More even bar distribution
            },
        },
        dataLabels: {
            enabled: false // Disable data labels for a cleaner look
        },
        stroke: {
            width: 1,
            colors: ["#fff"] // White border for better contrast
        },
        grid: {
            xaxis: {
                lines: {
                    show: false // Hide vertical grid lines for a cleaner design
                }
            },
            yaxis: {
                lines: {
                    show: false // Hide horizontal grid lines for a cleaner design
                }
            }
        },
        tooltip: {
            shared: false, // Tooltip for each individual bar
            x: {
                formatter: function (val) {
                    return val; // Tooltip for X-axis category
                }
            },
            y: {
                formatter: function (val) {
                    return Math.abs(val) + " voters"; // Display absolute value as "voters"
                }
            },
            style: {
                fontSize: '14px',
                fontFamily: 'Arial, Helvetica, sans-serif',
                fontWeight: 'bold',
            }
        },
        
        xaxis: {
            categories: ageLabels, // Dynamically set labels based on age ranges
            title: {
                text: 'Number of Voters',
                style: {
                    color: '#333',
                    fontSize: '14px',
                    fontWeight: 600,
                    fontFamily: 'Arial, Helvetica, sans-serif'
                }
            },
            labels: {
                formatter: function (val) {
                    return Math.abs(Math.round(val)); // Show rounded values
                },
                style: {
                    fontSize: '12px',
                    fontFamily: 'Arial, Helvetica, sans-serif',
                    color: '#333'
                }
            },
            offsetY: 10, // Adding space between X-axis title and X-axis labels
        },
        yaxis: {
            title: {
                text: 'Age Group',
                style: {
                    color: '#333',
                    fontSize: '14px',
                    fontWeight: 600,
                    fontFamily: 'Arial, Helvetica, sans-serif'
                },
                offsetX: 10, // Add horizontal padding
                offsetY: 10  // Add vertical padding
            },
            labels: {
                style: {
                    fontSize: '12px',
                    fontFamily: 'Arial, Helvetica, sans-serif',
                    color: '#333'
                }
            },
            offsetX: 10, // Adding space between Y-axis title and Y-axis labels
        },
        legend: {
            position: 'top', // Move the legend to the top
            horizontalAlign: 'center', // Align the legend horizontally at the top
            fontSize: '20px',
        },
        responsive: [{
            breakpoint: 768,
            options: {
                chart: {
                    height: 400
                },
                xaxis: {
                    labels: {
                        fontSize: '10px'
                    }
                },
                yaxis: {
                    labels: {
                        fontSize: '10px'
                    }
                },
                legend: {
                    position: 'bottom', // Move the legend to the bottom on smaller screens
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#ageSegmentationChart"), options);
    chart.render();
}



    renderChart();
JS);
?>

<section class="mt-5 new-map" style="position: relative;">
    <div class="d-flex align-items-center">
        <div>
            <p class="lead font-weight-bold mb-0">Filters: </p>
        </div>
        <div class="ml-5">
            <select class="form-control" id="select-barangay">
                <?= Html::tag('option', 'All Barangay', ['value' => '']) ?>
                <?php foreach (Specialsurvey::filter('barangay') as $name): ?>
                    <?= Html::tag('option', $name, [
                        'value' => $name,
                        'selected' => trim($searchModel->barangay) == trim($name) ? true : false
                    ]) ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="ml-5">
            <select class="form-control" id="select-purok">
                <?= Html::tag('option', 'All Purok', ['value' => '']) ?>
                <?php foreach (Specialsurvey::filter('purok') as $name): ?>
                    <?= Html::tag('option', $name, [
                        'value' => $name,
                        'selected' => trim($searchModel->purok) == trim($name) ? true : false
                    ]) ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="ml-5">
            <select class="form-control" id="select-criteria">
                <?php foreach ([1, 2, 3, 4, 5] as $n): ?>
                    <?= Html::tag('option', "Criteria {$n}", [
                        'value' => $n,
                        'selected' => App::get("criteria{$n}_color_id") ? true : false
                    ]) ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</section>


<div class="mt-10">
<h2>Gender Segmentation</h2>
</div>

<!-- Chart Section -->
<div id="ageSegmentationChart" style="height: 500px; width: 100%;"></div>



<h2>Gender Segmentation</h2>
<div id="genderSegmentationChart" style="height: 500px;"></div>


