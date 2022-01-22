<?php
function leafext_dequeue_leaflet() {
  wp_dequeue_script('wp_leaflet_map');
  wp_dequeue_style('leaflet_stylesheet');
}
add_action( 'wp_enqueue_scripts', 'leafext_dequeue_leaflet' , 100);

add_filter('pre_do_shortcode_tag', function ( $output, $shortcode ) {
	if ( 'leaflet-map' == $shortcode ) {
    wp_enqueue_script('wp_leaflet_map');
    wp_enqueue_style('leaflet_stylesheet');
	}
	return $output;
}, 10, 2);
