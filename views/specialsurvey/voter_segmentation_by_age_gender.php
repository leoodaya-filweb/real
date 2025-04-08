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


    $ageRanges = array_map(fn($item) => $item['age_range'], $ageSegmentationData);

    $maleData = [];
    $femaleData = [];

    foreach ($ageSegmentationData as $item) {
        // Negative values for male counts to make them appear on the left
        $maleData[] = -(int)($item['male_count'] ?? 0); // Cast to int to avoid any unexpected string behavior
        // Positive values for female counts to make them appear on the right
        $femaleData[] = (int)($item['female_count'] ?? 0); // Cast to int to ensure proper behavior
    }

    $encodedAgeLabels = json_encode($ageRanges);
    $encodedMaleData = json_encode($maleData);
    $encodedFemaleData = json_encode($femaleData);

    $this->registerJs(<<<JS
         
        $(document).ready(function () {
           
            const ageLabels = $encodedAgeLabels;
            const maleCounts = $encodedMaleData;
            const femaleCounts = $encodedFemaleData;

           
            
            renderChart(ageLabels,maleCounts, femaleCounts);

            const maleCount = $encodedMaleData.reduce((acc, val) => acc + Math.abs(val), 0); // Summing absolute male data
            const femaleCount = $encodedFemaleData.reduce((acc, val) => acc + val, 0); // Summing female data

            
            renderDoughnutChart(maleCount,femaleCount);

            $('.filter-select').change(function () {

                var barangay = $('#select-barangay').val();
                var purok = $('#select-purok').val();
                var criteria = $('#select-criteria').val();
                var color = $('#color-select').val();

              

                $.ajax({
                    url: '/real/web/specialsurvey/voter-segmentation-by-age-and-genders',
                    method: 'get',
                    data: { barangay, purok, criteria, color },
                    success: function (response) {
                        
                        
                        // Process Age Segmentation Data
                        let ageLabels = response.ageSegmentationData.map(item => item.age_range);
                        let maleCounts = response.ageSegmentationData.map(item => -parseInt(item.male_count || 0)); // Negative for left
                        let femaleCounts = response.ageSegmentationData.map(item => parseInt(item.female_count || 0)); // Positive for right

                        // Process Gender Distribution
                        let totalMale = Math.abs(maleCounts.reduce((a, b) => a + b, 0));
                        let totalFemale = femaleCounts.reduce((a, b) => a + b, 0);

                        renderChart(ageLabels, maleCounts, femaleCounts);
                        renderDoughnutChart(totalMale, totalFemale);
                    },
                    error: function (e) {
                        console.log('AJAX error', e);
                    }
                });
            });
        });

        function renderChart(ageLabels, maleCounts, femaleCounts) {
            var options = {
                series: [
                    {
                        name: 'Male',
                        data: maleCounts // Male data (blue)
                    },
                    {
                        name: 'Female',
                        data: femaleCounts.map(value => Math.abs(value)) // Female data (red)
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 440,
                    stacked: true, // Keep bars stacked
                    toolbar: {
                        show: false // Hide toolbar
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 1000,
                        animateGradually: {
                            enabled: true,
                            delay: 200
                        }
                    },
                },
                colors: ['#008FFB', '#FF4560'], // Blue for Male, Red for Female
                plotOptions: {
                    bar: {
                        borderRadius: 12, // Rounded corners for the bars
                        horizontal: true, // Horizontal bars
                        barHeight: '80%', // Height adjustment for a sleek look
                    },
                },
                dataLabels: {
                    enabled: false // Disable data labels for a cleaner design
                },
                stroke: {
                    width: 1,
                    colors: ["#fff"] // White border for contrast
                },
                grid: {
                    xaxis: {
                        lines: {
                            show: true, // Show grid lines for clarity
                            borderColor: '#e0e0e0' // Light gray border color
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false // Hide horizontal grid lines for a clean look
                        }
                    }
                },
                tooltip: {
                    shared: false, // Tooltip for each bar individually
                    x: {
                        formatter: function (val) {
                            return val; // Display age range
                        }
                    },
                    y: {
                        formatter: function (val) {
                            return Math.abs(val) + " voters"; // Display absolute value as "voters"
                        }
                    },
                    style: {
                        fontSize: '14px',
                        fontFamily: 'Roboto, Helvetica, sans-serif', // Modern font family
                        fontWeight: 'bold',
                        background: '#fff', // White background for tooltips
                        borderRadius: '8px', // Rounded corners for tooltips
                        boxShadow: '0 4px 10px rgba(0, 0, 0, 0.1)' // Light shadow for tooltips
                    }
                },
                title: {
                    text: 'Voter Demographics by Gender',
                    align: 'center',
                    style: {
                    fontSize: '24px', // Increased font size for a bigger title
                    fontWeight: 'bold',
                    fontFamily: 'Roboto, Helvetica, sans-serif',
                    color: '#333', // Dark color for title text
                    letterSpacing: '1px', // Increased spacing between letters for better readability
                    },
                    offsetY: -10, // Added vertical offset
                    offsetX: 10  // Added horizontal offset
                },
                xaxis: {
                    categories: ageLabels, // Dynamically set labels based on age ranges
                    title: {
                        text: 'Number of Voters',
                        style: {
                            color: '#333',
                            fontSize: '14px',
                            fontWeight: 600,
                            fontFamily: 'Roboto, Helvetica, sans-serif'
                        }
                    },
                    labels: {
                        formatter: function (val) {
                            return Math.abs(Math.round(val)); // Show rounded values
                        },
                        style: {
                            fontSize: '12px',
                            fontFamily: 'Roboto, Helvetica, sans-serif',
                            color: '#333'
                        }
                    },
                    offsetY: 10, // Add space between X-axis title and X-axis labels
                    axisBorder: {
                        show: true,
                        color: '#333',
                        height: 2,
                    },
                },
                yaxis: {
                    title: {
                        text: 'Age Group',
                        style: {
                            color: '#333',
                            fontSize: '14px',
                            fontWeight: 600,
                            fontFamily: 'Roboto, Helvetica, sans-serif'
                        },
                        offsetX: 10, // Add horizontal padding
                        offsetY: 10  // Add vertical padding
                    },
                    labels: {
                        style: {
                            fontSize: '12px',
                            fontFamily: 'Roboto, Helvetica, sans-serif',
                            color: '#333'
                        }
                    },
                    offsetX: 10, // Adding space between Y-axis title and Y-axis labels
                },
                legend: {
                    position: 'top', // Move the legend to the top
                    horizontalAlign: 'center', // Align the legend horizontally at the top
                    fontSize: '20px',
                    fontFamily: 'Roboto, Helvetica, sans-serif',
                    labels: {
                    useSeriesColors: true
                    },
                    offsetY: -20, // Add vertical padding
                    offsetX: 10  // Add horizontal padding
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

            document.querySelector("#ageSegmentationChart").innerHTML = "";
            var chart = new ApexCharts(document.querySelector("#ageSegmentationChart"), options);
            chart.render();
        }

        // Update renderDoughnutChart function
        function renderDoughnutChart(maleCount, femaleCount) {
            let totalCount = maleCount + femaleCount;
            let malePercentage = (maleCount / totalCount) * 100;
            let femalePercentage = (femaleCount / totalCount) * 100;

            var options = {
                chart: {
                    type: 'donut',
                    height: 500,
                    width: 500
                },
                series: [malePercentage, femalePercentage],
                labels: ['Male', 'Female'],
                colors: ['#008FFB', '#FF4560'], // Blue for Male, Red for Female
                plotOptions: {
                    pie: {
                        donut: {
                            size: '60%', // Size of the doughnut hole
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val.toFixed(2) + "%"; // Show percentage with 2 decimals
                    },
                    style: {
                        fontSize: '16px',
                        fontFamily: 'Arial, Helvetica, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#fff']
                    }
                },
                tooltip: {
                    shared: true,
                    y: {
                        formatter: function (val) {
                            return val.toFixed(2) + "%"; // Tooltip with percentage
                        }
                    }
                },
                title: {
                    text: 'Voter Demographics by Gender',
                    align: 'center',
                    style: {
                        fontSize: '24px', // Increased font size for a bigger title
                        fontWeight: 'bold',
                        fontFamily: 'Roboto, Helvetica, sans-serif',
                        color: '#333', // Dark color for title text
                        letterSpacing: '1px', // Increased spacing between letters for better readability
                    },
                    offsetY: -5 
                },
                legend: {
                    position: 'bottom', // Legend position
                    horizontalAlign: 'center', // Center the legend items
                    floating: false
                }
            };

            document.querySelector("#genderDoughnutChart").innerHTML = "";
            var chart = new ApexCharts(document.querySelector("#genderDoughnutChart"), options);
            chart.render();
        }




        // renderDoughnutChart(ageLabels, maleCounts, femaleCounts);
        // renderChart(maleCounts, femaleCounts);
    JS);
    ?>

    <section class="mt-5 new-map" style="position: relative;">
        <div class="d-flex align-items-center">
            <div>
                <p class="lead font-weight-bold mb-0">Filters: </p>
            </div>

            <div class="ml-5">
                <select class="form-control filter-select" id="select-barangay">
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
                <select class="form-control filter-select" id="select-purok">
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
                <select class="form-control  filter-select" id="select-criteria">
                    <?= Html::foreach([1, 2, 3, 4, 5], function($n) {
                        return Html::tag('option', "Criteria {$n}", [
                            'value' => $n,
                            'selected' => App::get("criteria{$n}_color_id") ? true: false
                        ]);
                    }) ?>
                </select>
            </div>

            <div class="ml-5">
                <select class="form-control filter-select" id="color-select">
                    <option value="" selected>All Colors</option>
                    <?php foreach ($colorData as $id => $name): ?>
                       
                            <option value="<?= $id ?>"><?= $name ?></option>
                      
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </section>


    <div class="mt-10">

    </div>
    <div class="mt-5">

    </div>
    

    <!-- Chart Section -->
    <div id="ageSegmentationChart" style="height: 500px; width: 100%; margin-top: 50px;"></div>



    <div class="d-flex align-items-center justify-content-center">
        <div id="genderDoughnutChart" style="height: 500px;"></div>
    </div>


