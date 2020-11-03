<?php
//Shortcode: [elevation]
// For use with only one map on a webpage

function elevation_function( $atts ){
	global $post;
	if ( ! is_page() ) return;
	wp_enqueue_script( 'elevation_js',
  	'https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.min.js',
			array('leaflet_js'),null);
	wp_enqueue_style( 'elevation_css',
	'https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.min.css',
	array('leaflet_stylesheet'));
	// custom js
	wp_enqueue_script('myelevation',
		plugins_url('js/elevation.js',CODESNIPPETS__PLUGIN_FILE), array('elevation_js'), '1.0', true);
	//
	$track = shortcode_atts( array('gpx' => false, 'summary' => false), $atts);
	if ( ! $track['gpx'] ) wp_die();

	if ( ! $track['summary'] ) {
		$summary = false;
		$slope = false;
	} else {
		$summary = "inline";
		$slope = "summary";
	}
	$options = get_option('wp_leaflet_ext');
	if (!is_array($options )) {
		$theme = "lime-theme";
	} else if ($options[theme] == "other") {
		$theme=$options['othertheme'];
	} else {
		$theme=$options[theme].'-theme';
	}
	
	wp_localize_script( 'myelevation', 'track', array(
		'gpx' => $track['gpx'],
		'summary' => $summary,
		'slope' => $slope,
		'theme' => $theme,
	));
}
add_shortcode('elevation', 'elevation_function' );
