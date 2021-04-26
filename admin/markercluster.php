<?php
// init settings fuer cluster
function leafext_cluster_init(){
	add_settings_section( 'cluster_settings', 'Marker Cluster', 'leafext_cluster_text', 'leafext_settings_cluster' );
	add_settings_field( 'leafext_cluster_disableClusteringAtZoom', 'disableClusteringAtZoom', 'leafext_form_cluster_disableClusteringAtZoom', 'leafext_settings_cluster', 'cluster_settings' );
	add_settings_field( 'leafext_cluster_maxClusterRadius', 'maxClusterRadius', 'leafext_form_cluster_maxClusterRadius', 'leafext_settings_cluster', 'cluster_settings' );
	register_setting( 'leafext_settings_cluster', 'leafext_cluster', 'leafext_validate_cluster' );
}
add_action('admin_init', 'leafext_cluster_init' );

//get Options
function leafext_form_cluster_get_options($reset=false) {
	if ( ! $reset) $options = get_option('leafext_cluster');
	if ( ! $options ) $options = array();
	//var_dump($options);
	if (!array_key_exists('zoom', $options)) $options['zoom'] = "17";
	if (!array_key_exists('radius', $options)) $options['radius'] = "80";
	//var_dump($options);
	return $options;
}

// Baue Abfrage standard zoom
function leafext_form_cluster_disableClusteringAtZoom() {
	//echo "leafext_form_cluster_disableClusteringAtZoom";
	$options = leafext_form_cluster_get_options();
	//var_dump($options);
	echo '<p>'.__('At this zoom level and below, markers will not be clustered. See', 'extensions-leaflet-map').' <a href="https://leaflet.github.io/Leaflet.markercluster/example/marker-clustering-realworld-maxzoom.388.html">'.__('Example','extensions-leaflet-map').'</a>. '.__('Plugins Default','extensions-leaflet-map').': 17</p>';
	echo '<input type="number" name="leafext_cluster[zoom]" value="'.$options['zoom'].'" min="1" max="19" />';
}

function leafext_form_cluster_maxClusterRadius() {
	//echo "leafext_form_cluster_maxClusterRadius";
	$options = leafext_form_cluster_get_options();
	//var_dump($options);
	echo '<p>'.__('The maximum radius that a cluster will cover from the central marker (in pixels). Decreasing will make more, smaller clusters. Default','extensions-leaflet-map').': 80.</p>';
	echo '<input type="number" name="leafext_cluster[radius]" value="'.$options['radius'].'" min="10" />';
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function leafext_validate_cluster($input) {
	if (isset($_POST['submit'])) {
		//echo "submit";
		if( isset( $input['zoom'] ) && $input['zoom'] != "" &&
			isset( $input['radius'] ) && $input['radius'] != ""  ) {
			$input['zoom'] = intval($input['zoom']);
			$input['radius'] = intval($input['radius']);
		} else {
			$input = array();
			$input = leafext_form_cluster_get_options(1);
		}
	}
	return $input;
}

// Erklaerung
function leafext_cluster_text() {
    echo '<p>'.__('I found it useful to change these values for my website. If you want to change <a href="https://github.com/Leaflet/Leaflet.markercluster#options">other options</a>, please tell me','extensions-leaflet-map').'.</p>';
		echo '<p>'.__('To reset all values to their defaults, simply clear the values','extensions-leaflet-map').'.</p>';
}
