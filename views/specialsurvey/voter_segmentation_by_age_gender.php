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
    $this->params['searchForm'] = 'testvv';
    $this->params['showCreateButton'] = false;//true; 
    $this->params['showExportButton'] = false; //true;
    $this->params['activeMenuLink'] = '/specialsurvey/voter-segmentation-by-age-and-genders';
    $this->params['createTitle'] = 'Create Survey';

    $ageRanges = array_map(fn($item) => $item['age_range'], $ageSegmentationData);

    $maleData = [];
    $femaleData = [];

    foreach ($ageSegmentationData as $item) {
        // Negative values for male counts to make them appear on the left
        $maleData[] = -(int)($item['male_count'] ?? 0); 
        // Positive values for female counts to make them appear on the right
        $femaleData[] = (int)($item['female_count'] ?? 0);
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

            const maleCount = $encodedMaleData.reduce((acc, val) => acc + Math.abs(val), 0); 
            const femaleCount = $encodedFemaleData.reduce((acc, val) => acc + val, 0);

            
            renderDoughnutChart(maleCount,femaleCount);

            $('.filter-select').change(function () {
                var barangay = $('#select-barangay').val();
                var criteria = $('#select-criteria').val();
                var color = $('#color-select').val();

                // Only store the selected purok IF the barangay hasn't changed
                var selectedPurok = $('#select-purok').val();
                if ($(this).attr('id') === 'select-barangay') {
                    selectedPurok = ""; // Reset purok if barangay changes
                }

                $.ajax({
                    url: '/real/web/specialsurvey/voter-segmentation-by-age-and-genders',
                    method: 'get',
                    data: { barangay, purok: selectedPurok, criteria, color },
                    success: function (response) {
                        let ageLabels = response.ageSegmentationData.map(item => item.age_range);
                        let maleCounts = response.ageSegmentationData.map(item => -parseInt(item.male_count || 0));
                        let femaleCounts = response.ageSegmentationData.map(item => parseInt(item.female_count || 0));

                        let totalMale = Math.abs(maleCounts.reduce((a, b) => a + b, 0));
                        let totalFemale = femaleCounts.reduce((a, b) => a + b, 0);

                        // ✅ Reset purok dropdown
                        $("#select-purok").html('<option value="" selected>Select..</option>');

                        let purokExists = false; // Track if previous purok exists in new list

                        $.each(response.purok, function (key, value) {
                            let isSelected = selectedPurok === value.purok ? 'selected' : '';
                            if (isSelected) purokExists = true;
                            $("#select-purok").append('<option value="' + value.purok + '" ' + isSelected + '>' + value.purok + '</option>');
                        });

                        // ✅ Reset selection if old purok doesn't exist in new barangay
                        if (!purokExists) {
                            $("#select-purok").val(""); // Reset to "Select.."
                        }

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
                        data: maleCounts
                    },
                    {
                        name: 'Female',
                        data: femaleCounts.map(value => Math.abs(value)) 
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 440,
                    stacked: true, 
                    toolbar: {
                        show: false 
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
                colors: ['#008FFB', '#FF4560'], 
                plotOptions: {
                    bar: {
                        borderRadius: 12,
                        horizontal: true, 
                        barHeight: '80%',
                    },
                },
                dataLabels: {
                    enabled: false 
                },
                stroke: {
                    width: 1,
                    colors: ["#fff"]
                },
                grid: {
                    xaxis: {
                        lines: {
                            show: true, 
                            borderColor: '#e0e0e0' 
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
                tooltip: {
                    shared: false,
                    x: {
                        formatter: function (val) {
                            return val; // Display age range
                        }
                    },
                    y: {
                        formatter: function (val) {
                            return Math.abs(val) + " voters"; 
                        }
                    },
                    style: {
                        fontSize: '14px',
                        fontFamily: 'Roboto, Helvetica, sans-serif',
                        fontWeight: 'bold',
                        background: '#fff',
                        borderRadius: '8px',
                        boxShadow: '0 4px 10px rgba(0, 0, 0, 0.1)'
                    }
                },
                title: {
                    text: 'Voter Segmentation by Gender',
                    align: 'center',
                    style: {
                    fontSize: '24px', 
                    fontWeight: 'bold',
                    fontFamily: 'Roboto, Helvetica, sans-serif',
                    color: '#333',
                    letterSpacing: '1px',
                    },
                    offsetY: -10, 
                    offsetX: 10 
                },
                xaxis: {
                    categories: ageLabels,
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
                    offsetY: 10, 
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
                        offsetX: 10,
                        offsetY: 10
                    },
                    labels: {
                        style: {
                            fontSize: '12px',
                            fontFamily: 'Roboto, Helvetica, sans-serif',
                            color: '#333'
                        }
                    },
                    offsetX: 10, 
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    fontSize: '20px',
                    fontFamily: 'Roboto, Helvetica, sans-serif',
                    labels: {
                    useSeriesColors: true
                    },
                    offsetY: -20, 
                    offsetX: 10 
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
                            size: '60%', 
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val.toFixed(2) + "%"; 
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
                            return val.toFixed(2) + "%";
                        }
                    }
                },
                title: {
                    text: 'Voter Segmentation by Gender',
                    align: 'center',
                    style: {
                        fontSize: '24px', 
                        fontWeight: 'bold',
                        fontFamily: 'Roboto, Helvetica, sans-serif',
                        color: '#333',
                        letterSpacing: '1px', 
                    },
                    offsetY: -5 
                },
                legend: {
                    position: 'bottom', 
                    horizontalAlign: 'center', 
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


