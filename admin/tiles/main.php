<?php
/**
 * main admin page for tile functions
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

include LEAFEXT_PLUGIN_DIR . '/admin/tiles/layerswitch.php';
include LEAFEXT_PLUGIN_DIR . '/admin/tiles/providers.php';

function leafext_tiles_tab() {
	$tabs = array (
		array (
			'tab' => 'tileshelp',
			'title' => 'Tile Services',
		),
		array (
			'tab' => 'tilesproviders',
			'title' => 'Leaflet-providers',
		),
		array (
			'tab' => 'tileswitch',
			'title' => 'Extra Tile Services',
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

	$textheader = $textheader. '</div>';
	return $textheader;
}

function leafext_admin_tiles($active_tab) {
  echo '<h2>'.leafext_tiles_tab().'</h2>';
	if( $active_tab == 'tileshelp') {
  	include LEAFEXT_PLUGIN_DIR . '/admin/tiles/help.php';
	} else
	if( $active_tab == 'tileswitch') {
  	echo '<form method="post" action="options.php">';
  	settings_fields('leafext_settings_maps');
  	do_settings_sections( 'leafext_settings_maps' );
  	submit_button();
  	echo '</form>';
	} else if( $active_tab == 'tilesproviders' ) {
    echo '<form method="post" action="options.php">';
    settings_fields('leafext_providers');
    do_settings_sections( 'leafext_providers' );
    submit_button();
    submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
    echo '</form>';
	}
}
