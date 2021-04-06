<?php
//Shortcode: [markerClusterGroup]
function clustergroup_function( $atts ){
	wp_enqueue_style( 'markercluster.default',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.0/css/MarkerCluster.Default.min.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_style( 'markercluster',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.0/css/MarkerCluster.min.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_script('markercluster',
		plugins_url('leaflet-plugins/leaflet.markercluster-1.5.0/js/leaflet.markercluster.js',LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'),null );
	wp_enqueue_script('leaflet.subgroup',
		plugins_url('../js/leaflet.featuregroup.subgroup.js',__FILE__),
		array('markercluster'),null);

	// custom js
	wp_enqueue_script('featuregroup_custom', esc_url( plugins_url( 'js/featuregroup.js',
  		dirname(__FILE__) ) ), Array('leaflet.subgroup'), null);

	$featuregroups = shortcode_atts( array('feat' => false, 'strings' => false, 'groups' => false), $atts);
	//feat="iconUrl" strings="red green" groups="rot gruen"

	$cl_strings= array_map('trim', explode( ',', $featuregroups['strings'] ));
	$cl_groups = array_map('trim', explode( ',', $featuregroups['groups'] ));
	if ( count( $cl_strings ) != count( $cl_groups ) ) wp_die("strings and groups do not match.");

	//Uebergabe der php Variablen an Javascript
	wp_localize_script( 'featuregroup_custom', 'featuregroups',
		array(
			'feat' => sanitize_text_field($featuregroups['feat']),
			'groups' => array_combine($cl_strings, $cl_groups),
		)
	);

}
add_shortcode('markerClusterGroup', 'clustergroup_function' );
?>
