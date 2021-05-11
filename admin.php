<?php
$init = true;
include LEAFEXT_PLUGIN_DIR . '/admin/elevation.php';
include LEAFEXT_PLUGIN_DIR . '/admin/layerswitch.php';
include LEAFEXT_PLUGIN_DIR . '/admin/markercluster.php';
include LEAFEXT_PLUGIN_DIR . '/admin/gesture.php';
$init = false;

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
	<h2>Extensions for Leaflet Map Options</h2>';

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'help';

	echo '<h3 class="nav-tab-wrapper">';

	echo '<a href="?page='.$leafext_plugin_name.'&tab=help" class="nav-tab';
	echo $active_tab == 'help' ? ' nav-tab-active' : '';
	echo '">'.__('Help',"extensions-leaflet-map").'</a>';
	echo '<a href="?page='.$leafext_plugin_name.'&tab=elevation" class="nav-tab';
	echo $active_tab == 'elevation' ? ' nav-tab-active' : '';
	echo '">Elevation Profile</a>';
	echo '<a href="?page='.$leafext_plugin_name.'&tab=tilelayers" class="nav-tab';
	echo $active_tab == 'tilelayers' ? ' nav-tab-active' : '';
	echo '">Switching Tilelayers</a>';
	echo '<a href="?page='.$leafext_plugin_name.'&tab=cluster" class="nav-tab';
	echo $active_tab == 'cluster' ? ' nav-tab-active' : '';
	echo '">Markercluster and Grouping</a>';
	echo '<a href="?page='.$leafext_plugin_name.'&tab=gesture" class="nav-tab';
	echo $active_tab == 'gesture' ? ' nav-tab-active' : '';
	echo '">Gesture Handling</a>';

	echo '</h3>';

	if( $active_tab == 'elevation' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/elevation.php';
	} else if ( $active_tab == 'tilelayers' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/layerswitch.php';
	} else if( $active_tab == 'cluster' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/markercluster.php';
	} else if( $active_tab == 'gesture' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/gesture.php';
	} else if( $active_tab == 'help' ) {
		include "admin/help.php";
	}
}
?>
