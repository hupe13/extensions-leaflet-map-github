<?php
/**
 * main admin page for cluster functions
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

include LEAFEXT_PLUGIN_DIR . '/admin/cluster/markercluster.php';
//include LEAFEXT_PLUGIN_DIR . '/admin/cluster/markerclusterold.php';
include LEAFEXT_PLUGIN_DIR . '/admin/cluster/placementstrategies.php';

function leafext_cluster_tab() {
	$tabs = array (
		array (
			'tab' => 'markercluster',
			'title' => 'Leaflet.markercluster',
		),
		array (
			'tab' => 'clustergroup',
			'title' => 'Leaflet.FeatureGroup.SubGroup',
		),
		array (
			'tab' => 'clusterplacementstrategies',
			'title' => 'Leaflet.MarkerCluster.PlacementStrategies',
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

function leafext_admin_cluster($active_tab) {
	if( $active_tab == 'clustergroup') {
		echo '<h2>'.leafext_cluster_tab().'</h2>';
		include LEAFEXT_PLUGIN_DIR . '/admin/cluster/clustergroup.php';
	} else if( $active_tab == 'markercluster' ) {
		leafext_admin_markercluster();
	} else if ( $active_tab == 'clusterplacementstrategies' ) {
		leafext_admin_placementstrategies();
	}
}
