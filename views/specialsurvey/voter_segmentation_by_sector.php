    <?php

    use app\helpers\App;
    use app\helpers\Html;
    use app\models\Specialsurvey;
    use yii\web\View;

    /* @var $this yii\web\View */
    /* @var $searchModel app\models\search\SpecialsurveySearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $ageSegmentationData array */
        
    /* @var $this yii\web\View */
    /* @var $searchModel app\models\search\SpecialsurveySearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */


    $this->title = 'Voter Segmentation By Sector';
    $this->params['breadcrumbs'][] = $this->title;

    $this->params['searchForm'] = 'testvv';
    $this->params['showCreateButton'] = false;//true; 
    $this->params['showExportButton'] = false; //true;
    $this->params['activeMenuLink'] = '/specialsurvey/voter-segmentation-by-sector';
    $this->params['createTitle'] = 'Create Survey';

  
    $this->registerJs(<<<JS
         
        $(document).ready(function () {
            renderChart();
          

            $('.filter-select').change(function () {

                var barangay = $('#select-barangay').val();
                var purok = $('#select-purok').val();
                var criteria = $('#select-criteria').val();
                var color = $('#color-select').val();

              

                $.ajax({
                    url: '/real/web/specialsurvey/voter-segmentation-by-sector',
                    method: 'get',
                    data: { barangay, purok, criteria },
                    success: function (response) {
                        
                        
                        console.log(response);
                        
                        // renderChart(ageLabels, maleCounts, femaleCounts);
                        // renderDoughnutChart(totalMale, totalFemale);
                    },
                    error: function (e) {
                        console.log('AJAX error', e);
                    }
                });
            });
        });


        function renderChart() {
            let data = {$chart_data_json}; // Ensure this is a JSON array of sector stacks
            let label = {$barangay_labels_json}; // Color categories

            var options = {
                series: data, // Uses stacked sector data
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
                        columnWidth: '50%', // Thinner bars for a sleek look
                        borderRadius: 5, // Rounded corners
                    } 
                },
                stroke: { width: 1, colors: ['#fff'] },
                title: { 
                    text: 'Voter Segmentation By Sector',
                    align: 'center', 
                    style: { 
                        fontSize: '26px', // BIG BOLD TITLE
                        fontWeight: 'bold',
                        color: '#333' 
                    } 
                },
                xaxis: { 
                    categories: label,
                    labels: { 
                        style: { fontSize: '14px', fontWeight: 'bold', colors: '#333' } 
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
                colors: ['#D72638', '#1B98E0', '#F4A261', '#2E4057'], // Vibrant professional colors

                // ✅ NEW: Display sector names inside the stacked bars
                dataLabels: {
                    enabled: true,
                    style: { fontSize: '13px', fontWeight: 'bold', colors: ['#fff'] },
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex].name; // Show sector name (Senior, PWD, etc.)
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#sectorSegmentationChart"), options);
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

          
        </div>
    </section>


    <div class="mt-10">

    </div>
    <div class="mt-5">

    </div>
    

    <!-- Chart Section -->
    <div id="sectorSegmentationChart" style="height: 500px; width: 100%; margin-top: 50px;"></div>





