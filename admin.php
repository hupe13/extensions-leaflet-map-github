<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();
//
include LEAFEXT_PLUGIN_DIR . 'php/elevation/elevation_functions.php';
include LEAFEXT_PLUGIN_DIR . '/admin/elevation/functions.php';
include LEAFEXT_PLUGIN_DIR . '/admin/elevation/params.php';
include LEAFEXT_PLUGIN_DIR . '/admin/elevation/owntheme.php';
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
	echo '">'. __('Elevation Settings','extensions-leaflet-map'). '</a>'."\n";
	//
	$tabs = array (
		array (
			'tab' => 'tilelayers',
			'title' => __('Switching Tile Layers','extensions-leaflet-map'),
		),
		array (
			'tab' => 'cluster',
			'title' => __('Markercluster and Grouping','extensions-leaflet-map'),
		),
		array (
			'tab' => 'hover',
			'title' => __('Hovering','extensions-leaflet-map'),
		),
		array (
			'tab' => 'gesture',
			'title' => __('Gesture Handling','extensions-leaflet-map'),
		),
		array (
			'tab' => 'zoomhome',
			'title' => 'leaflet.zoomhome',
		),
		// array (
		// 	'tab' => '',
		// 	'title' => '',
		// ),
	);
	foreach ( $tabs as $tab) {
		echo '<a href="?page='.$leafext_plugin_name.'&tab='.$tab['tab'].'" class="nav-tab';
		$active = ( $active_tab == $tab['tab'] ) ? ' nav-tab-active' : '' ;
		echo $active;
		echo '">'.$tab['title'].'</a>'."\n";
	}
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
