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
	wp_enqueue_script('my_markercluster',
		plugins_url('js/markercluster.min.js',LEAFEXT_PLUGIN_FILE), array('markercluster'),null);

	$defaults = array(
		'zoom'     => "17",
		'radius'   => "80",
		'spiderfy' => 1,
	);
	$def_settings = get_option('leafext_cluster');
	$params = shortcode_atts($defaults, $def_settings);
	$params = shortcode_atts($params, $atts);
	$params['spiderfy'] = ((bool)$params['spiderfy']) ? "true" : "false";
	
	// Uebergabe der php Variablen an Javascript
	wp_localize_script( 'my_markercluster', 'cluster', $params);
}
add_shortcode('cluster', 'leafext_cluster_function' );
?>
