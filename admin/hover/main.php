<?php
/**
 * Admin main page for hover functions
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

require LEAFEXT_PLUGIN_DIR . '/admin/hover/hover.php';
require LEAFEXT_PLUGIN_DIR . '/admin/hover/hoverlap.php';
require LEAFEXT_PLUGIN_DIR . '/admin/hover/settings.php';

function leafext_hover_tab() {
	$tabs = array(
		array(
			'tab'   => 'hover',
			'title' => __( 'hover', 'extensions-leaflet-map' ),
		),
		array(
			'tab'   => 'hoverlap',
			'title' => __( 'hoverlap', 'extensions-leaflet-map' ),
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

function leafext_admin_hover( $active_tab ) {
	if ( $active_tab === 'hover' ) {
		echo '<h2>' . wp_kses_post( leafext_hover_tab() ) . '</h2>';
		leafext_help_hover();
		leafext_hover_admin_page();
	} elseif ( $active_tab === 'hoverlap' ) {
		echo '<h2>' . wp_kses_post( leafext_hover_tab() ) . '</h2>';
		leafext_help_hoverlap();
	} else {
		echo 'Error';
	}
}
