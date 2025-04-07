<?php

use app\helpers\App;
use app\helpers\Html;
use app\models\Specialsurvey;
use app\widgets\Anchors;
use app\widgets\BulkAction;
use app\widgets\DateRange;
use app\widgets\ExportButton;
use app\widgets\FilterColumn;
use app\widgets\Grid;
use app\widgets\Mapbox;
use app\widgets\TinyMce;
//use yii\helpers\Url;
use app\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SpecialsurveySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

 $searchModel->searchTemplate = 'specialsurvey/_search_voters';
 $searchModel->searchAction = ['specialsurvey/voter-analysis'];


$this->title = 'Voter Support & Behavior Analysis';
$this->params['breadcrumbs'][] = $this->title;

$this->params['searchForm'] = 'testvv';
$this->params['showCreateButton'] = false;//true; 
$this->params['showExportButton'] = false; //true;
$this->params['activeMenuLink'] = '/specialsurvey/conversion-rate-analysis';
$this->params['createTitle'] = 'Create Survey';




$this->registerCss(<<< CSS
    #line-chart{
        max-width: 100%; height: auto; padding: 10px;
    }
   
    
CSS, ['type' => "text/css"]);

$this->registerJsVar('labels', $labels);
$this->registerJsVar('periods', $periods);
$this->registerJsVar('grayData', $grayData);
$this->registerJsVar('colorData', $colorData);

$this->registerJs(<<<JS
    var labels = {$labels};
    var periods = {$periods};
    var grayData = {$grayData} || [];
    var colorData = {$colorData} || [];

    function renderChart(selectedColorId) {
        var selectedColor = colorData.find(item => item.id == selectedColorId);

        var series = [{ 
            name: 'Gray Voters', 
            data: grayData, 
            color: '#e4e6ef' 
        }];

        if (selectedColor) {
            series.push({ 
                name: selectedColor.name, 
                data: selectedColor.data, 
                color: selectedColor.color 
            });
        }

        // Split labels into arrays with line breaks
        var formattedLabels = labels.map(function(value) {
            var parts = value.split('(');
            if (parts.length > 1) {
                return [parts[0], '(' + parts[1]];  // Create an array with two parts
            } else {
                return [value];  // Return the label as a single part if no '(' is found
            }
        });

        var options = {
            chart: { 
                type: 'line', 
                height: 500, 
                toolbar: { show: false }, 
                zoom: { enabled: false }
            },
            series: series,
            xaxis: { 
                title: { 
                    text: 'Survey Period',
                    style: { fontSize: '15px', fontWeight: '500', }
                },
                categories: formattedLabels,  // Use the formatted labels array
                labels: {
                    rotate: -45, // Adjust rotation for better readability
                    rotateAlways: true,
                    style: {
                        fontSize: '12px', // Reduced font size for better readability
                        fontWeight: '600',
                        colors: '#666',
                        padding: { top: 10, bottom: 10 }
                    },
                    trim: false
                },
                axisBorder: { show: true, color: '#ddd' },
                tickPlacement: 'on',
                position: 'bottom',
                
            },
            yaxis: {
                title: { 
                    text: 'Number of Votes',
                    style: { fontSize: '15px', fontWeight: '500', padding: { right: 10, left: 10 } }
                },
                labels: {
                    style: { fontSize: '12px', fontWeight: '600', colors: '#666' }
                },
                axisBorder: { show: true, color: '#ddd' }
            },
            title: { 
                text: 'Conversion Rate Analysis', 
                align: 'center',
                style: { 
                    fontSize: '22px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            },
            stroke: { 
                curve: 'smooth', 
                width: 3 
            },
            markers: { 
                size: 5, 
                hover: { size: 8 },
                strokeWidth: 2 
            },
            grid: {
                borderColor: '#e0e0e0',
                strokeDashArray: 4
            },
            legend: { 
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '12px',
                markers: {
                    width: 10,
                    height: 10,
                    radius: 10
                }
            },
            tooltip: {
                theme: 'dark',
                x: { format: 'dd MMM HH:mm' }
            }
        };

        document.querySelector("#line-chart").innerHTML = "";

        var chart = new ApexCharts(document.querySelector("#line-chart"), options);
        chart.render();
    }





    // Initial render with first color (if available)
    if (colorData.length > 0) {
        renderChart(colorData[0].id);
        fetchVoterList(colorData[0].id);
    } else {
        // console.warn("No color data available to render the chart.");
    }

    function fetchVoterList(selectedColors, selectedCriteria) {
        $.ajax({
            url: '/real/web/specialsurvey/conversion-rate-analysis',
            type: 'GET',
            data: { criteria: selectedCriteria, color_survey: selectedColors },
            success: function(response) {
                $('#voter-list').html(response);
            }
        });

        
    }

    

    

    // Handle multi-select dropdown change
    document.querySelectorAll("select").forEach(function(selectElement) {
        selectElement.addEventListener("change", function() {
            const selectedColors = document.querySelector("#color-select").value;
            const selectedCriteria = document.querySelector("#select-criteria").value;

            // Fetch voter list based on selected colors and criteria
            fetchVoterList(selectedColors, selectedCriteria);
            renderChart(selectedColors);
        });
    });

JS);
?>

<section class="mt-5 new-map" style="position: relative;">
    <div class="d-flex gap-5 align-items-center">
        <div>
            <p class="lead font-weight-bold mb-0">Filter  Color: </p>
        </div>
        <div class="ml-5">
            <select class="form-control" id="color-select">
                <?php foreach (json_decode($colorData, true) as $color): ?>
                    <?php if ($color['id'] != 2): // Exclude Gray Voters ?>
                        <option value="<?= $color['id'] ?>" 
                            data-content='<span class="badge" style="background-color: <?= $color['color'] ?>; margin-right: 5px;"></span><?= $color['name'] ?>'>
                            <?= $color['name'] ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>

        </div>
        <div class="ml-5">
                <p class="lead font-weight-bold mb-0">Criteria: </p>
            </div>
            <div class="ml-5">
                <select class="form-control" id="select-criteria">
                    <?= Html::foreach([1, 2, 3, 4, 5], function($n) {
                        return Html::tag('option', "Criteria {$n}", [
                            'value' => $n,
                            'selected' => App::get("criteria{$n}_color_id") ? true: false
                        ]);
                    }) ?>
                </select>

            </div>
    </div>
    <div  id="line-chart" ></div>
</section>



<div class="specialsurvey-index-page" >


 

 
    <div id="voter-list">
    <?= Html::beginForm(['bulk-action'], 'post'); ?>
        <?= BulkAction::widget(['searchModel' => $searchModel]) ?>
        <?= Grid::widget([
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]); ?>
    <?= Html::endForm(); ?> 
   
    </div>
    
    
    
</div>


