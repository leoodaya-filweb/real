<?php

$this->registerCss(<<< CSS
	#pac-input-{$widgetId} {
		background: #fff !important;
    	max-width: 400px;
		top: 10px !important;
		font-size: 14px;
		box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px -1px;
	}
	#map-{$widgetId} {
		height: 500px;
		box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px -1px;
	}
CSS);
$this->registerJsFile($api, [
	'async' => true, 
	'defer' => true, 
	'position' => yii\web\View::POS_BEGIN
]);


$this->registerWidgetJs($widgetFunction, <<< JS
	function initAutocomplete() {
		const map = new google.maps.Map(document.getElementById("map-{$widgetId}"), {
			center: {lat: {$latitude}, lng: {$longitude}},
			zoom: 17,
			mapTypeId: 'hybrid'
		});
		// Create the search box and link it to the UI element.
		const input = document.getElementById("pac-input-{$widgetId}");
		const searchBox = new google.maps.places.SearchBox(input);
		const searchInput = document.querySelector('#pac-input-{$widgetId}');

		map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
		// Bias the SearchBox results towards current map's viewport.
		map.addListener("bounds_changed", () => {
			searchBox.setBounds(map.getBounds());
		});

		let markers = [];

		searchInput.addEventListener("keydown", function(event) {
		    if (event.key === "Enter") {
		        event.preventDefault();
		    }
		});

		// Listen for the event fired when the user selects a prediction and retrieve
		// more details for that place.
		searchBox.addListener("places_changed", (e) => {
			const places = searchBox.getPlaces();

			if (places.length == 0) {
				return;
			}

			// Clear out the old markers.
			markers.forEach((marker) => {
			marker.setMap(null);
			});
			markers = [];

			// For each place, get the icon, name and location.
			const bounds = new google.maps.LatLngBounds();

			places.forEach((place) => {
			if (!place.geometry || !place.geometry.location) {
				return;
			}

			const icon = {
				url: place.icon,
				size: new google.maps.Size(71, 71),
				origin: new google.maps.Point(0, 0),
				anchor: new google.maps.Point(17, 34),
				scaledSize: new google.maps.Size(25, 25),
			};

			// Create a marker for each place.
			markers.push(
				new google.maps.Marker({
				map,
				icon,
				title: place.name,
				position: place.geometry.location,
				})
			);
			if (place.geometry.viewport) {
				// Only geocodes have viewport.
				bounds.union(place.geometry.viewport);
			} else {
				bounds.extend(place.geometry.location);
			}
			});
			map.fitBounds(bounds);
		});
		

		var elevator = new google.maps.ElevationService();
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
		google.maps.event.addListener(map, 'click', function(event) {
			var location = event.latLng;
			infowindow.setContent('');
			infowindow.close();
			marker.setMap(null);

			marker = new google.maps.Marker({
				position: location,
				map: map,
				icon: '{$householdIcon}'
			});

			geocoder.geocode({'latLng': location}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						infowindow.setContent(results[0].formatted_address);
					}
				}
			});

			elevator
				.getElevationForLocations({locations: [location]})
				.then(({ results }) => {
					// infowindow = new google.maps.InfoWindow({
					// 	content: 'Latitude: ' + location.lat() +
					// 	'<br>Longitude: ' + location.lng() +
					// 	'<br>Altitude: ' + results[0].elevation 
					// });
					$("#household-longitude").val(location.lng());
					$("#household-latitude").val(location.lat());
					$("#household-altitude").val(results[0].elevation)
				});

			infowindow.open(map, marker);
		});
	}
	initAutocomplete();

JS);
?>

<div>
<input id="pac-input-<?= $widgetId ?>" class="form-control" type="text" placeholder="Search Box"/>
</div>

<div id="map-<?= $widgetId ?>"></div>


