<?php
/**
 * main admin page for elevation functions
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

include LEAFEXT_PLUGIN_DIR . '/admin/elevation/elevation.php';
include LEAFEXT_PLUGIN_DIR . '/admin/elevation/owntheme.php';
include LEAFEXT_PLUGIN_DIR . '/admin/elevation/sgpx.php';
include LEAFEXT_PLUGIN_DIR . '/admin/elevation/multielevation.php';

function leafext_elevation_tab() {
	$tabs = array (
		array (
			'tab' => 'elevation',
			'title' => __('Elevation Profile','extensions-leaflet-map'),
			),
		array (
			'tab' => 'multielevation',
			'title' => __('Multiple hoverable tracks','extensions-leaflet-map'),
		),
		array (
			'tab' => 'elevationtheme',
			'title' => __('Own theme','extensions-leaflet-map'),
		),
	);

	if ( LEAFEXT_SGPX_ACTIVE || LEAFEXT_SGPX_UNCLEAN_DB || LEAFEXT_SGPX_SGPX ) {
		$tabs[] = array (
			'tab' => 'sgpxelevation',
			'title' => __('Migrate from wp-gpx-maps','extensions-leaflet-map'),
		);
	}

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

function leafext_admin_elevation($active_tab) {
	if( $active_tab == 'multielevation') {
		echo '<form method="post" action="options.php">';
		settings_fields('leafext_settings_multieleparams');
		do_settings_sections( 'leafext_settings_multieleparams' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		echo '</form>';
	} else if( $active_tab == 'elevationtheme') {
		echo '<form method="post" action="options.php">';
		settings_fields('leafext_settings_theme');
		do_settings_sections( 'leafext_settings_theme' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		echo '</form>';
	} else if( $active_tab == 'sgpxelevation' ) {

		echo '<form method="post" action="options.php">';
		if ( LEAFEXT_SGPX_ACTIVE ) {
			settings_fields('leafext_settings_sgpxparams');
			do_settings_sections( 'leafext_settings_sgpxparams' );
			submit_button();
		} else if ( LEAFEXT_SGPX_UNCLEAN_DB ) {
			settings_fields('leafext_settings_sgpx_unclean_db');
			do_settings_sections( 'leafext_settings_sgpx_unclean_db' );
			submit_button( __( 'Delete all settings from wp-gpx-maps!', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		} else if ( LEAFEXT_SGPX_SGPX ) {
			settings_fields('leafext_settings_sgpxparams');
			do_settings_sections( 'leafext_settings_sgpxparams' );
			submit_button( __( "I don't need this anymore. sgpx is always interpreted as elevation.", 'extensions-leaflet-map' ), 'delete', 'delete', false);
		} else {
			echo '<h2>'.leafext_elevation_tab().'</h2>';
			echo __('wp-gpx-maps is not installed and nothing is configured.',"extensions-leaflet-map");
		}
		echo '</form>';


	} else if( $active_tab == 'elevation' ) { //Last tab!!!
		echo '<form method="post" action="options.php">';
		settings_fields('leafext_settings_eleparams');
		do_settings_sections( 'leafext_settings_eleparams' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		echo '</form>';
	} else {
		echo "Error";
	}
}
?>
