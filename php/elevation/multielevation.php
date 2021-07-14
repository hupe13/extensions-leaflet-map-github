<?php
//Shortcode:
//[elevation-track file="'.$file.'" lat="'.$startlat.'" lng="'.$startlon.'" name="'.basename($file).'"]
//[elevation-tracks summary=1]

function leafext_elevation_track( $atts ){
	global $all_files;
	if (!is_array($all_files)) $all_files = array();
	$all_files[]=$atts['file'];
	//
	global $all_points;
	if (!is_array($all_points)) $all_points = array();
	$point = array(
		'latlng' => array($atts['lat'],$atts['lng']),
		'name' => $atts['name'],
	);
	$all_points[]=$point;
}
add_shortcode('elevation-track', 'leafext_elevation_track' );

//[elevation-tracks]
function leafext_elevation_tracks_script( $all_files, $all_points, $theme, $summary, $slope ){
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();

		var points = '.json_encode($all_points).';
		var tracks = '.json_encode($all_files).';
		var theme =  '.json_encode($theme).';
		//console.log(points);
		//console.log(tracks);

		var opts = {
			points: {
				icon: {
					iconUrl: "'.LEAFEXT_PLUGIN_URL.'" + "/leaflet-plugins/leaflet-elevation-1.6.9/images/elevation-poi.png",
					iconSize: [12, 12],
				},
			},
			elevation: {
				theme: theme,
				detachedView: true,
				elevationDiv: "#elevation-div",
				followPositionMarker: true,
				zFollow: 15,
				legend: false,
				followMarker: false,
				downloadLink:false,
				polyline: { weight: 3,},
				summary: '.json_encode($summary).',
				slope: '.json_encode($slope).',
			},
			markers: {
				startIconUrl: null, // "http://mpetazzoni.github.io/leaflet-gpx/pin-icon-start.png",
				endIconUrl: null, // "http://mpetazzoni.github.io/leaflet-gpx/pin-icon-end.png",
				shadowUrl: null, // "http://mpetazzoni.github.io/leaflet-gpx/pin-shadow.png",
				// wptIcon and wptIconUrls seems to be a bug, if configured, elevation chart does not appear
				// console.log in gpx.js is commented out. Nervt.
				wptIcon: null,
				wptIconUrls: null, // params.pluginsUrl + "/leaflet-plugins/leaflet-gpx-1.5.2/pin-icon-wpt.png",
			},
			legend_options:{
				collapsed: true,
			},
		};

		var mylocale = {
			"Altitude"		: "'.__("Altitude", "extensions-leaflet-map").'",
			"Total Length: "	: "'.__("Total Length", "extensions-leaflet-map").': ",
			"Max Elevation: "	: "'.__("Max Elevation", "extensions-leaflet-map").': ",
			"Min Elevation: "	: "'.__("Min Elevation", "extensions-leaflet-map").': ",
			"Total Ascent: "	: "'.__("Total Ascent", "extensions-leaflet-map").': ",
			"Total Descent: "	: "'.__("Total Descent", "extensions-leaflet-map").': ",
			"Min Slope: "		: "'.__("Min Slope", "extensions-leaflet-map").': ",
			"Max Slope: "		: "'.__("Max Slope", "extensions-leaflet-map").': ",
		};
		L.registerLocale("wp", mylocale);
		L.setLocale("wp");

		var routes;
		routes = new L.gpxGroup(tracks, {
			points: points,
			points_options: opts.points,
			elevation: true,
			elevation_options: opts.elevation,
			marker_options: opts.markers,
			legend: true,
			distanceMarkers: false,
			legend_options: opts.legend_options,
	    	});

		map.on("eledata_added eledata_clear", function(e) {
			var p = document.querySelector(".chart-placeholder");
			if(p) {
				p.style.display = e.type=="eledata_added" ? "none" : "";
			}
		});

	    routes.addTo(map);
	});

	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
	  var map = window.WPLeafletMapPlugin.getCurrentMap();
		map.options.maxZoom = 19;
		var bounds = [];
		bounds = new L.latLngBounds();
		var zoomHome = [];
		zoomHome = L.Control.zoomHome();
		var zoomhomemap=false;
		map.on("zoomend", function(e) {
			//console.log("zoomend");
			//console.log( zoomhomemap );
			if ( ! zoomhomemap ) {
				//console.log(map.getBounds());
				zoomhomemap=true;
				zoomHome.addTo(map);
				zoomHome.setHomeBounds(map.getBounds());
			}
		});
	});
</script>';
$text = \JShrink\Minifier::minify($text);
return "\n".$text."\n";
}

function leafext_elevation_tracks( $atts ){
	leafext_enqueue_elevation ();
	leafext_enqueue_zoomhome();

	wp_enqueue_script('leaflet.gpx',
		plugins_url('leaflet-plugins/leaflet-gpx-1.5.2/gpx-leafext.js',LEAFEXT_PLUGIN_FILE),
		array('elevation_js'),null);
	wp_enqueue_script('leaflet.gpxgroup',
		plugins_url('leaflet-plugins/leaflet-elevation-1.6.9/libs/leaflet-gpxgroup.min.js',LEAFEXT_PLUGIN_FILE),
		array('leaflet.gpx'),null);
	wp_enqueue_style( 'my_elevation_css',
	 	plugins_url('css/multielevation.css',LEAFEXT_PLUGIN_FILE),
	 	array('elevation_css'), null);

	global $all_files;
	global $all_points;

	$theme = leafext_elevation_theme();
	$atts = leafext_clear_params($atts);

	$chart_options = shortcode_atts( array('summary' => false), $atts);

	//Parameters see the sources from https://github.com/Raruto/leaflet-elevation
	if ( ! $chart_options['summary'] ) {
		$summary = false;
		$slope = false;
	} else {
		$summary = "inline";
		$slope = "summary";
	}

	$text = leafext_elevation_tracks_script( $all_files, $all_points, $theme, $summary, $slope);
	$text = $text.'<div class="has-text-align-center"><div id="elevation-div" class="leaflet-control elevation"><p class="chart-placeholder">';
	$text = $text.__("move mouse over a track or select one in control panel ...", "extensions-leaflet-map").'</p></div></div>';
	return $text;
}
add_shortcode('elevation-tracks', 'leafext_elevation_tracks' );
