<?php
/**
 * Functions for elevation shortcode
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_elevation_params() {
	$params = array(

// Aussehen

		// Default chart colors: theme lime-theme, magenta-theme, ...
		//theme: "lightblue-theme",
		array('theme', __('Theme Colors',"extensions-leaflet-map"), "lime-theme",
			array("lime-theme","steelblue-theme","purple-theme","yellow-theme","red-theme","magenta-theme","lightblue-theme")),

		//Default hupe13 polyline: { weight: 3, }, Default Raruto 5
		array('polyline', __('Polyline weight',"extensions-leaflet-map"), "{ weight: 3, }", array("{ weight: 3, }","{ weight: 4, }","{ weight: 5, }")),

		// https://github.com/Raruto/leaflet-elevation/issues/86#issuecomment-735274347
		// marker: "elevation-line" || "position-marker" || false
		array('marker', __('position/height indicator marker drawn onto the map',"extensions-leaflet-map"), 'elevation-line', array("elevation-line", "position-marker",false)),

		//https://github.com/Raruto/leaflet-elevation/issues/120#issuecomment-916958969
		// Display track waypoints: (true || false) - waypoints: false,
		// plugins default true
		array('waypoints', __('Display track waypoints.',"extensions-leaflet-map"), true, 1),
		
		// Toggle "leaflet-distance-markers" integration
		//distanceMarkers: false,
		array('distanceMarkers', __('Toggle "leaflet-distance-markers" integration',"extensions-leaflet-map"), false, 1),

// Graphen

		// Acceleration chart profile: true || "summary" || "disabled" || false
		//acceleration: false,
		//array('acceleration', __('Acceleration chart profile',"extensions-leaflet-map"), false, array(true,"summary","disabled",false)),
		array('acceleration', __('Acceleration chart profile',"extensions-leaflet-map"), false, array("summary","disabled",false)),

		// Slope chart profile: true || "summary" || "disabled" || false
		//slope: false,
		array('slope', __('Slope chart profile',"extensions-leaflet-map"), false, array(true,"summary","disabled",false)),

		// Speed chart profile: true || "summary" || "disabled" || false
		//speed: false,
		array('speed', __('Speed chart profile',"extensions-leaflet-map"), false, array(true,"summary","disabled",false)),
		
// Informationen

		// Display time info: true || "summary" || false
		//time: false,
		array('time', __('Display time info',"extensions-leaflet-map"), false, array(true,"summary",false)),

		// Display distance info: true || "summary"
		//distance: true,
		array('distance', __('Display distance info',"extensions-leaflet-map"), true, array(true,"summary")),

		// Display altitude info: true || "summary"
		//altitude: true,
		array('altitude', __('Display altitude info',"extensions-leaflet-map"), true, array(true,"summary")),
		
		// Summary track info style: "line" || "multiline" || false
		//Is this an error: line/inline ?
		//summary: 'multiline',
		//hupe13: true historical
		array('summary', __('Summary track info style:',"extensions-leaflet-map"), 'multiline', array(true, "inline","multiline",false)),

		// Toggle chart legend filter.
		//legend: true,
		array('legend', __('Toggle chart legend filter.',"extensions-leaflet-map"), true, 1),
		
//

		// Toggle chart. https://github.com/Raruto/leaflet-elevation/discussions/139
		//chart: true, 
		array('chart', __('Display / Hide chart.',"extensions-leaflet-map"), true, array(true, "on", "off")),
		
// Zusaetze

		//hupe13: Download Link
		array('downloadLink', __('downloadLink',"extensions-leaflet-map"), false, 1),
		
// Verhalten u.a.
		
		// Autoupdate map center on chart mouseover.
		//followMarker: true,
		array('followMarker', __('Autoupdate map center on chart mouseover.',"extensions-leaflet-map"), true, 1),

		// Autoupdate map bounds on chart update.
		//autofitBounds: true,
		array('autofitBounds', __('Autoupdate map bounds on chart update.',"extensions-leaflet-map"), true, 1),

		// Chart distance/elevation units.
		//imperial: false,
		array('imperial', __('Chart distance/elevation units.',"extensions-leaflet-map"), false, 1),

		// [Lat, Long] vs [Long, Lat] points. (leaflet default: [Lat, Long])
		//reverseCoords: false,
		array('reverseCoords', __('[Lat, Long] vs [Long, Lat] points. (leaflet default: [Lat, Long])',"extensions-leaflet-map"), false, 1),

		// Toggle chart ruler filter.
		//ruler: true,
		//array('ruler', __('Toggle chart ruler filter.',"extensions-leaflet-map"), true, 1),

		// Toggle "leaflet-almostover" integration
		//almostOver: true,
		array('almostOver', __('Toggle "leaflet-almostover" integration',"extensions-leaflet-map"), true, 1),

		// Render chart profiles as Canvas or SVG Paths
		//preferCanvas: true
		array('preferCanvas', __('Render chart profiles as Canvas or SVG Paths.',"extensions-leaflet-map"), true, 1),
		
		// Chart container outside/inside map container
		//detached: true,
		//array('detached', 'Chart container outside/inside map container', true, 1),

		// if (detached), the elevation chart container
		//elevationDiv: "#elevation-div",

		// if (!detached) autohide chart profile on chart mouseleave
		//autohide: false,
		//array('autohide', 'if (!detached) autohide chart profile on chart mouseleave', false, 1),

		// if (!detached) initial state of chart profile control
		//collapsed: false,
		//array('collapsed', 'if (!detached) initial state of chart profile control', false, 1),

		// if (!detached) control position on one of map corners
		//position: "topright",

	);
	return $params;
}

//Shortcode: [elevation gpx="...url..."]

function leafext_elevation_script($gpx,$theme,$settings,$chart){
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var elevation_options = {
		//lime-theme (default), magenta-theme, steelblue-theme, purple-theme, yellow-theme, lightblue-theme
			theme: '.json_encode($theme).',
		';
		//old settings
		if ( $settings['summary'] == "1" ) {
			$text = $text.'
				summary: "inline",
				slope: "summary",
				speed:  false,
				acceleration:  false,
				time: false,
				downloadLink: false,
				preferCanvas: false,
				legend: false,
				polyline:  { weight: 3, },
				';
				//old settings end
		} else {
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

		}//old settings end

	$text = $text.'	};
		var mylocale = {
			"Altitude"	: "'.__("Altitude", "extensions-leaflet-map").'",
			"Total Length: "	: "'.__("Total Length", "extensions-leaflet-map").': ",
			"Max Elevation: "	: "'.__("Max Elevation", "extensions-leaflet-map").': ",
			"Min Elevation: "	: "'.__("Min Elevation", "extensions-leaflet-map").': ",
			"Total Ascent: "	: "'.__("Total Ascent", "extensions-leaflet-map").': ",
			"Total Descent: "	: "'.__("Total Descent", "extensions-leaflet-map").': ",
			"Min Slope: "	: "'.__("Min Slope", "extensions-leaflet-map").': ",
			"Max Slope: "	: "'.__("Max Slope", "extensions-leaflet-map").': ",
			"Speed: "	: "'.__("Speed", "extensions-leaflet-map").': ",
			"Min Speed: "	: "'.__("Min Speed", "extensions-leaflet-map").': ",
			"Max Speed: "	: "'.__("Max Speed", "extensions-leaflet-map").': ",
			"Avg Speed: "	: "'.__("Avg Speed", "extensions-leaflet-map").': ",
			"Acceleration: "	: "'.__("Acceleration", "extensions-leaflet-map").': ",
			"Min Acceleration: "	: "'.__("Min Acceleration", "extensions-leaflet-map").': ",
			"Max Acceleration: "	: "'.__("Max Acceleration", "extensions-leaflet-map").': ",
			"Avg Acceleration: "	: "'.__("Avg Acceleration", "extensions-leaflet-map").': ",
		};
		L.registerLocale("wp", mylocale);
		L.setLocale("wp");

		// Instantiate elevation control.
		var controlElevation = L.control.elevation(elevation_options);
		var track_options= { url: "'.$gpx.'" };
		controlElevation.addTo(map);';
		
		if ($chart != "1") {
		$text=$text.'var controlButton = L.easyButton(
			"<i class=\"fa fa-area-chart\" aria-hidden=\"true\"></i>",
			function(btn, map) { 
				controlElevation._toggle(); },
				"Elevation",
				{ position: "topright" }
				).addTo( map );';
		}
		
		$text=$text.'
		// Load track from url (allowed data types: "*.geojson", "*.gpx")
		controlElevation.load(track_options.url);';

		if (is_string($chart)) {
		$text=$text.'map.on("eledata_added", function(e) {
			//console.log("eledata_added");
			//Ja 2x!!! Koennte man als Parameter setzen
			controlElevation._toggle();';
			if ($chart == "off") {
				$text=$text.'
				controlElevation._toggle();';
			}
			$text=$text.'
		});';
		}
	$text=$text.'	
	});
	</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_elevation_settings() {
	$defaults=array();
	$params = leafext_elevation_params();
	foreach($params as $param) {
		$defaults[$param[0]] = $param[2];
	}
	$options = shortcode_atts($defaults, get_option('leafext_eleparams'));
	return $options;
}

function leafext_elevation_theme() {
	$ownoptions = get_option('leafext_values');
	if (is_array($ownoptions)) {
		if ( $ownoptions['theme'] == 'other' ) {
			$theme = $ownoptions['othertheme'];
		} else {
			$theme=$ownoptions['theme'].'-theme';
		}
	} else {
		$newoptions=leafext_elevation_settings();
		$theme = $newoptions['theme'];
	}
	return($theme);
}	

function leafext_elevation_function( $atts ) {
	if ( ! $atts['gpx'] ) {
		$text = "[elevation ";
		foreach ($atts as $key=>$item){
			$text = $text. "$key = $item ";
		}
		$text = $text. "]";
		return $text;
	}
	leafext_enqueue_elevation ();
	
	$atts1=leafext_case(array_keys(leafext_elevation_settings()),leafext_clear_params($atts));
	$options = shortcode_atts(leafext_elevation_settings(), $atts1);

	$track = $atts['gpx'];
	
	if ( array_key_exists('theme', $atts) ) {
		$theme = $atts['theme'];
	} else {
		$theme = leafext_elevation_theme();
	}
	unset($options['theme']);
	
	$chart = $options['chart'];
	if ( $chart != "1" ) {
		leafext_enqueue_easybutton();
	}
	unset($options['chart']);
	
	return leafext_elevation_script($track,$theme,$options,$chart);
}
add_shortcode('elevation', 'leafext_elevation_function' );
