<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [zoomhomemap]

// iterate any of these: `maps`, `markers`, `markergroups`, `lines`, `circles`, `geojsons`
function leafext_zoomhome_script($fit){
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		//console.log(window.WPLeafletMapPlugin);
		var map_id = map._leaflet_id;
		var maps=[];
		maps[map_id] = map;
		maps[map_id].options.maxZoom = 19;
		//console.log("map_id* "+map_id);
		//console.log("fit "+'.json_encode($fit).');

		if(typeof maps[map_id].zoomControl !== "undefined") {
			maps[map_id].zoomControl._zoomOutButton.remove();
			maps[map_id].zoomControl._zoomInButton.remove();
		}
		var zoom = 0;

		var bounds = [];
		bounds[map_id] = new L.latLngBounds();
		var zoomHome = [];
		zoomHome[map_id] = L.Control.zoomHome();

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
						zoom++;
						//console.log("looping "+zoom);
						//console.log(layer);
						if (layer instanceof L.Marker){
							//console.log("is_marker");
							mapmarkers++;
							bounds[map_id].extend(layer._latlng);
						} else if (layer instanceof L.Polyline){
							//console.log("is_Line");
							maplines++;
							bounds[map_id].extend(layer._latlng);
						} else if (layer instanceof L.Circle){
							//console.log("is_Circle");
							mapcircles++;
							bounds[map_id].extend(layer._latlng);
						// } else {
						// 	console.log(layer);
						}
					});
				}
			}
		});
		//console.log("markers "+mapmarkers);
		//console.log("lines "+maplines);
		//console.log("circles "+mapcircles);

		//geojson asynchron
		var geojsons = window.WPLeafletMapPlugin.geojsons;
		if (geojsons.length > 0) {
			zoom++;
			//console.log("geojsons "+geojsons.length);
			var geocount = geojsons.length;
			zoomHome[map_id].addTo(map);
			for (var j = 0, len = geocount; j < len; j++) {
				var geojson = geojsons[j];
				if (map_id == geojsons[j]._map._leaflet_id) {
					geojson.on("ready", function () {
						//console.log(this._map._leaflet_id);
						bounds[map_id].extend(this.getBounds());
						if (bounds[map_id].isValid()) {
							zoomHome[map_id].setHomeBounds(bounds[map_id]);
							if ('.json_encode($fit).') maps[map_id].fitBounds(bounds[map_id]);
						}
					});
				}
			}
		}

		//elevation asynchron
		maps[map_id].on("eledata_loaded", function(e) {
			//console.log("elevation loaded");
			bounds[map_id].extend(e.layer.getBounds());
			zoomHome[map_id].setHomeBounds(bounds[map_id]);
			maps[map_id].fitBounds(bounds[map_id]);
			zoom = -99;
		});

		//
		if ( zoom > 0 ) {
			if (bounds[map_id].isValid()) {
				zoomHome[map_id].addTo(map);
				zoomHome[map_id].setHomeBounds(bounds[map_id]);
				if ('.json_encode($fit).') {
					//console.log("fit true");
					//console.log("zoom "+maps[map_id].getZoom());
					maps[map_id].fitBounds(bounds[map_id]);
					//if (maps[map_id].getZoom() > 14 && zoom == 1) {
						//	maps[map_id].setZoom(14);
					//}
				}
			}
		}
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
	$atts = leafext_clear_params($atts);
	$params = shortcode_atts($defaults, $atts);
	$params['fit'] = (bool)$params['fit'];

	return leafext_zoomhome_script($params['fit']);
}
add_shortcode('zoomhomemap', 'leafext_plugin_zoomhome_function' );
