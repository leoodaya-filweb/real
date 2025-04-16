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


    $this->title = 'Project Turnout';
    $this->params['breadcrumbs'][] = $this->title;

    $this->params['searchForm'] = 'testvv';
    $this->params['showCreateButton'] = false;//true; 
    $this->params['showExportButton'] = false; //true;
    $this->params['activeMenuLink'] = '/specialsurvey/project-turnout';
    $this->params['createTitle'] = 'Create Survey';

  
    $this->registerJs(<<<JS
          
          
        $(document).ready(function () {
            var survey_name = $('#select-survey').val();
            var criteria = $('#select-criteria').val();
            var color = $('#select-color').val() ?? null;

            $.ajax({
                url: '/real/web/specialsurvey/project-turnout',
                method: 'get',
                data: { survey_name, criteria, color },
                success: function (response) {
                    const data = response.chart_data_json;
                    const label = response.color_labels_json;

                    renderChart(data, label);

                    $(".table-responsive tbody").html(response.table_html);
                },
                error: function (e) {
                    console.log('AJAX error', e);
                }
            });
           
          
            $('.filter-select').change(function () {
                var survey_name = $('#select-survey').val();
                var criteria = $('#select-criteria').val();
                var color = $('#select-color').val();

                $.ajax({
                    url: '/real/web/specialsurvey/project-turnout',
                    method: 'get',
                    data: { survey_name, criteria, color },
                    success: function (response) {
                        const data = response.chart_data_json;
                        const label = response.color_labels_json;

                        renderChart(data, label);

                        $(".table-responsive tbody").html(response.table_html);
                    },
                    error: function (e) {
                        console.log('AJAX error', e);
                    }
                });
            });

            

            
            
        });


        function renderChart(chart_data_json, color_labels_json) {
            let data = chart_data_json;
            let label = color_labels_json; // Object.keys(chart_data_json); // optional, if not passed in

            const staticColors = [
                '#1e88e5', '#ff7043', '#66bb6a', '#ffa726', '#ab47bc', '#00acc1', '#ffca28',
                '#8e24aa', '#7e57c2', '#42a5f5', '#26a69a', '#f4511e', '#29b6f6', '#8d6e63',
                '#d32f2f', '#0288d1', '#c2185b'
            ];

            // Ensure there are enough colors for the data labels
            const colors = staticColors.slice(0, label.length);

            var options = {
                series: [{
                    name: 'Projected Votes',
                    data: data
                }],
                chart: {
                    type: 'bar',
                    height: 650,
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 600  // Smooth and modern animation speed
                    },
                    // background: '#f7f7f7',  // Light background for a clean, airy look
                    sparkline: { enabled: false }  
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '85%', 
                        borderRadius: 8, 
                        dataLabels: { position: 'top' }
                    }
                },
                stroke: { width: 0 },  // No border for the bars to keep it sleek
                title: {
                    text: 'Projected Votes Per Barangay',
                    align: 'center',
                    style: {
                        fontSize: '26px',
                        fontWeight: '600',  // More approachable but modern font weight
                        color: '#333',
                        fontFamily: "'Roboto', sans-serif",  // A modern, clean font
                    }
                },
                xaxis: {
                    categories: label,
                    labels: {
                        style: { fontSize: '14px', fontWeight: '400', colors: '#555', fontFamily: "'Roboto', sans-serif" },
                    },
                    axisBorder: { show: true, color: '#ddd' },
                    axisTicks: { show: true, color: '#ddd' }
                },
                yaxis: {
                    labels: {
                        style: { fontSize: '14px', fontWeight: '400', colors: '#555', fontFamily: "'Roboto', sans-serif" },
                    },
                    title: {
                        text: 'Projected Votes',
                        style: { fontSize: '16px', fontWeight: '600', color: '#333' }
                    }
                },
                tooltip: {
                    theme: 'light',  // Light theme tooltip to keep it user-friendly
                    y: { formatter: function (val) { return val; } },
                    marker: { show: true },  // Add a marker for clarity
                    style: { fontFamily: "'Roboto', sans-serif", fontWeight: '400' }
                },
                grid: {
                    borderColor: '#e0e0e0',
                    strokeDashArray: 3,  // Subtle dashed grid lines
                    padding: { left: 20, right: 20, top: 20, bottom: 20 },
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontSize: '14px',
                    markers: { radius: 4 },
                    labels: { colors: '#333' }
                },
                colors: colors,  // Using dynamic color scheme for better distinction

                dataLabels: {
                    enabled: true,
                    style: { fontSize: '14px', fontWeight: '600', colors: ['#fff'] },
                    formatter: function (val) { return val; }  // Display the vote count clearly on top of bars
                },
                responsive: [
                    {
                        breakpoint: 1024,
                        options: {
                            chart: { height: 500 },
                            title: { fontSize: '22px' },
                            xaxis: { labels: { fontSize: '12px' } }
                        }
                    },
                    {
                        breakpoint: 768,
                        options: {
                            chart: { height: 400 },
                            title: { fontSize: '20px' },
                            xaxis: { labels: { fontSize: '10px' } }
                        }
                    }
                ]
            };

            // Clear existing chart if any
            document.querySelector("#projectedVotesGraph").innerHTML = "";

            // Create and render the chart
            var chart = new ApexCharts(document.querySelector("#projectedVotesGraph"), options);
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
                    <option value="" disabled>Select Color</option>
                    <?php foreach ($colorData as $id => $name): ?>
                        <option value="<?= $id ?>" <?= $id === array_key_first($colorData) ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

          
        </div>
    </section>


    <div class="mt-5">

    </div>
    <div class="mt-5">

    </div>

    <div class="table-responsive mt-5">
        <table class="table table-hover  table-active table-striped">
            <thead class="text-primary bg-gradient">
                <tr class="text-start">
                    <th>Barangay</th>
                    <th >Total Voters</th>
                    <th >Total Registered Voters</th>
                    <th >Supported Voters</th>
                    <th >Support Rate %</th>
                    <th >Projected Votes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datas as $data): ?>
                    <tr class="text-start">
                        <td><?= Html::encode($data['barangay']) ?></td>
                        <td ><?= Html::encode($data['total_voters']) ?></td>
                        <td ><?= Html::encode($data['total_registered']) ?></td>
                        <td ><?= Html::encode($data['support_voters']) ?></td>
                        <td ><?= Html::encode(number_format($data['support_rate'], 2)) ?>%</td>
                        <td ><?= Html::encode($data['projected_votes']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>    
        </table>
    </div>


     <!-- Chart Section -->
     <div id="projectedVotesGraph" style="height: 500px; width: 100%; margin-top: 10px;"></div>





