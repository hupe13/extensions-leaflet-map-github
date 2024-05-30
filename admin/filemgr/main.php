<?php
/**
 * Admin main page for filemgr
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

require LEAFEXT_PLUGIN_DIR . '/admin/filemgr/uploader.php';
require LEAFEXT_PLUGIN_DIR . '/admin/filemgr/filemgr-settings.php';
require LEAFEXT_PLUGIN_DIR . '/admin/filemgr/filemgr.php';
require LEAFEXT_PLUGIN_DIR . '/admin/filemgr/managefiles-functions.php';

function leafext_filemgr_tab() {
	//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- no form
	$get       = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	$tb_iframe = isset( $get['TB_iframe'] ) ? $get['TB_iframe'] : '';
	if ( $tb_iframe === true ) {
		return '';
	}
	$tabs = array();
	if ( current_user_can( 'manage_options' ) ) {
		$tabs[] = array(
			'tab'   => 'filemgr',
			'title' => __( 'Files for Leaflet Map', 'extensions-leaflet-map' ),
		);
	}
	$tabs[] = array(
		'tab'   => 'filemgr-list',
		'title' => __( 'List Files', 'extensions-leaflet-map' ),
	);
	$tabs[] = array(
		'tab'   => 'filemgr-dir',
		'title' => __( 'Tracks from all files in a directory', 'extensions-leaflet-map' ),
	);

	$page       = isset( $get['page'] ) ? $get['page'] : '';
	$active_tab = isset( $get['tab'] ) ? $get['tab'] : '';
	$textheader = '<div class="nav-tab-wrapper">';

	foreach ( $tabs as $tab ) {
		$textheader = $textheader . '<a href="?page=' . $page . '&tab=' . $tab['tab'] . '" class="nav-tab';
		$active     = ( $active_tab === $tab['tab'] ) ? ' nav-tab-active' : '';
		$textheader = $textheader . $active;
		$textheader = $textheader . '">' . $tab['title'] . '</a>' . "\n";
	}

	$textheader = $textheader . '</div>';
	return $textheader;
}

function leafext_admin_filemgr( $active_tab ) {
	echo '<h2>' . wp_kses_post( leafext_filemgr_tab() ) . '</h2>';
	if ( $active_tab === 'filemgr' ) {
		echo '<form method="post" action="options.php">';
		settings_fields( 'leafext_settings_filemgr' );
		do_settings_sections( 'leafext_settings_filemgr' );
		wp_nonce_field( 'leafext_file', 'leafext_file_nonce' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false );
		echo '</form>';
	} elseif ( $active_tab === 'filemgr-list' ) {
		leafext_managefiles();
	} elseif ( $active_tab === 'filemgr-dir' ) {
		include 'leaflet-directory.php';
		echo wp_kses_post( leafext_directory_help_text() );
	}
}
