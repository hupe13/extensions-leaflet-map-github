<?php
//Shortcode: [cluster]
// For use with only one map on a webpage
// For use with more than one map on a webpage
function leafext_cluster_function( $atts ){
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
	// wp_enqueue_script('my_markercluster',
	// 	plugins_url('js/markercluster.min.js',LEAFEXT_PLUGIN_FILE), array('markercluster'),null);
	wp_enqueue_script('my_markercluster',
		plugins_url('js/markercluster.min.js',LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'), null);

	$defaults = get_option('leafext_cluster');
	if ( ! $defaults ) $defaults = array();
	//var_dump($defaults);
	if (!array_key_exists('zoom', $defaults)) $defaults['zoom'] = "17";
	if (!array_key_exists('radius', $defaults)) $defaults['radius'] = "50";

	$cluster_options = shortcode_atts( array('zoom' => $defaults['zoom'], 'radius' => $defaults['radius']), $atts);
	//var_dump($cluster_options); wp_die();

	// Uebergabe der php Variablen an Javascript
	wp_localize_script( 'my_markercluster', 'cluster', $cluster_options);
}
add_shortcode('cluster', 'leafext_cluster_function' );
?>
