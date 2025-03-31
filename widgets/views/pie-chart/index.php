<?php

use app\helpers\Html;
use app\widgets\AppIcon;

$this->registerWidgetJs($widgetFunction, <<< JS
    let options = {$options};
    options['tooltip']['y']['formatter'] = function(value, series) {
        // use series argument to pull original string from chart data
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");    
    };

    const apexChart = "#pie-{$widgetId}";
    var chart = new ApexCharts(document.querySelector(apexChart), options);
    chart.render();
JS);
?>


<div id="pie-<?= $widgetId ?>" class="d-flex justify-content-center"></div>

    