<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [elevation gpx="...url..."]

function leafext_elevation_script($gpx,$summary,$slope,$theme){
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var elevation_options = {
			//lime-theme (default), magenta-theme, steelblue-theme, purple-theme, yellow-theme, lightblue-theme
			theme: '.json_encode($theme).',
			// Autoupdate map center on chart mouseover.
			followMarker: false,
			legend: false,
			downloadLink:false,
			polyline: { weight: 3, },
			// Summary track info style: "line" || "multiline" || false || inline(?)
			// Slope chart profile: true || "summary" || false
			summary: '.json_encode($summary).',
			slope: '.json_encode($slope).',
		};

		var mylocale = {
			"Altitude"				: "'.__("Altitude", "extensions-leaflet-map").'",
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

		// Instantiate elevation control.
		var controlElevation = L.control.elevation(elevation_options);
		var track_options= { url: "'.$gpx.'" };
		controlElevation.addTo(map);
		// Load track from url (allowed data types: "*.geojson", "*.gpx")
		controlElevation.load(track_options.url);
	});
	</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_elevation_function( $atts ) {
	leafext_enqueue_elevation ();
	//
	$atts=leafext_clear_params($atts);

	$track = shortcode_atts( array('gpx' => false, 'summary' => false), $atts);
	if ( ! $track['gpx'] ) wp_die("No gpx track!");

	//Parameters see the sources from https://github.com/Raruto/leaflet-elevation
	if ( ! $track['summary'] ) {
		$summary = false;
		$slope = false;
	} else {
		$summary = "inline";
		$slope = "summary";
	}

	$theme = leafext_elevation_theme();
	
	return leafext_elevation_script($track['gpx'],$summary,$slope,$theme);
}
add_shortcode('elevation', 'leafext_elevation_function' );
