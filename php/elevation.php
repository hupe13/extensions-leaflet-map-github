<?php
//Shortcode: [elevation gpx="...url..."]
// For use with only one map on a webpage

function leafext_elevation_function( $atts ){
	wp_enqueue_script( 'elevation_js',
		plugins_url('leaflet-plugins/leaflet-elevation-1.6.7/js/leaflet-elevation.min.js',LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'),null);
	wp_enqueue_style( 'elevation_css',
		plugins_url('leaflet-plugins/leaflet-elevation-1.6.7/css/leaflet-elevation.min.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	// language
	$lang = get_locale();
	if ( strlen( $lang ) > 0 ) {
		$lang = explode( '_', $lang )[0];
	}

	// custom js
	wp_enqueue_script('myelevation',
		plugins_url('js/elevation.js',LEAFEXT_PLUGIN_FILE),
		['elevation_js','wp-i18n'] );
	wp_set_script_translations( 'myelevation', 'extensions-leaflet-map', LEAFEXT_PLUGIN_DIR . '/languages/' );
	//wp_set_script_translations( 'myelevation', 'extensions-leaflet-map');
	
	//
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

	$options = get_option('leafext_values');
	if (!is_array($options )) {
		$theme = "lime-theme";
	} else if ($options['theme'] == "other") {
		$theme=$options['othertheme'];
	} else {
		$theme=$options['theme'].'-theme';
	}

	// Uebergabe der php Variablen an Javascript
	wp_localize_script( 'myelevation', 'track', array(
		'gpx' => $track['gpx'],
		'summary' => $summary,
		'slope' => $slope,
		'theme' => $theme,
	));
}
add_shortcode('elevation', 'leafext_elevation_function' );
