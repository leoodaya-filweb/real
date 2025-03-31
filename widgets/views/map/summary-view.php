<?php

$this->registerJsFile($api, ['async' => true, 'defer' => true, 'position' => yii\web\View::POS_BEGIN]);

$this->registerWidgetJs($widgetFunction, <<< JS
	var map;
	function initMap() {
		map = new google.maps.Map(document.getElementById('map-{$widgetId}'), {
			center: {lat: {$latitude}, lng: {$longitude}},
			zoom: 17,
			mapTypeId: 'hybrid'
		});

		var marker = new google.maps.Marker({
			position: {lat: {$latitude}, lng: {$longitude}},
			map: map,
			icon: '{$householdIcon}'
		});
		var infowindow = new google.maps.InfoWindow({
			content: '',
			position: {lat: {$latitude}, lng: {$longitude}},
		});

		var geocoder = new google.maps.Geocoder();
		
		geocoder.geocode(
			{'latLng': {lat: {$latitude}, lng: {$longitude}}}, 
			function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						infowindow.setContent(results[0].formatted_address);
					}
				}
			}
		);

		infowindow.open(map, marker);
	}
	initMap();
JS);

$this->registerCss(<<< CSS
	#map-{$widgetId} {
		height: 450px;
		box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px -1px;
	}
CSS);
?>

<div id="map-<?= $widgetId ?>"></div>


