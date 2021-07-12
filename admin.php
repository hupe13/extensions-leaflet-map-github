<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();
//
include LEAFEXT_PLUGIN_DIR . '/admin/elevation/functions.php';
leafext_elevation_admin_init();
include LEAFEXT_PLUGIN_DIR . '/admin/layerswitch.php';
include LEAFEXT_PLUGIN_DIR . '/admin/markercluster.php';
include LEAFEXT_PLUGIN_DIR . '/admin/gesture.php';

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
add_action('admin_menu', 'leafext_add_page', 99);

// Draw the menu page itself
function leafext_do_page() {
	$leafext_plugin_name = basename(dirname(  __FILE__  ));
	echo '<div class="wrap">
	<h2>Extensions for Leaflet Map Options and Help</h2>'."\n";

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'help';

	echo '<h3 class="nav-tab-wrapper">';
	//
	echo '<a href="?page='.$leafext_plugin_name.'&tab=help" class="nav-tab';
	echo $active_tab == 'help' ? ' nav-tab-active' : '';
	echo '">'.__('Help',"extensions-leaflet-map").'</a>'."\n";
	//
	echo '<a href="?page='.$leafext_plugin_name.'&tab=elevation" class="nav-tab';
	if ( strpos( $active_tab, 'elevation' ) !== false ) {
		echo ' nav-tab-active';
	}
	echo '">Elevation Settings</a>'."\n";
	//
	echo '<a href="?page='.$leafext_plugin_name.'&tab=tilelayers" class="nav-tab';
	echo $active_tab == 'tilelayers' ? ' nav-tab-active' : '';
	echo '">Switching Tilelayers</a>'."\n";
	//
	echo '<a href="?page='.$leafext_plugin_name.'&tab=cluster" class="nav-tab';
	echo $active_tab == 'cluster' ? ' nav-tab-active' : '';
	echo '">Markercluster and Grouping</a>'."\n";
	//
	echo '<a href="?page='.$leafext_plugin_name.'&tab=hover" class="nav-tab';
	echo $active_tab == 'hover' ? ' nav-tab-active' : '';
	echo '">Hovering</a>'."\n";
	//
	echo '<a href="?page='.$leafext_plugin_name.'&tab=gesture" class="nav-tab';
	echo $active_tab == 'gesture' ? ' nav-tab-active' : '';
	echo '">Gesture Handling</a>'."\n";
	//
	echo '<a href="?page='.$leafext_plugin_name.'&tab=zoomhome" class="nav-tab';
	echo $active_tab == 'zoomhome' ? ' nav-tab-active' : '';
	echo '">leaflet.zoomhome</a>'."\n";
	//
	echo '</h3>';

	if ( strpos( $active_tab,  'elevation' ) !== false ) {
		leafext_admin_elevation($active_tab);
	} else if ( $active_tab == 'tilelayers' ) {
		echo '<form method="post" action="options.php">';
		settings_fields('leafext_settings_maps');
		do_settings_sections( 'leafext_settings_maps' );
		submit_button();
		echo '</form>';
	} else if( $active_tab == 'cluster' ) {
		echo '<form method="post" action="options.php">';
		settings_fields('leafext_settings_cluster');
		do_settings_sections( 'leafext_settings_cluster' );
		submit_button();
		submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false);
		echo '</form>';
	} else if( $active_tab == 'hover' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/help/hovergeojson.php';
	} else if( $active_tab == 'gesture' ) {
		echo '<form method="post" action="options.php">';
		settings_fields('leafext_settings_gesture');
		do_settings_sections( 'leafext_settings_gesture' );
		submit_button();
		echo '</form>';
	} else if( $active_tab == 'zoomhome' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/help/zoomhome.php';
	} else if( $active_tab == 'help' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/help.php';
	}
}
