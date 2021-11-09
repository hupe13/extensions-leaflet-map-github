<?php
/**
 * Functions for zoomhomemap shortcode
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [zoomhomemap]
function leafext_zoomhome_script($fit){
	$maxzoom=get_option('leaflet_default_zoom');
	if ($maxzoom == 20) $maxzoom = 19;
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var map_id = map._leaflet_id;
		var maps=[];
		maps[map_id] = map;

		if (typeof maps[map_id].options.maxZoom == "undefined")
			maps[map_id].options.maxZoom = '.$maxzoom.';
		if (maps[map_id].options.maxZoom == 20)
			maps[map_id].options.maxZoom = 19;

		//console.log("map_id* "+map_id);

		if(typeof maps[map_id].zoomControl !== "undefined") {
			maps[map_id].zoomControl._zoomOutButton.remove();
			maps[map_id].zoomControl._zoomInButton.remove();
		}

		var bounds = [];
		bounds[map_id] = new L.latLngBounds();

		// parameter fit: only when map !fitbound and ele fitbounds then set zoomhome to map,
		// not in elevation
		// 0: home = ele fitbounds (default)
		// 1: home = map
		var allfit = [];
		if ('.json_encode($fit).' && typeof maps[map_id]._shouldFitBounds === "undefined" ) {
			allfit[map_id] = new L.latLngBounds();
		}

		var zoomHome = [];
		zoomHome[map_id] = L.Control.zoomHome();
		zoomHome[map_id].addTo(maps[map_id]);

		// Some elements zooming to be ready on map
		// Z.Z. Only Lines
		var ende = [];

		//
		var markergroups = window.WPLeafletMapPlugin.markergroups;
		var mapmarkers = 0;
		var mappolygon = 0;
		var maplines = 0;
		var mapcircles = 0;
		Object.entries(markergroups).forEach(([key, value]) => {
			if ( markergroups[key]._map !== null ) {
				if (map_id == markergroups[key]._map._leaflet_id) {
					//console.log("markergroups loop");
					markergroups[key].eachLayer(function(layer) {
						//console.log(layer);
						if (layer instanceof L.Marker){
							//markers do not have fitbounds
							//console.log("is_marker");
							mapmarkers++;
							if ( typeof maps[map_id]._shouldFitBounds !== "undefined") {
								bounds[map_id].extend(layer._latlng);
							} else if ( typeof allfit[map_id] !== "undefined") {
								allfit[map_id].extend(layer._latlng);
								//console.log("allfit marker");
							}
						} else if (layer instanceof L.Polygon) {
							//console.log("is_Polygon");
							mappolygon++;
							if ( typeof maps[map_id]._shouldFitBounds !== "undefined") {
								bounds[map_id].extend(layer.getBounds());
							} else if ( typeof allfit[map_id] !== "undefined") {
								//console.log("allfit polygon wird groesser");
								allfit[map_id].extend(layer.getBounds());
							} else {
								//console.log("polygon was nun?");
							}
						} else if (layer instanceof L.Polyline){
							//console.log("is_Line");
							maplines++;
							if ( typeof maps[map_id]._shouldFitBounds !== "undefined") {
								//console.log("all lines should fit to map");
								bounds[map_id].extend(layer.getBounds());
								ende[map_id] = 1;
								maps[map_id].on("zoomend", function(e) {
									if ( ende[map_id] ) {
										//console.log("lines zooming");
										maps[map_id].fitBounds(bounds[map_id]);
									}
									ende[map_id] = 0;
								});
							} else if ( typeof allfit[map_id] !== "undefined") {
								//console.log("allfit line wird groesser");
								allfit[map_id].extend(layer.getBounds());
							} else {
								//console.log("line was nun?");
								ende[map_id] = 1;
								maps[map_id].on("zoomend", function(e) {
									if ( ende[map_id] ) {
										//console.log(map_id+ "ready zoomend");
										//zoomHome[map_id].setHomeZoom(maps[map_id].getBounds());
										//Uncaught Error: Attempted to load an infinite number of tiles
										zoomHome[map_id].setHomeCoordinates(maps[map_id].getCenter());
										zoomHome[map_id].setHomeZoom(maps[map_id].getZoom());
										ende[map_id] = 0;
									}
								});
							}
						} else if (layer instanceof L.Circle){
							//console.log("is_Circle");
							//console.log(layer);
							mapcircles++;
							if ( typeof maps[map_id]._shouldFitBounds !== "undefined") {
								bounds[map_id].extend(layer.getBounds());
							} else if ( typeof allfit[map_id] !== "undefined") {
								allfit[map_id].extend(layer.getBounds());
							}
						//} else {
							//console.log(layer);
						}
					});
				}
			}
		});
		//console.log("markers "+mapmarkers);
		//console.log("polygon "+mappolygon);
		//console.log("lines "+maplines);
		//console.log("circles "+mapcircles);

		maps[map_id].whenReady ( function() {
			if (bounds[map_id].isValid()) {
				//console.log("ready map has bounds");
				zoomHome[map_id].setHomeBounds(bounds[map_id]);
				maps[map_id].fitBounds(bounds[map_id]);
			} else {
				//console.log(map_id+" ready map has no bounds");
				//console.log(allfit[map_id]);
				//console.log(bounds[map_id]);
				if (typeof allfit[map_id] !== "undefined") {
					//console.log("ready map allfit");
					if (allfit[map_id].isValid()) {
						//console.log("ready map has valid allfit");
						zoomHome[map_id].setHomeBounds(allfit[map_id]);
						//maps[map_id].fitBounds(bounds[map_id]);
					} else {
						//console.log("ready map has invalid allfit");
					}
				} else {
					//console.log("ready map has no allfit");
				}
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
						//console.log("geojson maps[map_id]._shouldFitBounds "+maps[map_id]._shouldFitBounds);
						//console.log("geojson animate "+this._map._animateToZoom);
						if ( typeof maps[map_id]._shouldFitBounds !== "undefined") { //leaflet-map fitbounds
							bounds[map_id].extend(this.getBounds());
							zoomHome[map_id].setHomeBounds(bounds[map_id]);
							maps[map_id].fitBounds(bounds[map_id]);
						} else if (typeof this.map._animateToZoom !== "undefined" ) { //zoom auf das element
							zoomHome[map_id].setHomeCoordinates(maps[map_id]._animateToCenter);
							zoomHome[map_id].setHomeZoom(maps[map_id]._animateToZoom);
						} else if ( ! bounds[map_id].isValid() ) {
							//console.log("geojson bounds invalid"); // weder noch
							zoomHome[map_id].setHomeCoordinates(maps[map_id].getCenter());
							zoomHome[map_id].setHomeZoom(maps[map_id].getZoom());
						//} else {
							//console.log("kommt das vor?");
							//console.log("geojson bounds valid");
							//zoomHome[map_id].setHomeBounds(bounds[map_id]);
							//maps[map_id].fitBounds(bounds[map_id]);
						}
						if ( typeof allfit[map_id] !== "undefined") {
							//console.log("allfit geojson wird groesser");
							allfit[map_id].extend(this.getBounds());
							zoomHome[map_id].setHomeBounds(allfit[map_id]);
						}
					});
				}
			}
		}

		//elevation asynchron
		maps[map_id].on("eledata_loaded", function(e) {
			//console.log(map_id+"elevation loaded");
			//console.log("maps[map_id]._shouldFitBounds "+maps[map_id]._shouldFitBounds);
			if ( typeof maps[map_id]._shouldFitBounds !== "undefined") {
				bounds[map_id].extend(e.layer.getBounds());
				//console.log("ele getbounds");
			}
			if (bounds[map_id].isValid()) {
				//console.log("ele bounds valid");
				zoomHome[map_id].setHomeBounds(bounds[map_id]);
				maps[map_id].fitBounds(bounds[map_id]);
			} else {
				//console.log("ele bounds not valid");
				//console.log("animate "+maps[map_id]._zoom);
				zoomHome[map_id].setHomeCoordinates(maps[map_id].getCenter());
				zoomHome[map_id].setHomeZoom(maps[map_id].getZoom());
				//console.log(maps[map_id]);
			}
		});

		// maps[map_id].on("zoomend", function(e) {
		// 	console.log("zoomend zoom "+maps[map_id].getZoom());
		// });
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
