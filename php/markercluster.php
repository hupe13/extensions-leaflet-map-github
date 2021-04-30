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

	$defaults = get_option('leafext_cluster');
	if ( ! $defaults ) $defaults = array();
	//var_dump($defaults);
	if (!array_key_exists('zoom', $defaults)) $defaults['zoom'] = "17";
	if (!array_key_exists('radius', $defaults)) $defaults['radius'] = "80";
	if (!array_key_exists('spiderfy', $defaults)) $defaults['spiderfy'] = "1";
	
	if (is_array($atts)) {
		foreach ($atts as $attribute => $value) {
			if (is_int($attribute)) {
				$atts[strtolower($value)] = true;
				unset($atts[$attribute]);
			}
		}
		if ( array_key_exists('!spiderfy', $atts) ) {
			$atts['spiderfy'] = false;
			unset($atts['!spiderfy']);
		} else if ( array_key_exists('spiderfy', $atts) ) {
			if ( $atts['spiderfy'] != "" ) {
				$atts['spiderfy'] = (bool)$atts['spiderfy'];
			} else {
				$atts['spiderfy'] = true;
			}
		}
	}
	
	if (!array_key_exists('zoom', $atts)) $atts['zoom'] = $defaults['zoom'];
	if (!array_key_exists('radius', $atts)) $atts['radius'] = $defaults['radius'];
	if (!array_key_exists('spiderfy', $atts)) $atts['spiderfy'] = $defaults['spiderfy'];
	// Uebergabe der php Variablen an Javascript
	wp_localize_script( 'my_markercluster', 'cluster', $atts);
}
add_shortcode('cluster', 'leafext_cluster_function' );
?>
