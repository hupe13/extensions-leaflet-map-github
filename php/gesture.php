<?php
// For use with any map on a webpage
function gestures_leaflet_loaded() {
	global $post;
	if ( ! is_page() ) return;
	wp_enqueue_script('gestures_leaflet', 'https://unpkg.com/leaflet-gesture-handling', array('wp_leaflet_map'), '1.0', true);
	//wp_enqueue_style('gestures_leaflet_styles', 'https://unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css');
	// obiges bringt Fehler: Resource interpreted as Stylesheet but transferred with MIME type text/plain
	wp_enqueue_style('gestures_leaflet_styles', 'https://unpkg.com/leaflet-gesture-handling@1.2.1/dist/leaflet-gesture-handling.min.css');
	// custom js
	wp_enqueue_script('gestures_leaflet_custom', plugins_url('js/gesture.js',LEAFEXT__PLUGIN_FILE), array('gestures_leaflet'), '1.0', true);
}

// Enqueue if shortcode exists
add_filter('pre_do_shortcode_tag', function ( $output, $shortcode ) {
	if ( 'leaflet-map' == $shortcode ) {
		gestures_leaflet_loaded();
    }
	return $output;
}, 10, 2);
?>
