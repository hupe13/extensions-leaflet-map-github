<?php
include 'admin/elevation.php';
include 'admin/switching_tilelayer.php';

// Admin Menu
add_action('admin_menu', 'leafext_add_page', 99);

// Add menu page
function leafext_add_page() {
	$leafext_plugin_name = basename(dirname(  __FILE__  ));
	//Add Submenu
	$leafext_admin_page =
		add_submenu_page( 'leaflet-map',
			'Extensions for Leaflet Map Options',
			'Extensions for Leaflet Map',
		'manage_options',
		$leafext_plugin_name,
		'leafext_do_page');
}

// Draw the menu page itself
function leafext_do_page() {
	$leafext_plugin_name = basename(dirname(  __FILE__  ));
	echo '<div class="wrap">
	<h2>Extensions for Leaflet Map Options</h2>';

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'elevation';

	echo '<h3 class="nav-tab-wrapper">';

	echo '<a href="?page='.$leafext_plugin_name.'&tab=elevation" class="nav-tab';
	echo $active_tab == 'elevation' ? ' nav-tab-active' : '';
	echo '">Elevation Theme</a>';
	echo '<a href="?page='.$leafext_plugin_name.'&tab=tilelayers" class="nav-tab';
	echo $active_tab == 'tilelayers' ? ' nav-tab-active' : '';
	echo '">Switching Tilelayers</a>';
	echo '<a href="?page='.$leafext_plugin_name.'&tab=help" class="nav-tab';
	echo $active_tab == 'help' ? ' nav-tab-active' : '';
	echo '">'.__('Help',"extensions-leaflet-map").'</a>';

	echo '</h3>';
	if( $active_tab != 'help' ) {
	echo '<form method="post" action="options.php">';
	if( $active_tab == 'elevation' ) {
		settings_fields('leafext_settings_theme');
		do_settings_sections( 'leafext_settings_theme' );
		//
	} else if ( $active_tab == 'tilelayers' ) {
		settings_fields('leafext_settings_maps');
		do_settings_sections( 'leafext_settings_maps' );
	}
	submit_button();
	echo '</form>';
	}
	if( $active_tab == 'elevation' ) {
		echo leafext_elevation_help_text();
	}
	if( $active_tab == 'help' ) {
		include "admin/help.php";
		echo leafext_help();
	}
}

?>
