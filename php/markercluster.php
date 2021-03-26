<?php
//Shortcode: [cluster]
// For use with only one map on a webpage
// For use with more than one map on a webpage
function leafext_cluster_function(){
	wp_enqueue_style( 'markercluster.default',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.0/css/MarkerCluster.Default.min.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_style( 'markercluster',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.0/css/MarkerCluster.min.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_script('markercluster',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.0/js/leaflet.markercluster.js',LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'),null );
	// custom js
	wp_enqueue_script('my_markercluster',
		plugins_url('js/markercluster.min.js',LEAFEXT_PLUGIN_FILE), array('markercluster'),null);
}
add_shortcode('cluster', 'leafext_cluster_function' );
?>
