<?php
/**
 * Admin main page for grouping functions
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

require LEAFEXT_PLUGIN_DIR . '/admin/grouping/featuregroup.php';
require LEAFEXT_PLUGIN_DIR . '/admin/grouping/parentgroup.php';
require LEAFEXT_PLUGIN_DIR . '/admin/grouping/settings.php';

function leafext_grouping_tab() {
	$tabs = array(
		array(
			'tab'   => 'featuregroup',
			'title' => 'featuregroup',
		),
		array(
			'tab'   => 'parentgroup',
			'title' => 'parentgroup',
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

function leafext_admin_grouping( $active_tab ) {
	if ( $active_tab === 'featuregroup' ) {
		echo '<h2>' . wp_kses_post( leafext_grouping_tab() ) . '</h2>';
		leafext_help_featuregroup();
	} elseif ( $active_tab === 'parentgroup' ) {
		echo '<h2>' . wp_kses_post( leafext_grouping_tab() ) . '</h2>';
		leafext_help_parentgroup();
		leafext_parentgroup_admin_page();
	} else {
		echo 'Error';
	}
}
