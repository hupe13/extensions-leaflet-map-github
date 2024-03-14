<?php
/**
 * Main admin page for cluster functions
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

require LEAFEXT_PLUGIN_DIR . '/admin/marker/markercluster.php';
require LEAFEXT_PLUGIN_DIR . '/admin/marker/placementstrategies.php';
require LEAFEXT_PLUGIN_DIR . '/admin/marker/extramarker.php';
require LEAFEXT_PLUGIN_DIR . '/admin/marker/geojsonmarker.php';
require LEAFEXT_PLUGIN_DIR . '/admin/marker/targetmarker.php';
require LEAFEXT_PLUGIN_DIR . '/admin/marker/hidemarkers.php';

function leafext_marker_tab() {
	$tabs = array(
		array(
			'tab'    => 'markercluster',
			'plugin' => 'Leaflet.markercluster',
			'title'  => __( 'Marker Clustering', 'extensions-leaflet-map' ),
		),
		array(
			'tab'    => 'markerclustergroup',
			'plugin' => 'Leaflet.FeatureGroup.SubGroup',
			'title'  => __( 'Clustering and Grouping of Markers', 'extensions-leaflet-map' ),
		),
		array(
			'tab'    => 'markerclusterplacementstrategies',
			'title'  => __( 'Styling Markercluster', 'extensions-leaflet-map' ),
			'plugin' => 'Leaflet.MarkerCluster.PlacementStrategies',
		),
		array(
			'tab'    => 'extramarker',
			'title'  => 'Font Awesome Icons',
			'plugin' => 'Leaflet.ExtraMarkers',
		),
		array(
			'tab'   => 'geojsonmarker',
			'title' => __( 'Design and Group markers from geojson files', 'extensions-leaflet-map' ),
		),
		array(
			'tab'   => 'targetmarker',
			'title' => __( 'Target Marker', 'extensions-leaflet-map' ),
		),
		array(
			'tab'   => 'hidemarkers',
			'title' => __( 'Hide Markers', 'extensions-leaflet-map' ),
		),
	);

	//phpcs:disable WordPress.Security.NonceVerification.Recommended -- no form
	$get        = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	$active_tab = isset( $get['tab'] ) ? $get['tab'] : '';
	$textheader = '<div class="nav-tab-wrapper">';

	foreach ( $tabs as $tab ) {
		$textheader = $textheader . '<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=' . $tab['tab'] . '" class="nav-tab';
		$active     = ( $active_tab == $tab['tab'] ) ? ' nav-tab-active' : '';
		$textheader = $textheader . $active;
		$textheader = $textheader . '">' . $tab['title'] . '</a>' . "\n";
	}

		$textheader = $textheader . '</div>';
	return $textheader;
}

function leafext_admin_marker( $active_tab ) {
	if ( $active_tab == 'markercluster' ) {
		leafext_admin_markercluster();
	} elseif ( $active_tab == 'markerclustergroup' ) {
		echo '<h2>' . leafext_marker_tab() . '</h2>';
		include LEAFEXT_PLUGIN_DIR . '/admin/marker/clustergroup.php';
	} elseif ( $active_tab == 'extramarker' ) {
		echo '<h2>' . leafext_marker_tab() . '</h2>';
		leafext_extramarker_help();
	} elseif ( $active_tab == 'markerclusterplacementstrategies' ) {
		leafext_admin_placementstrategies();
	} elseif ( $active_tab == 'extramarker' ) {
		echo leafext_marker_tab();
		leafext_extramarker_help();
	} elseif ( $active_tab == 'geojsonmarker' ) {
		echo '<h2>' . leafext_marker_tab() . '</h2>';
		leafext_help_geojsonmarker();
	} elseif ( $active_tab == 'targetmarker' ) {
		leafext_targetmarker_help();
	} elseif ( $active_tab == 'hidemarkers' ) {
		echo '<h2>' . leafext_marker_tab() . '</h2>';
		leafext_help_hidemarkers();
	}
}

// echo '<a href="?page='.$leafext_plugin_name.'&tab=extramarker" class="nav-tab';
// if ( strpos( $active_tab, 'extramarker' ) !== false ) {
// echo ' nav-tab-active';
// }
// echo '">'. __('ExtraMarkers','extensions-leaflet-map'). '</a>'."\n";
