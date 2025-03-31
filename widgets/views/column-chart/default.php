<?php

$this->registerWidgetJs($widgetFunction, <<< JS
	// Shared Colors Definition
	const primary = '#6993FF';
	const success = '#1BC5BD';
	const info = '#8950FC';
	const warning = '#FFA800';
	const danger = '#F64E60';

	const apexChart = "#chart_3";
	var options = {
		series: [{
			name: 'Net Profit',
			data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 10, 12, 44]
		}, {
			name: 'Revenue',
			data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 10, 12, 44]
		}],
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
			categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
		colors: [primary, danger]
	};

	var chart = new ApexCharts(document.querySelector(apexChart), options);
	chart.render();
JS);
?>
<div id="chart_3"></div>