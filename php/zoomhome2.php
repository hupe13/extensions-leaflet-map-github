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
		var map_id = map._leaflet_id;
		var maps=[];
		maps[map_id] = map;
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
    //zoomHome[map_id].addTo(maps[map_id]);
		console.log("loaded "+map_id);
		console.log("zoom load "+maps[map_id].getZoom());
    //zoomHome[map_id].setHomeBounds(maps[map_id].getBounds());
    //zoomHome[map_id].setHomeCoordinates(maps[map_id].getCenter());
    //zoomHome[map_id].setHomeZoom(maps[map_id].getZoom());

    //maps[map_id].on("load", function(e) {
    maps[map_id].whenReady( function(e) {
      if (bounds[map_id].isValid()) {
        zoomHome[map_id].addTo(map);
        zoomHome[map_id].setHomeBounds(bounds[map_id]);
        //console.log("zoom "+maps[map_id].getZoom());
        maps[map_id].fitBounds(bounds[map_id]);
      } else {
        console.log("no bounds");
        zoomHome[map_id] = L.Control.zoomHome();
        zoomHome[map_id].addTo(maps[map_id]);
        console.log("loaded "+map_id);
        console.log("zoom load "+maps[map_id].getZoom());
        //zoomHome[map_id].setHomeBounds(maps[map_id].getBounds());
        zoomHome[map_id].setHomeCoordinates(maps[map_id].getCenter());
        zoomHome[map_id].setHomeZoom(maps[map_id].getZoom());
      }
    });

		if ('.json_encode($fit).') {
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
						bounds[map_id].extend(this.getBounds());
						if (bounds[map_id].isValid()) {
							zoomHome[map_id].setHomeBounds(bounds[map_id]);
							maps[map_id].fitBounds(bounds[map_id]);
						}
					});
				}
			}
		}
		}

		//elevation asynchron
		maps[map_id].on("eledata_loaded", function(e) {
			//console.log("elevation loaded");
			//console.log(zoomHome);
			if ('.json_encode($fit).') {
				bounds[map_id].extend(e.layer.getBounds());
			}
			//console.log(Object.keys(zoomHome[map_id]));
			//console.log(Object.keys(zoomHome[map_id]).includes("_zoomHomeButton"));
			if ( ! Object.keys(zoomHome[map_id]).includes("_zoomHomeButton")) {
				console.log("Lade Control");
				//zoomHome[map_id].addTo(map);
			}
			if (bounds[map_id].isValid()) {
				zoomHome[map_id].setHomeBounds(bounds[map_id]);
				maps[map_id].fitBounds(bounds[map_id]);
			} else {
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
