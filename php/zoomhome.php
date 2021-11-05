<?php
/**
 * Functions for zoomhomemap shortcode
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [zoomhomemap]

// iterate any of these: `maps`, `markers`, `markergroups`, `lines`, `circles`, `geojsons`
function leafext_zoomhome_script($fit){
	$maxzoom=get_option('leaflet_default_zoom');
	if ($maxzoom == 20) $maxzoom = 19;
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		//console.log(window.WPLeafletMapPlugin);
		var map_id = map._leaflet_id;
		var maps=[];
		maps[map_id] = map;
		//console.log("map zoom* "+maps[map_id].options.maxZoom);
		if (typeof maps[map_id].options.maxZoom == "undefined")
			maps[map_id].options.maxZoom = '.$maxzoom.';
		if (maps[map_id].options.maxZoom == 20)
			maps[map_id].options.maxZoom = 19;
		//console.log(maps[map_id]);
		//console.log(maps[map_id].options.maxZoom);
		//console.log("map_id* "+map_id);
		console.log("fit "+'.json_encode($fit).');

		if(typeof maps[map_id].zoomControl !== "undefined") {
			maps[map_id].zoomControl._zoomOutButton.remove();
			maps[map_id].zoomControl._zoomInButton.remove();
		}

		var bounds = [];
		bounds[map_id] = new L.latLngBounds();

		var zoomHome = [];
		zoomHome[map_id] = L.Control.zoomHome();
		zoomHome[map_id].addTo(maps[map_id]);

		if ('.json_encode($fit).' || typeof maps[map_id]._shouldFitBounds !== "undefined" ) {
			// //
			// var lines = window.WPLeafletMapPlugin.lines;
			// if (lines.length > 0) {
				// console.log("lines "+lines.length);
			// }
			// //
			// var markers = window.WPLeafletMapPlugin.markers;
			// if (markers.length > 0) {
				// console.log("markers "+markers.length);
			// }
			// //
			// var circles = window.WPLeafletMapPlugin.circles;
			// if (circles.length > 0) {
				// console.log("circles "+circles.length);
			// }

			//
			var markergroups = window.WPLeafletMapPlugin.markergroups;
			var mapmarkers = 0;
			var maplines = 0;
			var mapcircles = 0;
			Object.entries(markergroups).forEach(([key, value]) => {
				if ( markergroups[key]._map !== null ) {
					if (map_id == markergroups[key]._map._leaflet_id) {
						//console.log("markergroups loop");
						markergroups[key].eachLayer(function(layer) {
						//console.log(layer);
						if (layer instanceof L.Marker){
							//console.log("is_marker");
							mapmarkers++;
							bounds[map_id].extend(layer._latlng);
						} else if (layer instanceof L.Polyline){
							//console.log("is_Line");
							maplines++;
							bounds[map_id].extend(layer.getBounds());
						} else if (layer instanceof L.Circle){
							//console.log("is_Circle");
							//console.log(layer);
							mapcircles++;
							bounds[map_id].extend(layer.getBounds());
						//} else {
						 	//console.log(layer);
						}
					});
				}
			}
			});
			//console.log("markers "+mapmarkers);
			//console.log("lines "+maplines);
			//console.log("circles "+mapcircles);

		}
			
		maps[map_id].whenReady ( function() {
			if (bounds[map_id].isValid()) {
				console.log("ready map has bounds");
				//console.log("zoom "+maps[map_id].getZoom());
				zoomHome[map_id].setHomeBounds(bounds[map_id]);
				maps[map_id].fitBounds(bounds[map_id]);
			} else {
				console.log("ready map has no bounds");
				console.log("zoom load "+maps[map_id].getZoom());
				//zoomHome[map_id].setHomeBounds(maps[map_id].getBounds());
				zoomHome[map_id].setHomeCoordinates(maps[map_id].getCenter());
				zoomHome[map_id].setHomeZoom(maps[map_id].getZoom());
			}
		});

		//geojson asynchron
		var geojsons = window.WPLeafletMapPlugin.geojsons;
		if (geojsons.length > 0) {
			//console.log("geojsons "+geojsons.length);
			var geocount = geojsons.length;
			for (var j = 0, len = geocount; j < len; j++) {
				var geojson = geojsons[j];
				if (map_id == geojsons[j]._map._leaflet_id) {
					geojson.on("ready", function () {
						//console.log(this._map._leaflet_id);
						console.log("geojson maps[map_id]._shouldFitBounds "+maps[map_id]._shouldFitBounds);
						//console.log(this);
						console.log(this._map._animateToZoom);
						if ('.json_encode($fit).' || typeof maps[map_id]._shouldFitBounds !== "undefined") {
							bounds[map_id].extend(this.getBounds());
						}
						if (bounds[map_id].isValid()) {
							console.log("geojson bounds valid");
							zoomHome[map_id].setHomeBounds(bounds[map_id]);
							maps[map_id].fitBounds(bounds[map_id]);
						} else {
							console.log("geojson bounds not valid");
							console.log(maps[map_id]._animateToCenter);
							console.log(maps[map_id].getCenter());
							console.log(maps[map_id]._animateToZoom);
							if (typeof this.map._animateToZoom !== "undefined" ) {
								zoomHome[map_id].setHomeCoordinates(maps[map_id]._animateToCenter);
								zoomHome[map_id].setHomeZoom(maps[map_id]._animateToZoom);
							} else {
								zoomHome[map_id].setHomeCoordinates(maps[map_id].getCenter());
								zoomHome[map_id].setHomeZoom(maps[map_id].getZoom());
							}
						}
					});
				}
			}
		}

		//elevation asynchron
		maps[map_id].on("eledata_loaded", function(e) {
			console.log("elevation loaded");
			//console.log(zoomHome);
			console.log("elevation maps[map_id]._shouldFitBounds "+maps[map_id]._shouldFitBounds);

			if ('.json_encode($fit).' || typeof maps[map_id]._shouldFitBounds !== "undefined") {
				bounds[map_id].extend(e.layer.getBounds());
			}
			//console.log(Object.keys(zoomHome[map_id]));
			//console.log(Object.keys(zoomHome[map_id]).includes("_zoomHomeButton"));
			if (bounds[map_id].isValid()) {
				zoomHome[map_id].setHomeBounds(bounds[map_id]);
				maps[map_id].fitBounds(bounds[map_id]);
			} else {
				console.log(maps[map_id]._animateToCenter);
				console.log(maps[map_id].getCenter());
				console.log(maps[map_id]._animateToZoom);
				zoomHome[map_id].setHomeCoordinates(maps[map_id].getCenter());
				zoomHome[map_id].setHomeZoom(maps[map_id].getZoom());
			}
		});

		maps[map_id].on("zoomend", function(e) {
			console.log("zoomend");
			console.log("zoom "+maps[map_id].getZoom());
		});
	});
	</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_plugin_zoomhome_function($atts){
	leafext_enqueue_zoomhome ();
	//
	$defaults = array(
		'fit' => 1,
	);
	$atts1 = leafext_clear_params($atts);
	$params = shortcode_atts($defaults, $atts1);
	switch ($params['fit']) {
		case "false":
		case "0": $params['fit'] = false; break;
		default: $params['fit'] = true;
	}
	return leafext_zoomhome_script($params['fit']);
}
add_shortcode('zoomhomemap', 'leafext_plugin_zoomhome_function' );
