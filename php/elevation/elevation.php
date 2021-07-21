<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

include_once LEAFEXT_PLUGIN_DIR . '/php/elevation/elevation_functions.php';

//Shortcode: [elevation gpx="...url..."]

function leafext_elevation_script($gpx,$theme,$settings){
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
				polyline:  { weight: 3, },
				';
				//old settings end
		} else {
		foreach ($settings as $k => $v) {
			$text = $text. "$k: ";
			switch (gettype($v)) {
				case "string":
					switch ($v) {
						case "0": $value = "false"; break;
						case "1": $value = "true"; break;
						default: $value = '"'.$v.'"';break;
					}
					break;
				case "boolean":
				case "integer":
					$value = $v ? "true" : "false"; break;
				default: //var_dump(gettype($v)); wp_die();
			}
			switch ($k) {
				case "polyline": $value = $v; break;
			}

			$text = $text.$value;
			$text = $text.",\n";
		}
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
		controlElevation.addTo(map);
		// Load track from url (allowed data types: "*.geojson", "*.gpx")
		controlElevation.load(track_options.url);

	});
	</script>';
	//$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_elevation_function( $atts ) {
	leafext_enqueue_elevation ();
	//
	$atts1=leafext_elevation_case(leafext_clear_params($atts));
	$options = shortcode_atts( array_merge(array('gpx' => false),leafext_elevation_settings()), $atts1);
	if ( ! $options['gpx'] ) wp_die("No gpx track!");
	$track = $options['gpx'];
	unset($options['gpx']);
	if ( array_key_exists('theme', $atts) ) {
		$theme = $atts['theme'];
	} else {
		$theme = leafext_elevation_theme();
	}
	unset($options['theme']);

	return leafext_elevation_script($track,$theme,$options);
}
add_shortcode('elevation', 'leafext_elevation_function' );
