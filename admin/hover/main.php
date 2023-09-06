<?php
/**
* main admin page for hover functions
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

include LEAFEXT_PLUGIN_DIR . '/admin/hover/hover.php';
include LEAFEXT_PLUGIN_DIR . '/admin/hover/hoverlap.php';
include LEAFEXT_PLUGIN_DIR . '/admin/hover/settings.php';

function leafext_hover_tab() {
	$tabs = array (
		array (
			'tab' => 'hover',
			'title' => __('hover','extensions-leaflet-map'),
		),
		array (
			'tab' => 'hoverlap',
			'title' => __('hoverlap','extensions-leaflet-map'),
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

function leafext_admin_hover($active_tab) {
	if( $active_tab == 'hover') {
		echo '<h2>'.leafext_hover_tab().'</h2>';
		leafext_help_hover();
		leafext_hover_admin_page();
	} else if( $active_tab == 'hoverlap') {
		echo '<h2>'.leafext_hover_tab().'</h2>';
		leafext_help_hoverlap();
	} else {
		echo "Error";
	}
}
