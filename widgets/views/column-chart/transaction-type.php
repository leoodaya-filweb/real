<?php

$this->registerWidgetJs($widgetFunction, <<< JS
	// Shared Colors Definition
	const primary = '#6993FF';
	const success = '#1BC5BD';
	const info = '#8950FC';
	const warning = '#FFA800';
	const danger = '#F64E60';


	const primary2 = '#28936b';
	const success2 = '#2a6c48';
	const info2 = '#51a336';
	const warning2 = '#ad9b1f';
	const danger2 = '#ff49b6';


	const apexChart = "#chart-{$widgetId}";

	let data = {$data};
	var options = {
		series: data.series,
		chart: {
			type: 'bar',
			height: 350,
			toolbar: {
				show: false 
			}
		},
		plotOptions: {
			bar: {
				horizontal: false,
				// columnWidth: '55%',
				endingShape: 'rounded'
			},
		},
		dataLabels: {
			enabled: false
		},
		stroke: {
			show: true,
			width: 2,
			colors: ['transparent']
		},
		xaxis: {
			categories: $data.labels,
		},
		yaxis: {
			title: {
				text: 'Total'
			}
		},
		fill: {
			opacity: 1
		},
		tooltip: {
			y: {
				formatter: function (val) {
					return val;
				}
			}
		},
		colors: [primary, success, danger, info, warning, primary2, success2, danger2, info2, warning2]
	};

	var chart = new ApexCharts(document.querySelector(apexChart), options);
	chart.render();
JS);
?>
<div id="chart-<?= $widgetId ?>"></div>