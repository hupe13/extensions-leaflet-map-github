<?php
//Shortcode: [cluster]
// For use with only one map on a webpage
// For use with more than one map on a webpage
function cluster_function(){
	global $post;
	if ( ! is_page() ) return;
	wp_enqueue_style( 'markercluster.default',
		'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css',
			array('leaflet_stylesheet'));
	wp_enqueue_style( 'markercluster',
		'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css',
			array('leaflet_stylesheet'));
	wp_enqueue_script('markercluster',
		'https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js',
			array('leaflet_js'),null );
	// custom js
	wp_enqueue_script('my_markercluster',
		plugins_url('js/markercluster.js',CODESNIPPETS__PLUGIN_FILE), array('markercluster'),null);
}
add_shortcode('cluster', 'cluster_function' );
?>
