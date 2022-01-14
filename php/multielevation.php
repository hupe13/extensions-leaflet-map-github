<?php
/**
 * Functions for multielevation
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_multielevation_params() {
	$params = array (
		array(
			'param' => 'filename',
			'shortdesc' => __('Filename as trackname',"extensions-leaflet-map"),
			'desc' => __('Use filename (without extension) as name of the track.',"extensions-leaflet-map"),
			'default' => false,
			'values' => 1,
		),
		array(
			'param' => 'summary',
			'shortdesc' => __('Summary',"extensions-leaflet-map"),
			'desc' =>	sprintf ( __('Valid for %s: Only elevation profile with or without summary line will be displayed.',"extensions-leaflet-map"),
				'<code>[elevation-<span style="color: #d63638">tracks</span>]</code>'),
			'default' => true,
			'values' => 1,
		),
	);
	return $params;
}

function leafext_eleparams_for_multi($options=array()) {
		$multi=array();
		$params = leafext_elevation_params();
		foreach($params as $param) {
			if ( $param['multielevation'] ) $multi[] = $param["param"];
		}

	// $params = array(
	// 	//'theme', in multioptions
	// 	//'polyline',
	// 	'marker',
	// 	//'waypoints',
	// 	//'distanceMarkers', in multioptions
	// 	//'downloadLink',
	// 	'summary',
	// 	'legend',
	// 	'altitude',
	// 	'acceleration',
	// 	'slope',
	// 	'speed',
	// 	'time',
	// 	'distance',
	// 	//'chart',
	// 	'followMarker',
	// 	'autofitBounds',
	// 	'imperial',
	// 	'reverseCoords',
	// 	//'ruler',
	// 	//'almostOver',
	// 	//'preferCanvas',
	// );
	if (count($options) > 1 ) {
		$multioptions = array();
		foreach ($multi as $param) {
			$multioptions[$param] = $options[$param];
		}
		return $multioptions;
	} else {
		$text = '';
		sort($multi);
		foreach ($multi as $param) {
			$text = $text.'<code>'.$param.'</code>, ';
		}
		$text = substr($text, 0, -2);
		return $text;
	}
}

function leafext_multielevation_settings() {
	$defaults=array();
	$params = leafext_multielevation_params();
	foreach($params as $param) {
		$defaults[$param['param']] = $param['default'];
	}
	$options = shortcode_atts($defaults, get_option('leafext_multieleparams'));
	return $options;
}

//Shortcode:
//[elevation-track file="'.$file.'" lat="'.$startlat.'" lng="'.$startlon.'" name="'.basename($file).'" filename=true/false]
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

	$multioptions = shortcode_atts(leafext_multielevation_settings(), leafext_clear_params($atts));

	$defaults = array (
		'lat'  => '',
		'lng'  => '',
		'name' => '',
	);
	$params = shortcode_atts($defaults, $atts);

	if ( $multioptions['filename']) {
		$path_parts = pathinfo($atts['file']);
		$name = $path_parts['basename'];
	} else if ( $params['name'] != "" ) {
		$name = $params['name'];
	} else {
		$name = '';
	}
	//

	if ( $params['lat'] == "" || $params['lng'] == "" || $name == "" ) {
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
	if ( $name == "" ) {
		$name = (string) $gpx->trk->name;
	}
	//Fallback
	if ( $name == "" ) {
		$name = $path_parts['basename'];
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
function leafext_elevation_tracks_script( $all_files, $all_points, $theme, $settings, $multioptions ) {
	//var_dump($settings,$multioptions); wp_die();
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

		let controlwitdh = document.getElementsByClassName( "leaflet-right");
		let maxcontrolwidth = 0;
		for(var i=0, len=controlwitdh.length; i<len; i++)	{
			var computed = getComputedStyle( controlwitdh[i], null );
			var width = parseInt(computed.getPropertyValue( "width" ));
			if (width > maxcontrolwidth) {
				maxcontrolwidth = width;
			}
		}
		//console.log( maxcontrolwidth );
		//console.log(window.innerWidth);
		var collapse = false;
		if (window.innerWidth/5 < maxcontrolwidth) {
			collapse = true;
		}

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
				almostOver: true,
	';

					foreach ($settings as $k => $v) {
						switch ($k) {
							case "polyline":
								$text = $text. "$k: ". $v .',';
								unset ($settings[$k]);
								break;
							default:
						}
					}
					$text = $text.leafext_java_params ($settings);

			$text = $text.'
			},
			markers: {
				startIconUrl: null, // "http://mpetazzoni.github.io/leaflet-gpx/pin-icon-start.png",
				endIconUrl: null, // "http://mpetazzoni.github.io/leaflet-gpx/pin-icon-end.png",
				shadowUrl: null, // "http://mpetazzoni.github.io/leaflet-gpx/pin-shadow.png",
			},
			legend_options:{
				collapsed: collapse,
			},
			filename_option: ';
			$text = $multioptions['filename'] ?  $text.'true' :  $text.'false';
			$text = $text.',
		};

		var mylocale = {
			"Acceleration"	: "'.__("Acceleration", "extensions-leaflet-map").'",
			"Altitude"		: "'.__("Altitude", "extensions-leaflet-map").'",
			"Slope"			: "'.__("Slope", "extensions-leaflet-map").'",
			"Speed"			: "'.__("Speed", "extensions-leaflet-map").'",
			"Total Time: "      : "'.__("Total Time", "extensions-leaflet-map").': ",
			"Total Length: "	: "'.__("Total Length", "extensions-leaflet-map").': ",
			"Max Elevation: "	: "'.__("Max Elevation", "extensions-leaflet-map").': ",
			"Min Elevation: "	: "'.__("Min Elevation", "extensions-leaflet-map").': ",
			"Total Ascent: "	: "'.__("Total Ascent", "extensions-leaflet-map").': ",
			"Total Descent: "	: "'.__("Total Descent", "extensions-leaflet-map").': ",
			"Min Slope: "	: "'.__("Min Slope", "extensions-leaflet-map").': ",
			"Max Slope: "	: "'.__("Max Slope", "extensions-leaflet-map").': ",
			"Min Speed: "	: "'.__("Min Speed", "extensions-leaflet-map").': ",
			"Max Speed: "	: "'.__("Max Speed", "extensions-leaflet-map").': ",
			"Avg Speed: "	: "'.__("Avg Speed", "extensions-leaflet-map").': ",
			"Min Acceleration: "	: "'.__("Min Acceleration", "extensions-leaflet-map").': ",
			"Max Acceleration: "	: "'.__("Max Acceleration", "extensions-leaflet-map").': ",
			"Avg Acceleration: "	: "'.__("Avg Acceleration", "extensions-leaflet-map").': ",
		};
		L.registerLocale("wp", mylocale);
		L.setLocale("wp");

		console.log(opts.elevation);
		var routes;
		routes = new L.gpxGroup(tracks, {
			points: points,
			points_options: opts.points,
			elevation: true,
			elevation_options: opts.elevation,
			marker_options: opts.markers,
			legend: true,
			distanceMarkers: ';
			$text = $multioptions['distanceMarkers'] ?  $text.'true' :  $text.'false';
			$text = $text.',
			legend_options: opts.legend_options,
			filename: opts.filename_option,
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

	if ( is_array($atts) && array_key_exists('theme', $atts) ) {
		$theme = $atts['theme'];
	} else {
		$theme = leafext_elevation_theme();
	}

	$multioptions = shortcode_atts(leafext_multielevation_settings(), leafext_clear_params($atts));

	// some defaults
	$options = array (
		'speed' =>  false,
		'acceleration' =>  false,
		'time' => false,
		'downloadLink' => false,
		'preferCanvas' => false,
		'legend' => false,
	);
	if ($multioptions['summary']) {
		$options['summary'] = "inline";
		$options['slope'] = "summary";
	} else {
		$options['summary'] = false;
		$options['slope'] = false;
	}
	$multioptions['distanceMarkers'] = false;
	$text = leafext_elevation_tracks_script( $all_files, $all_points, $theme, $options, $multioptions);
	$text = $text.'<p class="chart-placeholder">';
	$text = $text.__("move mouse over a track or select one in control panel ...", "extensions-leaflet-map").'</p>';
	return $text;
}
add_shortcode('elevation-tracks', 'leafext_elevation_tracks' );

//Interpret options like in elevation in [multielevation]
function leafext_multielevation( $atts ){
	leafext_enqueue_elevation ();
	leafext_enqueue_multielevation();
	leafext_enqueue_zoomhome();

	global $all_files;
	global $all_points;

	if ( is_array($atts) && array_key_exists('theme', $atts) ) {
		$theme = $atts['theme'];
	} else {
		$theme = leafext_elevation_theme();
	}

	// filenames as tracknames?
	$multioptions = shortcode_atts(leafext_multielevation_settings(), leafext_clear_params($atts));

	$atts1 = leafext_case(array_keys(leafext_elevation_settings()),leafext_clear_params($atts));
	$options = shortcode_atts(leafext_elevation_settings(), $atts1);

	$multioptions['distanceMarkers'] = $options['distanceMarkers'];

	$options = leafext_eleparams_for_multi($options);

	$text = leafext_elevation_tracks_script( $all_files, $all_points, $theme, $options, $multioptions);
	$text = $text.'<p class="chart-placeholder">';
	$text = $text.__("move mouse over a track or select one in control panel ...", "extensions-leaflet-map").'</p>';
	return $text;
}
add_shortcode('multielevation', 'leafext_multielevation' );
