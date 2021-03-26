<?php
// For use with any map on a webpage
function leafext_gestures_leaflet_loaded() {
	wp_enqueue_script('gestures_leaflet',
		plugins_url('leaflet-plugins/leaflet-gesture-handling-1.2.1/js/leaflet-gesture-handling.min.js',LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'), null);
	wp_enqueue_style('gestures_leaflet_styles',
		plugins_url('leaflet-plugins/leaflet-gesture-handling-1.2.1/css/leaflet-gesture-handling.min.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	// custom js
	wp_enqueue_script('gestures_leaflet_custom', plugins_url('js/gesture.min.js',LEAFEXT_PLUGIN_FILE), array('gestures_leaflet'), null);
}

// Enqueue if shortcode [leaflet-map] exists
add_filter('pre_do_shortcode_tag', function ( $output, $shortcode ) {
	if ( 'leaflet-map' == $shortcode ) {
		leafext_gestures_leaflet_loaded();
    }
	return $output;
}, 10, 2);
?>
