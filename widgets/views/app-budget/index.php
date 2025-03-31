<?php

use app\helpers\Html;
use app\widgets\AppIcon;

$this->registerCss(<<< CSS
    .app-iconbox {
        box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px 0 !important;
    }
    #{$widgetId} .wave .card-body {
        padding: 0.25rem 0.25rem;
    }
CSS);

$this->registerWidgetJs($widgetFunction, <<< JS
    // Shared Colors Definition
    const primary = '#6993FF';
    const success = '#1BC5BD';
    const info = '#8950FC';
    const warning = '#FFA800';
    const danger = '#F64E60';
    const facebook = '#3b5998';
    const twitter = '#1da1f2';

    if({$showChart}) {
        const apexChart = "#pie-{$widgetId}";
        var options = {
            series: {$series},
            chart: {
                width: 450,
                type: 'pie',
            },
            labels: {$labels},
            tooltip: {
                y: {
                    formatter: function(value, series) {
                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");  
                    }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            colors: [primary, success, warning, danger, info, facebook, twitter]
        };

        var chart = new ApexCharts(document.querySelector(apexChart), options);
        chart.render();
    }
JS);
?>

<?php $this->beginContent("@app/widgets/views/app-budget/{$template}.php", [
    'widgetId' => $widgetId
]); ?>
    <div class="row">
        
        <?= Html::if($showChart == 'true', <<< HTML
            <div class="col">
                <div class="text-center">
                    <p class="lead font-weight-bold">Distribution ({$totalDisbursed})</p>
                </div>
                <div id="pie-{$widgetId}" class="d-flex justify-content-center"></div>
            </div>
        HTML) ?>
        <div class="col">
            <div class="wave wave-animate-slower app-iconbox card card-custom">
                <div class="card-body">
                    <div class="d-flex align-items-center p-5">
                        <div class="mr-6 icon-content">
                            <div class="svg-icon svg-icon-success svg-icon-4x">
                                <?= AppIcon::widget(['icon' => 'money']) ?>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-dark text-hover-success font-weight-bold font-size-h4 mb-3">Budget 
                                <span class="text-success">
                                    (<?= $model->getTotalAmount(true) ?>)
                                </span>
                            </a>
                            <div class="text-dark-75">
                                Total budget reserved for assistance as of this year (<?= $model->year ?>).
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-5"></div>
            <div class="wave wave-animate-slower app-iconbox card card-custom">
                <div class="card-body">
                    <div class="d-flex align-items-center p-5">
                        <div class="mr-6 icon-content">
                            <div class="svg-icon svg-icon-primary svg-icon-4x">
                                <?= AppIcon::widget(['icon' => 'money']) ?>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Usable 
                                <span class="text-primary">
                                    (<?= $model->getTotalUsable(true) ?>)
                                </span>
                            </a>
                            <div class="text-dark-75">
                                Total budget usable expressed in pesos to date (<?= $model->year ?>).
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="my-5"></div>
            <div class="wave wave-animate-slower app-iconbox card card-custom">
                <div class="card-body">
                    <div class="d-flex align-items-center p-5">
                        <div class="mr-6 icon-content">
                            <div class="svg-icon svg-icon-warning svg-icon-4x">
                                <?= AppIcon::widget(['icon' => 'money']) ?>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-dark text-hover-warning font-weight-bold font-size-h4 mb-3">Disbursed 
                                <span class="text-warning">
                                    (<?= $totalDisbursed ?>)
                                </span>
                            </a>
                            <div class="text-dark-75">
                                Total amount disbursed for AICS(program/project) (<?= $model->year ?>).
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $this->endContent(); ?>
    