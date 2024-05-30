<?php
/**
 * Main admin page for tile functions
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

require LEAFEXT_PLUGIN_DIR . '/admin/tiles/layerswitch.php';
require LEAFEXT_PLUGIN_DIR . '/admin/tiles/providers.php';

function leafext_tiles_tab() {
	$tabs = array(
		array(
			'tab'   => 'tiles',
			'title' => __( 'Tile Server', 'extensions-leaflet-map' ),
		),
		array(
			'tab'   => 'tilesproviders',
			'title' => __( 'Leaflet-providers', 'extensions-leaflet-map' ),
		),
		array(
			'tab'   => 'tileswitch',
			'title' => __( 'Extra Tile Server', 'extensions-leaflet-map' ),
		),
	);

	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- no form
	$get        = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	$active_tab = isset( $get['tab'] ) ? $get['tab'] : '';
	$textheader = '<div class="nav-tab-wrapper">';

	foreach ( $tabs as $tab ) {
		$textheader = $textheader . '<a href="?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=' . $tab['tab'] . '" class="nav-tab';
		$active     = ( $active_tab === $tab['tab'] ) ? ' nav-tab-active' : '';
		$textheader = $textheader . $active;
		$textheader = $textheader . '">' . $tab['title'] . '</a>' . "\n";
	}

	$textheader = $textheader . '</div>';
	return $textheader;
}

function leafext_admin_tiles( $active_tab ) {
	if ( $active_tab === 'tiles' ) {
		echo '<h2>' . wp_kses_post( leafext_tiles_tab() ) . '</h2>';
		include LEAFEXT_PLUGIN_DIR . '/admin/tiles/help.php';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo ( leafext_help_tiles() );
	} elseif ( $active_tab === 'tileswitch' ) {
		echo '<h2>' . wp_kses_post( leafext_tiles_tab() ) . '</h2>';
		if ( current_user_can( 'manage_options' ) ) {
			echo '<form method="post" action="options.php">';
		} else {
			echo '<form>';
		}
		settings_fields( 'leafext_settings_maps' );
		do_settings_sections( 'leafext_settings_maps' );
		if ( current_user_can( 'manage_options' ) ) {
			wp_nonce_field( 'leafext_tiles', 'leafext_tiles_nonce' );
			submit_button();
		}
		echo '</form>';
	} elseif ( $active_tab === 'tilesproviders' ) {
		echo '<h2>' . wp_kses_post( leafext_tiles_tab() ) . '</h2>';
		if ( current_user_can( 'manage_options' ) ) {
			echo '<form method="post" action="options.php">';
			settings_fields( 'leafext_providers' );
			do_settings_sections( 'leafext_providers' );
			wp_nonce_field( 'leafext_tiles', 'leafext_tiles_nonce' );
			submit_button();
			submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false );
			echo '</form>';
		} else {
			leafext_providers_help();
		}
	}
}
