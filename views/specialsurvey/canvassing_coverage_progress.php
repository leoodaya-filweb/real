    <?php

    use app\helpers\App;
    use app\helpers\Html;
    use app\models\Specialsurvey;
use app\widgets\BulkAction;
use app\widgets\DateRange;
use app\widgets\Grid;
use yii\web\View;

    /* @var $this yii\web\View */
    /* @var $searchModel app\models\search\SpecialsurveySearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $ageSegmentationData array */
        
    /* @var $this yii\web\View */
    /* @var $searchModel app\models\search\SpecialsurveySearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */


    $this->title = 'Canvassing Coverage Progress';
    $this->params['breadcrumbs'][] = $this->title;

    $this->params['searchForm'] = 'testvv';
    $this->params['showCreateButton'] = false;//true; 
    $this->params['showExportButton'] = false; //true;
    $this->params['activeMenuLink'] = '/specialsurvey/canvassing-coverage-progress';
    $this->params['createTitle'] = 'Create Survey';

  
    $this->registerJs(<<<JS
         
         $(document).ready(function () {

             // Function to get URL parameters
            function getUrlParam(param) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }

            // Preselect survey and criteria based on URL
            const surveyName = getUrlParam('survey_name') ?? $('#select-survey').val();
            const criteria = getUrlParam('criteria') ?? $('#select-criteria').val();
            const color = getUrlParam('color_survey') ?? $('#select-color').val();

            if (surveyName) {
                $('#select-survey').val(surveyName);
            }

            if (criteria) {
                $('#select-criteria').val(criteria);
            }

            if (color) {
                $('#select-color').val(color);
            }

            if (window.location.search.includes('list=1')) {
                const newUrl = window.location.href.replace(/(\?|&)list=1(&|$)/, '$1').replace(/[?&]$/, '');
                window.history.replaceState({}, '', newUrl);
            }

            renderChart({$chartData});

            $('.filter-select').change(function () {
                var survey_name = $('#select-survey').val();
                var criteria = $('#select-criteria').val();
                var color = $('#select-color').val();

                // First AJAX: voters list
                $.ajax({
                    url: '/real/web/specialsurvey/canvassing-coverage-progress?list=1',
                    method: 'get',
                    data: { survey_name, criteria,  color},
                    dataType: 'html',
                    success: function (response) {
                        $('#voters-list').html(response);

                        // Clean up pagination URLs
                        $('#voters-list a').each(function () {
                            const href = $(this).attr('href');
                            if (href && href.includes('list=1')) {
                                const cleaned = href.replace(/(\?|&)list=1(&|$)/, '$1').replace(/[?&]$/, '');
                                $(this).attr('href', cleaned);
                            }
                        });
                    },
                    error: function (e) {
                        console.log('AJAX error', e);
                    }
                });

                // Second AJAX: chart
                $.ajax({
                    url: '/real/web/specialsurvey/canvassing-coverage-progress',
                    method: 'get',
                    data: { survey_name, criteria, color },
                    success: function (response) {
                        const data = JSON.parse(response.chartData);
                        renderChart(data);
                    },
                    error: function (e) {
                        console.log('AJAX error', e);
                    }
                });
            });
        });


        let chart;
        function renderChart(chart_data_json) {
            const labels = chart_data_json.map(item => item.barangay_name); // Array of barangay names
            const surveyed = chart_data_json.map(item => parseInt(item.surveyed_households, 10));  // Ensuring base 10 parsing
            const totals = chart_data_json.map(item => parseInt(item.total_households, 10));  // Ensuring base 10 parsing

            // Calculate the percentage for each barangay
            const percentages = surveyed.map((count, index) => {
                const totalCount = totals[index];
                return (count / totalCount) * 100;  // Calculate the percentage
            });

            // Destroy the existing chart if it exists
            if (chart) {
                chart.destroy();
            }

            const options = {
                series: [{
                    name: 'Surveyed Households',
                    data: percentages  // Pass the percentage data as the series
                }],
                chart: {
                    type: 'bar',
                    height: 100 + (labels.length * 35),
                    toolbar: { show: false },
                    // background: '#f4f6f9',  // Light background color
                    // padding: {
                    //     top: 20,
                    //     right: 50,
                    //     bottom: 20,
                    //     left: 20
                    // },
                    // margin: {
                    //     top: 10,
                    //     right: 50,
                    //     bottom: 10,
                    //     left: 10
                    // }
                },        
                plotOptions: {
                    bar: {
                        horizontal: true,
                        barHeight: '85%',
                        distributed: true, // 🎯 This enables different colors per bar
                        dataLabels: {
                            position: 'center'
                        },
                        borderRadius: 10, // Rounded corners
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val.toFixed(2) + '%';  // Display percentage on the bars
                    },
                    style: {
                        fontSize: '14px',  // Larger and cleaner font for data labels
                        fontWeight: '600',  // Make the text slightly bolder
                        colors: ['#fff'],   // White color for better contrast
                    }
                },
                colors: ['#009688', '#607d8b', '#ff9800', '#673ab7', '#3f51b5', '#8bc34a', '#f44336', '#2196f3', '#9e9e9e'], // Modern color palette with gradients
                xaxis: {
                    categories: labels,
                    title: {
                        text: 'Survey Coverage (%)',
                        style: {
                            color: '#333',  // Dark gray color for text
                            fontSize: '14px',
                            fontWeight: '500',
                            fontFamily: 'Roboto, Helvetica, sans-serif',  // Modern font
                        }
                    },
                    labels: {
                        style: {
                            fontSize: '12px',
                            fontFamily: 'Roboto, Helvetica, sans-serif',
                            color: '#555', // Softer text for readability
                        },
                        formatter: function (val) {
                            return val + '%';  // Add percentage symbol on the x-axis labels
                        }
                    },
                    offsetY: 10,
                    axisBorder: {
                        show: true,
                        color: '#ddd',
                        height: 2,
                    },
                },
                yaxis: {
                    categories: labels,
                    type: 'category',
                    labels: {
                        style: {
                            fontSize: '12px',
                            color: '#555',
                            fontFamily: 'Roboto, Helvetica, sans-serif', // Modern font for labels
                        }
                    },
                    title: {
                        text: 'Barangay',
                        style: {
                            fontSize: '16px',
                            fontWeight: '600',
                            color: '#333',
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    theme: 'dark',
                    custom: function ({ series, seriesIndex, dataPointIndex, w }) {
                        const surveyedCount = surveyed[dataPointIndex];
                        const totalCount = totals[dataPointIndex];

                        // Calculate the coverage percentage
                        const percentage = (surveyedCount / totalCount) * 100;

                        // Return custom tooltip content with surveyed count and percentage using string concatenation
                        return '<div style="padding: 10px; background-color: #333; color: white; border-radius: 5px; font-size: 14px;">' +
                            '<strong>Surveyed:</strong> ' + surveyedCount + ' out of ' + totalCount + ' households <br>' +
                            '<strong>Coverage:</strong> ' + percentage.toFixed(2) + '%</div>';
                    }
                },
                grid: {
                    borderColor: '#e0e0e0',
                    strokeDashArray: 3,  // Slightly thinner grid lines for a cleaner look
                },
                legend: {
                    show: false,
                },
                // title: {
                //     text: 'Survey Coverage by Barangay',
                //     align: 'center',
                //     style: {
                //         fontSize: '24px',
                //         fontWeight: '700',
                //         color: '#333',
                //         fontFamily: 'Roboto, Helvetica, sans-serif',
                //     }
                // }
            };

            // Create a new chart and assign it to the global variable
            chart = new ApexCharts(document.querySelector("#progressBarGraph"), options);
            chart.render();
        }

    JS);
    ?>

    <section class="mt-5 new-map" style="position: relative;">
        <div class="d-flex align-items-center">
            <div>
                <p class="lead font-weight-bold mb-0">Filters: </p>
            </div>
            
    
            
            <div class="ml-5">
                <select class="form-control filter-select" id="select-survey">
                    <?=  Html::tag('option', 'Select Survey', [
                        'value' => '',
                        'selected' => true,
                    // 'disabled' => true
                    ]) ?>
                    <?= Html::foreach(Specialsurvey::filter('survey_name'), function($name) {
                    
                        $isLastSurvey = $name === end(Specialsurvey::filter('survey_name')); // Check if it's the last survey
                        return Html::tag('option', $name, [
                            'value' => $name,
                            'selected' => $isLastSurvey, // Auto-select the last survey
                        ]);
                        // return Html::tag('option', $name, [
                        //     'value' => $name,
                        //     'selected' => false
                        // ]);
                    }) ?>
                </select>
            </div>
            <div class="ml-5">
                <select class="form-control filter-select" id="select-criteria">
                    <?= Html::foreach([1, 2, 3, 4, 5], function($n) {
                        return Html::tag('option', "Criteria {$n}", [
                            'value' => $n,
                            'selected' => App::get("criteria{$n}_color_id") ? true: false
                        ]);
                    }) ?>
                </select>
            </div>
          
            
            <div class="ml-5">
                <select class="form-control filter-select" id="select-color">
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
    <h2>Progress Bar graph  </h2>
    <div id="progressBarGraph" style="height: 500px; width: auto; "></div>


    <div id="voters-list">
        <?= Html::beginForm(['bulk-action'], 'post'); ?>
            <?= BulkAction::widget(['searchModel' => $searchModel]) ?>
            <?= Grid::widget([
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]); ?>
        <?= Html::endForm(); ?> 
      
                    
    </div>

    
    




