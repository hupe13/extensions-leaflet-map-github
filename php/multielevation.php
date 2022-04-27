<?php
/**
 * Functions for multielevation
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_multielevation_params($typ = array('changeable')) {
	$params = array (
		array(
			'param' => 'filename',
			'shortdesc' => __('Filename as trackname',"extensions-leaflet-map"),
			'desc' => __('Use filename (without extension) as name of the track.',"extensions-leaflet-map"),
			'default' => false,
			'values' => 1,
			'typ' => array('changeable','point','multielevation','tracks'),
		),
		array(
			'param' => 'summary',
			'shortdesc' => __('Summary',"extensions-leaflet-map"),
			'desc' =>	sprintf ( __('Valid for %s: Only elevation profile with or without summary line will be displayed.',"extensions-leaflet-map"),
				'<code>[elevation-<span style="color: #d63638">tracks</span>]</code>'),
			'default' => true,
			'values' => 1,
			'typ' => array('changeable','tracks'),
		),

		//     highlight: {
		//       color: '#ff0',
		//       opacity: 1,
		//     },
		array(
			'param' => 'highlight',
			'shortdesc' => __('Highlight color',"extensions-leaflet-map"),
			'desc' =>	'The active track is displayed in this color.',
			'default' => '#ffff00',
			'values' => "color",
			'typ' => array('changeable','multielevation'),
		),

		// flyToBounds: true,
		// array(
		// 	'param' => 'flyToBounds',
		// 	'shortdesc' => __('flyToBounds',"extensions-leaflet-map"),
		// 	'desc' =>	'',
		// 	'default' => true,
		// 	'values' => 1,
		// 	'typ' => array('changeable','multielevation'),
		// ),

		//     distanceMarkers: true,
		//     distanceMarkers_options: {
		//       lazy: true
		//     },
		array(
			'param' => 'distanceMarkers',
			'shortdesc' => __('Toggle "leaflet-distance-markers" integration',"extensions-leaflet-map"),
			'desc' =>	'',
			'default' => true,
			'values' => 1,
			'typ' => array('changeable','multielevation'),
		),

	);

	if (count($typ) > 0) {
		$options = array();
		foreach ($typ as $type) { //"fixed", "changeable", ""
			foreach($params as $key => $param){
				if ( in_array ($type, $params[$key]['typ'])) $options[] = $params[$key];
			}
		}
		return $options;
	}
	return $params;
}

// immer true, oder?
//     points: [],
//     points_options: {
//       icon: {
//         iconUrl: '../images/elevation-poi.png',
//         iconSize: [12, 12],
//       }
//     },

// fest aber true
//     legend: false,
//     legend_options: {
//       position: "topright",
//       collapsed: false,
//     },

function leafext_eleparams_for_multi($options=array()) {
		$multi=array();
		$params = leafext_elevation_params(array("multielevation"));
		foreach($params as $param) {
			$multi[] = $param["param"];
		}
		//var_dump($multi);

	if (count($options) > 1 ) {
		$multioptions = array();
		foreach ($multi as $param) {
			$multioptions[$param] = $options[$param];
		}
		//var_dump($multioptions); wp_die();
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

function leafext_multielevation_settings($typ=array('changeable')) {
	$defaults=array();
	$params = leafext_multielevation_params($typ);
	foreach($params as $param) {
		$defaults[$param['param']] = $param['default'];
	}
	$options = shortcode_atts($defaults, get_option('leafext_multieleparams'));
	return $options;
}

//Shortcode:
//[elevation-track file="'.$file.'" lat="'.$startlat.'" lng="'.$startlon.'" name="'.basename($file).'" filename=true/false]
// lat lng name optional
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

	// filenames as tracknames?

	if ( $params['name'] == "" ) {
		$multioptions = shortcode_atts(leafext_multielevation_settings(array('point')), leafext_clear_params($atts));
		//var_dump($multioptions);
		if ( boolval($multioptions['filename'])) {
			$path_parts = pathinfo($atts['file']);
			$params['name'] = $path_parts['filename'];
		} else {
			$params['name'] = (string) $gpx->trk->name;
		}
		//Fallback
		if ( $params['name'] == "" ) {
			$path_parts = pathinfo($atts['file']);
			$name = $path_parts['filename'];
		}
	}

	$point = array(
		'latlng' => $latlng,
		'name' 	 => $params['name'],
	);

	$all_points[] = $point;
	$all_files[] = $atts['file'];
}
add_shortcode('elevation-track', 'leafext_elevation_track' );

//[elevation-tracks summary=0/1]
//{multielvation ...}
function leafext_multielevation( $atts, $contents, $shortcode){
	leafext_enqueue_elevation ();
	leafext_enqueue_multielevation();
	leafext_enqueue_zoomhome();

	global $all_files;
	global $all_points;

	$ele_options = array(
		'detachedView' => true,
		'elevationDiv' => "#elevation-div",
		'zFollow' => 15,
		'flyToBounds' => true,
		'legend' => false,
	);

	if ( is_array($atts) && array_key_exists('theme', $atts) ) {
		$ele_options['theme'] = $atts['theme'];
	} else {
		$ele_options['theme'] = leafext_elevation_theme();
	}

	if ($shortcode == "elevation-tracks") {
		$options = array (
			'acceleration' =>  false,
			'almostOver' => true,
			'downloadLink' => false,
			'followMarker' => false,
			'legend' => false,
			'preferCanvas' => false,
			'speed' =>  false,
			'time' => false,
			'height' => 200,
		);
		$multioptions = shortcode_atts(leafext_multielevation_settings(array('tracks')), leafext_clear_params($atts));
		if ($multioptions['summary']) {
			$options['summary'] = "inline";
			$options['slope'] = "summary";
		} else {
			$options['summary'] = false;
			$options['slope'] = false;
		}
		$options = array_merge($options, $ele_options);
		$multioptions['distanceMarkers'] = false;
	}

	if ($shortcode == "multielevation" ) {
		$atts1 = leafext_case(array_keys(leafext_elevation_settings(array("multielevation"))), leafext_clear_params($atts));
		$options = shortcode_atts(leafext_elevation_settings(array("multielevation")), $atts1);

		if (isset($options['pace']) ) {
			if (isset($options['pace']) ) {
				$options = leafext_elevation_pace($options);
			}
		}

		$multioptions = shortcode_atts(leafext_multielevation_settings(array('multielevation')), leafext_clear_params($atts));
		if (isset($multioptions['highlight']) ) {
				$multioptions['highlight'] = "{color: '".$multioptions['highlight']."',opacity: 1,}";
		}

		//var_dump(leafext_multielevation_settings(array('multielevation')),$multioptions); wp_die();
	}

	$options = array_merge($options, $ele_options);

	$rand = rand(1,20);
	$text = leafext_multielevation_script( $all_files, $all_points, $options, $multioptions, $rand);

	$text = $text.'<p class="chart-placeholder chart-placeholder-'.$rand.'">';
	$text = $text.__("move mouse over a track or select one in control panel ...", "extensions-leaflet-map").'</p>';
	$all_files = array();
	$all_points = array();
	return $text;
}
add_shortcode('elevation-tracks', 'leafext_multielevation');
add_shortcode('multielevation', 'leafext_multielevation');

function leafext_multielevation_script( $all_files, $all_points, $settings, $multioptions, $rand ) {
	//var_dump($settings,$multioptions); wp_die();
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();

		var points = '.json_encode($all_points).';
		var tracks = '.json_encode($all_files).';
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
	';

			list($text1, $settings) = leafext_ele_java_params($settings);
			$text = $text.$text1;
			$text = $text.leafext_java_params ($settings);

			$text = $text.'
			},
		};
		console.log(opts.elevation);';

		$text = $text.leafext_elevation_locale();

		$text = $text.'
		let routes;

		import("'.LEAFEXT_ELEVATION_URL.'libs/leaflet-gpxgroup.js");

		routes = L.gpxGroup(tracks, {
			async: false,
			points: points,
			points_options: opts.points,
			elevation: true,
			elevation_options: opts.elevation,
			legend: true,
			legend_options: {
	      position: "topright",
	      collapsed: true,
	    },';
			$text = $text.leafext_java_params ($multioptions);
			$text = $text.'
		});
		console.log(routes);
		routes.addTo(map);

		L.Control.Elevation.prototype.__btnIcon = "'.LEAFEXT_ELEVATION_URL.'/images/elevation.svg";
		map.on("eledata_added eledata_clear", function(e) {
			var p = document.querySelector(".chart-placeholder-'.$rand.'");
			if(p) {
				p.style.display = e.type=="eledata_added" ? "none" : "";
			}
		});

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
//$text = \JShrink\Minifier::minify($text);
return "\n".$text."\n";
}
