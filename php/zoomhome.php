<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [zoomhomemap]

//https://stackoverflow.com/questions/43228007/check-if-font-awesome-exists-before-enqueueing-to-wordpress/43229715
function leafext_plugin_stylesheet_installed($array_css) {
    global $wp_styles;
    foreach( $wp_styles->queue as $style ) {
        foreach ($array_css as $css) {
            if (false !== strpos( $wp_styles->registered[$style]->src, $css ))
                return 1;
        }
    }
    return 0;
}

// iterate any of these: `maps`, `markers`, `markergroups`, `lines`, `circles`, `geojsons`
function leafext_zoomhome_script($fit){
	include_once LEAFEXT_PLUGIN_DIR . '/pkg/JShrink/Minifier.php';
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
    //console.log(window.WPLeafletMapPlugin);
		console.log("map._leaflet_id "+map._leaflet_id);
		var map_id = map._leaflet_id;
    var maps=[];
    maps[map_id] = map;
		console.log("fit "+'.json_encode($fit).');

		if(typeof maps[map_id].zoomControl !== "undefined") {
			maps[map_id].zoomControl._zoomOutButton.remove();
			maps[map_id].zoomControl._zoomInButton.remove();
		}
		var zoom = 0;

		var bounds = [];
		bounds[map_id] = new L.latLngBounds();
		var zoomHome = [];
    zoomHome[map_id] = L.Control.zoomHome();

		maps[map_id].on("eledata_loaded", function(e) {
			console.log("elevation loaded");
			bounds[map_id].extend(e.layer.getBounds());
			zoomHome[map_id].setHomeBounds(bounds[map_id]);
			maps[map_id].fitBounds(bounds[map_id]);
			zoom = -99;
		});

		//
		var lines = window.WPLeafletMapPlugin.lines;
		if (lines.length > 0) {
			zoom++;
			console.log("lines "+lines.length);
			for (var k = 0, len = lines.length; k < len; k++) {
				if (map_id == lines[k]._map._leaflet_id) {
					var line = lines[k];
					bounds[map_id].extend(line.getBounds());
				}
			}
		}
		//
		var markers = window.WPLeafletMapPlugin.markers;
		if (markers.length > 0) {
			console.log("markers "+markers.length);
			zoom++;
			var markerArray = [];
			markerArray[map_id] = [];

			for (var m = 0, len = markers.length; m < len; m++) {
				//console.log("marker "+m);
				//console.log(markers[m]);
				if ( markers[m]._map !== null ) {
					//console.log(map_id, markers[m]._map._leaflet_id);
					if (map_id == markers[m]._map._leaflet_id) {
						markerArray[map_id].push(markers[m]);
					  //console.log("valid");
            //console.log(markers[m]);
          }
				}
			}
      console.log("markerArray.length "+markerArray[map_id].length);
			var group = [];
      group[map_id] = L.featureGroup(markerArray[map_id]);
      //console.log(group[map_id].length);
      if ( typeof group[map_id].length !== "undefined" ) {
			     bounds[map_id].extend(group[map_id].getBounds());
			     if ('.json_encode($fit).') maps[map_id].fitBounds(bounds[map_id]);
      }
		}
		//
		var markergroups = window.WPLeafletMapPlugin.markergroups;
    console.log("markergroups");
    Object.entries(markergroups).forEach(([key, value]) => {
      if ( markergroups[key]._map !== null ) {
        if (map_id == markergroups[key]._map._leaflet_id) {
          console.log("markergroups loop");
          markergroups[key].eachLayer(function(layer) {
            bounds[map_id].extend(layer._latlng);
          });
        }
      }
    });

		//geojson asynchron
		var geojsons = window.WPLeafletMapPlugin.geojsons;
		if (geojsons.length > 0) {
			zoom++;
			console.log("geojsons "+geojsons.length);
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

		//
		var circles = window.WPLeafletMapPlugin.circles;
		if (circles.length > 0) {
			console.log("circles "+circles.length);
			zoom++;
			for (var c = 0, len = circles.length; c < len; c++) {
				if (map_id == circles[c]._map._leaflet_id) {
					var circle = circles[c];
					bounds[map_id].extend(circle.getBounds());
				}
			}
		}
		//
		if ( zoom > 0 ) {
			if (bounds[map_id].isValid()) {
				zoomHome[map_id].addTo(map);
				zoomHome[map_id].setHomeBounds(bounds[map_id]);
				maps[map_id].options.maxZoom = 19;
				if ('.json_encode($fit).') {
					//console.log("fit true");
					console.log(maps[map_id].getZoom());
					maps[map_id].fitBounds(bounds[map_id]);
					//if (maps[map_id].getZoom() > 14 && zoom == 1) {
						//	maps[map_id].setZoom(14);
					//}
				}
			}
		}
		window.addEventListener("load", main);
		});
	</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_plugin_zoomhome_function($atts){
	wp_enqueue_script('zoomhome',
		plugins_url('leaflet-plugins/leaflet.zoomhome/leaflet.zoomhome.min.js',LEAFEXT_PLUGIN_FILE),
			array('wp_leaflet_map'), null);
	wp_enqueue_style('zoomhome',
		plugins_url('leaflet-plugins/leaflet.zoomhome/leaflet.zoomhome.css',LEAFEXT_PLUGIN_FILE),
			array('leaflet_stylesheet'), null);
	// Font awesome
	$font_awesome = array('font-awesome', 'fontawesome');
	if (leafext_plugin_stylesheet_installed($font_awesome) === 0) {
			wp_enqueue_style('font-awesome',
        plugins_url('css/font-awesome.min.css',LEAFEXT_PLUGIN_FILE),
          array('zoomhome'), null);
	}

	if (is_array($atts)) {
		for ($i = 0; $i < count($atts); $i++) {
			if (isset($atts[$i])) {
				if ( strpos($atts[$i],"!") === false ) {
					$atts[$atts[$i]] = 1;
				} else {
					$atts[substr($atts[$i],1)] = 0;
				}
			}
		}
	}
	//
	$defaults = array(
		'fit' => 1,
	);
	$params = shortcode_atts($defaults, $atts);
	$params['fit'] = (bool)$params['fit'];

  return leafext_zoomhome_script($params['fit']);
}
add_shortcode('zoomhomemap', 'leafext_plugin_zoomhome_function' );
