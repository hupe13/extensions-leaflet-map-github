<?php
/**
 * Functions for multielevation
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode:
//[elevation-track file="'.$file.'" lat="'.$startlat.'" lng="'.$startlon.'" name="'.basename($file).'"]
// lat lng name optional
//[elevation-tracks summary=1]

function leafext_elevation_track( $atts ){

	if ( $atts['file'] == "" ) {
		$text = "[elevation-track ";
		foreach ($atts as $key=>$item){
			$text = $text. "$key=$item ";
		}
		$text = $text."]";
		return $text;
	}

	global $all_files;
	if (!is_array($all_files)) $all_files = array();
	global $all_points;
	if (!is_array($all_points)) $all_points = array();

	$defaults = array (
		'lat'  => '',
		'lng'  => '',
		'name' => '',
	);
	$params = shortcode_atts($defaults, $atts);
	//

	if ( $params['lat'] == "" || $params['lng'] == "" || $params['name'] == "" ) {
		$gpx = simplexml_load_file($atts['file']);
		if ($gpx ===  FALSE) {
			$text = "[elevation-track read error ";
			foreach ($params as $key=>$item){
				$text = $text. "$key=$item ";
			}
			$text = $text."]";
			return $text;
		}
	}
	if ( $params['lat'] == "" || $params['lng'] == "" ) {
		$latlng = array(
			(float)$gpx->trk->trkseg->trkpt[0]->attributes()->lat,
			(float)$gpx->trk->trkseg->trkpt[0]->attributes()->lon,
		);
	} else {
		$latlng = array($params['lat'],$params['lng']);
	}
	if ( $params['name'] == "" ) {
		$name = (string) $gpx->trk->name;
	} else {
		$name = $params['name'];
	}
	$point = array(
		'latlng' => $latlng,
		'name' 	 => $name,
	);

	$all_points[] = $point;
	$all_files[]=$atts['file'];
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
					iconUrl: "'.LEAFEXT_ELEVATION_URL.'" + "/images/elevation-poi.png",
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
			},
			legend_options:{
				//collapsed: true,
				collapsed: false,
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
		routes.addTo(map);
		
		map.on("eledata_added eledata_clear", function(e) {
			var p = document.querySelector(".chart-placeholder");
			if(p) {
				p.style.display = e.type=="eledata_added" ? "none" : "";
			}
		});
	
		if (typeof map.options.maxZoom == "undefined")
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
	leafext_enqueue_multielevation();
	leafext_enqueue_zoomhome();

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
