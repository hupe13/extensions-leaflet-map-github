<?php
/**
 * main admin page for cluster functions
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

include LEAFEXT_PLUGIN_DIR . '/admin/marker/markercluster.php';
include LEAFEXT_PLUGIN_DIR . '/admin/marker/placementstrategies.php';
include LEAFEXT_PLUGIN_DIR . '/admin/marker/extramarker.php';
include LEAFEXT_PLUGIN_DIR . '/admin/marker/geojsonmarker.php';
include LEAFEXT_PLUGIN_DIR . '/admin/marker/hidemarkers.php';

function leafext_marker_tab() {
	$tabs = array (
		array (
			'tab' => 'extramarker',
			'title' => 'Leaflet.ExtraMarkers',
		),
		array (
			'tab' => 'markercluster',
			'title' => 'Leaflet.markercluster',
		),
		array (
			'tab' => 'markerclusterplacementstrategies',
			'title' => 'Leaflet.MarkerCluster.PlacementStrategies',
		),
		array (
			'tab' => 'markerclustergroup',
			'title' => 'Leaflet.FeatureGroup.SubGroup',
		),
		array (
			'tab' => 'geojsonmarker',
			'title' => 'Markers for Geojson',
		),
		array (
			'tab' => 'hidemarkers',
			'title' => 'Hide Markers',
		),
	);

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : '';
	$textheader = '<div class="nav-tab-wrapper">';

	foreach ( $tabs as $tab) {
		$textheader = $textheader. '<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab='.$tab['tab'].'" class="nav-tab';
		$active = ( $active_tab == $tab['tab'] ) ? ' nav-tab-active' : '' ;
		$textheader = $textheader. $active;
		$textheader = $textheader. '">'.$tab['title'].'</a>'."\n";
	}

	//
	$textheader = $textheader. '</div>';
	return $textheader;
}

function leafext_admin_marker($active_tab) {
	if ( $active_tab ==  'extramarker') {
		echo '<h2>'.leafext_marker_tab().'</h2>';
		leafext_extramarker_help();
	} else if( $active_tab == 'markerclustergroup') {
		echo '<h2>'.leafext_marker_tab().'</h2>';
		include LEAFEXT_PLUGIN_DIR . '/admin/marker/clustergroup.php';
	} else if( $active_tab == 'markercluster' ) {
		leafext_admin_markercluster();
	} else if ( $active_tab == 'markerclusterplacementstrategies' ) {
		leafext_admin_placementstrategies();
	} else if ( $active_tab ==  'extramarker') {
		echo leafext_marker_tab();
		leafext_extramarker_help();
	} else if( $active_tab == 'geojsonmarker') {
		echo '<h2>'.leafext_marker_tab().'</h2>';
		leafext_help_geojsonmarker();
	} else if( $active_tab == 'hidemarkers') {
		echo '<h2>'.leafext_marker_tab().'</h2>';
		leafext_help_hidemarkers();
	}
}

// echo '<a href="?page='.$leafext_plugin_name.'&tab=extramarker" class="nav-tab';
// if ( strpos( $active_tab, 'extramarker' ) !== false ) {
// 	echo ' nav-tab-active';
// }
// echo '">'. __('ExtraMarkers','extensions-leaflet-map'). '</a>'."\n";
