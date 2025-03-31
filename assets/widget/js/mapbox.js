class MapboxWidget {
    marker = null;
	constructor({ widgetId, accessToken, lnglat, enableGeocoder, enableNavigationController, enableDrawing, mapLoadScript, initLoadScript, dataloadingScript, sourcedataScript, styleUrl, zoom, pitch, bearing, householdIcon, onClickScript, markerDragEndScript, draggableMarker, enableClick, showMarker }) {
		mapboxgl.accessToken = accessToken;

		this.widgetId = widgetId;
		this.accessToken = accessToken;
		this.lnglat = lnglat;
		this.enableGeocoder = enableGeocoder;
		this.enableNavigationController = enableNavigationController;
        this.enableDrawing = enableDrawing;
        this.mapLoadScript = mapLoadScript;
        this.initLoadScript = initLoadScript;
        this.dataloadingScript = dataloadingScript;
		this.sourcedataScript = sourcedataScript;
        this.styleUrl = styleUrl;
        this.zoom = zoom;
        this.pitch = pitch;
        this.bearing = bearing;
        this.householdIcon = householdIcon;
        this.onClickScript = onClickScript;
        this.markerDragEndScript = markerDragEndScript;
        this.draggableMarker = draggableMarker;
        this.enableClick = enableClick;
        this.showMarker = showMarker;

	    this.map = this.createMapInstance();
	}

	createMapInstance() {
		return new mapboxgl.Map({
	        container: this.widgetId, // container ID
            style: this.styleUrl, // style URL
	        // style: 'mapbox://styles/roelfilweb/cli146vxi02hd01pgel9nez2k', // style URL
	        center: this.lnglat, // starting position [lng, lat]
	        zoom: this.zoom || 13, // starting zoom,
	        pitch: this.pitch || 30,
	        antialias: true,
	        bearing: this.bearing || -17.6,
	    });
	}

	addMapboxDraw() {
        const draw = new MapboxDraw({
            displayControlsDefault: false,
            // Select which mapbox-gl-draw control buttons to add to the map.
            controls: {
                polygon: true,
                trash: true
            },
            // Set mapbox-gl-draw to draw by default.
            // The user does not have to click the polygon control button first.
            defaultMode: 'draw_polygon',
			styles: [
				// Override the default fill and stroke colors
				{
					'id': 'gl-draw-polygon-fill-inactive',
					'type': 'fill',
                    filter: ['==', '$type', 'Polygon'],
					'paint': {
						'fill-color': '#F64E60', // Set the desired fill color
						'fill-opacity': 0.5
					}
				},
				{
					'id': 'gl-draw-polygon-stroke-inactive',
					'type': 'line',
                    filter: ['==', '$type', 'LineString'],
					'paint': {
						'line-color': '#F64E60', // Set the desired stroke color
						'line-width': 2
					}
				},
				// Add point markers to each corner
				{
					'id': 'gl-draw-polygon-and-line-vertex-active',
					'type': 'circle',
                    'filter': ['==', '$type', 'Point'],
					'paint': {
						'circle-radius': 5,
						'circle-color': '#F64E60', // Set the desired corner point color
						'circle-opacity': 0.8 // Set the desired corner point opacity (0-1)
					}
				},
				{
				    "id": "gl-draw-polygon-and-line-vertex-active-fill",
				    "type": "fill",
				    "filter": ["all", ["==", "$type", "Polygon"], ["==", "active", "true"]],
				    "paint": {
				      "fill-color": "#D20C0C",
				      "fill-opacity": 0.2
				    }
				}
			]
        });
        this.map.addControl(draw);

        function updateArea(e) {
            const data = draw.getAll();
            const answer = document.getElementById('calculated-area');
            if (data.features.length > 0) {
                const area = turf.area(data);
                // Restrict the area to 2 decimal points.
                const rounded_area = Math.round(area * 100) / 100;
                answer.innerHTML = '<p><strong>'+rounded_area.toLocaleString()+'</strong></p><p>square meters</p>';
            } 
            else {
                answer.innerHTML = '';
                if (e.type !== 'draw.delete')
                    alert('Click the map to draw a polygon.');
            }
        }

        this.map.on('draw.create', updateArea);
        this.map.on('draw.delete', updateArea);
        this.map.on('draw.update', updateArea);
    }

    addMarker(lnglat = '') {
        lnglat = lnglat || this.lnglat;

        const self = this;
        self.marker = new mapboxgl.Marker({
            color: "#FFFFFF",
            draggable: this.draggableMarker,
            icon: {
                url: this.householdIcon,
                size: [25, 25]
            },
            color: 'red',
            size: 50,
            opacity: 0.5
        })
        .setLngLat(lnglat)
        .addTo(this.map);

        // self.marker.on('dragstart', function () {
        //     console.log('dragstart');
        // });

        self.marker.on('dragend', function (e) {
            const coordinate = self.marker.getLngLat();

            self.markerDragEndScript(coordinate)
        });
    }

    addNavigationControl() {
        const nav = new mapboxgl.NavigationControl({
            visualizePitch: true
        });
        this.map.addControl(nav, 'bottom-right');
    }

    addPopover() {
        const markerHeight = 50;
        const markerRadius = 10;
        const linearOffset = 25;
        const popupOffsets = {
            'top': [0, 0],
            'top-left': [0, 0],
            'top-right': [0, 0],
            'bottom': [0, -markerHeight],
            'bottom-left': [linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
            'bottom-right': [-linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
            'left': [markerRadius, (markerHeight - markerRadius) * -1],
            'right': [-markerRadius, (markerHeight - markerRadius) * -1]
        };

        const popup = new mapboxgl.Popup({offset: popupOffsets, className: 'my-class'})
            .setLngLat(this.lnglat)
            .setHTML("General Nakar")
            .setMaxWidth("300px")
            .addTo(this.map);
    }

    addScaleControl() {
        const scale = new mapboxgl.ScaleControl({
            maxWidth: 80,
            unit: 'imperial'
        });
        this.map.addControl(scale);
        scale.setUnit('metric');
    }

    addGeolocateControl() {
	    this.map.addControl(new mapboxgl.GeolocateControl({
	        positionOptions: {
	            enableHighAccuracy: true
	        },
	        trackUserLocation: true,
	        showUserHeading: true
	    }));
	}

	setPov() {
        const camera = this.map.getFreeCameraOptions();
        const position = [138.72649, 35.33974];
        const altitude = 3000;
        camera.position = mapboxgl.MercatorCoordinate.fromLngLat(position, altitude);
        camera.lookAtPoint(lnglat);
        this.map.setFreeCameraOptions(camera);
    }

    add3DBuilding() {
        // Insert the layer beneath any symbol layer.
        const layers = this.map.getStyle().layers;
        const labelLayerId = layers.find(
        (layer) => layer.type === 'symbol' && layer.layout['text-field']
        ).id;
         
        // The 'building' layer in the Mapbox Streets
        // vector tileset contains building height data
        // from OpenStreetMap.
        this.map.addLayer({
            'id': 'add-3d-buildings',
            'source': 'composite',
            'source-layer': 'building',
            'filter': ['==', 'extrude', 'true'],
            'type': 'fill-extrusion',
            'minzoom': 15,
            'paint': {
                'fill-extrusion-color': '#337ab7',
                 
                // Use an 'interpolate' expression to
                // add a smooth transition effect to
                // the buildings as the user zooms in.
                'fill-extrusion-height': [
                    'interpolate',
                    ['linear'],
                    ['zoom'],
                    15,
                    0,
                    15.05,
                    ['get', 'height']
                ],
                'fill-extrusion-base': [
                    'interpolate',
                    ['linear'],
                    ['zoom'],
                    15,
                    0,
                    15.05,
                    ['get', 'min_height']
                ],
                'fill-extrusion-opacity': 0.6
            }
        }, labelLayerId);
    }

    addMarkerWithSymbol() {
        this.map.loadImage(
            'https://docs.mapbox.com/mapbox-gl-js/assets/custom_marker.png',
            (error, image) => {
                if (error) throw error;
                this.map.addImage('custom-marker', image);
                // Add a GeoJSON source with 2 points
                this.map.addSource('points', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': [
	                        {
	                            // feature for Mapbox DC
	                            'type': 'Feature',
	                            'geometry': {
	                                'type': 'Point',
	                                'coordinates': this.lnglat
	                            },
	                            'properties': {
	                                'title': 'Mapbox DC'
	                            }
	                        },
	                        {
	                            // feature for Mapbox SF
	                            'type': 'Feature',
	                            'geometry': {
	                                'type': 'Point',
	                                'coordinates': [-122.414, 37.776]
	                            },
	                            'properties': {
	                                'title': 'Mapbox SF'
	                            }
	                        }
	                    ]
                    }
                });
                 
                // Add a symbol layer
                this.map.addLayer({
                    'id': 'points',
                    'type': 'symbol',
                    'source': 'points',
                    'layout': {
                        'icon-image': 'custom-marker',
                        // get the title name from the source's "title" property
                        'text-field': ['get', 'title'],
                        'text-font': [
                            'Open Sans Semibold',
                            'Arial Unicode MS Bold'
                        ],
                        'text-offset': [0, 1.25],
                        'text-anchor': 'top'
                    }
                });
            }
        );
    }

    addLineString() {
        this.map.addSource('route', {
            'type': 'geojson',
            'data': {
                'type': 'Feature',
                'properties': {},
                'geometry': {
                    'type': 'LineString',
                    'coordinates': [
                        [121.042728, 14.367669],
                        [121.054101, 14.359105],
                    ]
                }
            }
        });
        
        this.map.addLayer({
            'id': 'route',
            'type': 'line',
            'source': 'route',
            'layout': {
                'line-join': 'round',
                'line-cap': 'round'
            },
            'paint': {
                'line-color': '#888',
                'line-width': 8
            }
        });
    }

    addGeocoderPlugin() {
        // Add the control to the map.
        this.map.addControl(
            new MapboxGeocoder({
                accessToken: mapboxgl.accessToken,
                mapboxgl: mapboxgl
            })
        );
    }

    addNavigationDirection() {
        this.map.addControl(
            new MapboxDirections({
                accessToken: mapboxgl.accessToken
            }),
            'top-left'
        );
    }

    add3dTerrain() {
        this.map.addSource('mapbox-dem', {
	        'type': 'raster-dem',
	        'url': 'mapbox://mapbox.mapbox-terrain-dem-v1',
	        'tileSize': 512,
	        'maxzoom': 14
        });
        // add the DEM source as a terrain layer with exaggerated height
        this.map.setTerrain({ 'source': 'mapbox-dem', 'exaggeration': 1.5 });
    }

    updatePaintAttribute(layerId, paintProperty, paintValue) {
        var layer = this.map.getLayer(layerId);
        if (layer) {
            var newPaint = {};
            newPaint[paintProperty] = paintValue;
            this.map.setPaintProperty(layerId, paintProperty, paintValue);
            console.log('paintValue', paintValue)
        }
    }

	init() {
        this.initLoadScript(this);

 		if (this.enableGeocoder) {
	    	this.addGeocoderPlugin();
 		}

 		if (this.enableNavigationController) {
	    	this.addNavigationDirection();
 		}
 		
 		if (this.enableDrawing) {
	    	this.addMapboxDraw();
 		}
        if (this.showMarker) {
            this.addMarker();
        }
	    // this.addNavigationControl();
	    // this.addPopover();
	    this.addScaleControl();
	    this.addGeolocateControl();
	    // setPov();

	    this.map.on('click', (e) => {
            if (this.enableClick) {
                if (this.marker) {
                    this.marker.remove();
                }
                const {lat, lng} = e.lngLat;
                this.addMarker([lng, lat]);
                this.onClickScript({lat, lng});
            }
	    });

        this.map.on('dataloading', (e) => {
            this.dataloadingScript(this.map, e);
        });

        this.map.on('sourcedata', (e) => {
            this.sourcedataScript(this.map, e);
        });

	    this.map.on('load', () => {
            this.mapLoadScript(this.map, this);

	        // this.addMarkerWithSymbol();
	        // addLineString();
	    });

	    this.map.on('style.load', () => {
	        this.add3DBuilding();
	        // this.add3dTerrain();
	    });
	}

}