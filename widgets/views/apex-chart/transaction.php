<?php

use app\widgets\DateRange;

$src = <<< JS
    var initChart  = function(s, apexChart) {
        var options = {
            series: s.series,
            chart: {
                type: 'line',
                height: {$height},
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },

            stroke: {
                show: true,
                width: 2,
            },
            
            xaxis: {
                categories: s.labels,
            },
            yaxis: {
                title: {
                    text: 'Transactions'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                }
            },
            colors: s.colors
        };

        var chart = new ApexCharts(document.querySelector(apexChart), options);
        chart.render();
    }
    KTApp.blockPage();
    var _start = start.format('MMMM DD, YYYY');
    var _end = end.format('MMMM DD, YYYY')
    $.ajax({
        url: app.baseUrl + 'dashboard/transaction-chart',
        data: {
            start: _start,
            end: _end,
        },
        dataType: 'json',
        method: 'post',
        success: function(s) {
            if(s.status == 'success') {

                $('#apex-chart-container-{$widgetId}').html('<div id="apex-chart-{$widgetId}"></div>')
                initChart(s.data, "#apex-chart-{$widgetId}")
            }
            else {
            }
            KTApp.unblockPage();
            $('#span-{$widgetId}').html(_start + ' - ' + _end)
        },
        error: function(e) {
            KTApp.unblockPage();
        }
    })
JS;

$this->registerWidgetJs($widgetFunction, <<<JS
    var initChart  = function(s, apexChart) {
        var options = {
            series: s.series,
            chart: {
                type: 'line',
                height: {$height},
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },

            stroke: {
                show: true,
                width: 2,
            },
            
            xaxis: {
                categories: s.labels,
            },
            yaxis: {
                title: {
                    text: 'Visitors'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                }
            },
            colors: s.colors
        };

        var chart = new ApexCharts(document.querySelector(apexChart), options);
        chart.render();
    }

    initChart({$data}, "#apex-chart-{$widgetId}");
JS, \yii\web\View::POS_END);
?>

<div class="card card-custom card-stretch gutter-b apex-chart">
    <div class="card-header h-auto border-0">
        <div class="card-title py-5 draggable-handle">
            <h3 class="card-label">
                <span class="d-block text-dark font-weight-bolder">
                    Transaction Statistics
                </span>
                <div class="text-muted mt-3 font-weight-bold font-size-lg">
                    Total transactions as of 
                    <span id="span-<?= $id ?>">
                        <?= $start ?> - <?= $end ?>
                    </span>
                </div>
            </h3>
        </div>
        <div class="toolbar">
            <?= DateRange::widget([
                'title' => false, 
                'onchange' => $src,
                'model' => $model
            ]) ?>
        </div>
    </div>
    <div class="card-body" id="apex-chart-container-<?= $widgetId ?>">
        
        <div id="apex-chart-<?= $widgetId ?>"></div>
    </div>
</div>