<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

include LEAFEXT_PLUGIN_DIR . '/admin/elevation/main.php';
include LEAFEXT_PLUGIN_DIR . '/admin/marker/main.php';
include LEAFEXT_PLUGIN_DIR . '/admin/gesture.php';
include LEAFEXT_PLUGIN_DIR . '/admin/tiles/main.php';
include LEAFEXT_PLUGIN_DIR . '/admin/filemgr/main.php';
include LEAFEXT_PLUGIN_DIR . '/admin/hover/main.php';

// Add menu page for admin
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
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'help';
	leafext_admin_tabs();
	//
	if ( strpos( $active_tab,  'elevation' ) !== false ) {
		leafext_admin_elevation($active_tab);
	} else if ( strpos( $active_tab, 'filemgr' ) !== false ) {
		leafext_admin_filemgr($active_tab);
	} else if ( strpos( $active_tab, 'marker' ) !== false ) {
		leafext_admin_marker($active_tab);
	} else if ( strpos( $active_tab, 'tiles' ) !== false ) {
		leafext_admin_tiles($active_tab);
	} else if ( strpos( $active_tab, 'hover' ) !== false ) {
		leafext_admin_hover($active_tab);
	} else if( $active_tab == 'gesture' ) {
		echo '<form method="post" action="options.php">';
		settings_fields('leafext_settings_gesture');
		do_settings_sections( 'leafext_settings_gesture' );
		submit_button();
		echo '</form>';
	} else if( $active_tab == 'zoomhome' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/zoomhome.php';
		leafext_zoomhome_help();
	} else if( $active_tab == 'help' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/help.php';
		leafext_help_table($leafext_plugin_name);
	} else if( $active_tab == 'fullscreen' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/fullscreen.php';
		leafext_help_fullscreen();
	} else if( $active_tab == 'choropleth' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/choropleth.php';
		leafext_choropleth_help();
	} else if( $active_tab == 'featuregroup' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/featuregroup.php';
	} else if( $active_tab == 'leafletsearch' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/leaflet-search.php';
		leafext_leafletsearch_help();
	} else if( $active_tab == 'overviewmap' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/overview-map.php';
		leafext_overviewmap_help();
	}
}

//add Menu for others
function leafext_add_nonadmin_page() {
	$leafext_plugin_name = basename(dirname(  __FILE__  ));
	//Add Submenu
	$leafext_autor_page =
	add_submenu_page( 'leaflet-shortcode-helper',
	'Extensions for Leaflet Map Options',
	'Extensions for Leaflet Map',
	'edit_posts',
	$leafext_plugin_name,
	'leafext_do_nonadmin_page');
}
add_action('admin_menu', 'leafext_add_nonadmin_page', 99);

function leafext_do_nonadmin_page() {
	$leafext_plugin_name = basename(dirname(  __FILE__  ));
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'help';
	leafext_admin_tabs();
	//
	if ( strpos( $active_tab,  'elevation' ) !== false ) {
		leafext_admin_elevation($active_tab);
	} else if ( strpos( $active_tab, 'filemgr' ) !== false ) {
		leafext_admin_filemgr($active_tab);
	} else if ( strpos( $active_tab, 'marker' ) !== false ) {
		leafext_admin_marker($active_tab);
	} else if ( strpos( $active_tab, 'tiles' ) !== false ) {
		leafext_admin_tiles($active_tab);
	} else if ( strpos( $active_tab, 'hover' ) !== false ) {
		leafext_admin_hover($active_tab);
	} else if( $active_tab == 'gesture' ) {
		echo '<form>';
		settings_fields('leafext_settings_gesture');
		do_settings_sections( 'leafext_settings_gesture' );
		echo '</form>';
	} else if( $active_tab == 'zoomhome' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/zoomhome.php';
		leafext_zoomhome_help();
	} else if( $active_tab == 'help' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/help.php';
		leafext_help_table($leafext_plugin_name);
	} else if( $active_tab == 'fullscreen' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/fullscreen.php';
		leafext_help_fullscreen();
	} else if( $active_tab == 'choropleth' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/choropleth.php';
		leafext_choropleth_help();
	} else if( $active_tab == 'featuregroup' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/featuregroup.php';
		//leafext_help_featuregroup();
	} else if( $active_tab == 'leafletsearch' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/leaflet-search.php';
		leafext_leafletsearch_help();
	} else if( $active_tab == 'overviewmap' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/overview-map.php';
		leafext_overviewmap_help();
	}
	//
}

function leafext_admin_tabs() {
	$leafext_plugin_name = basename(dirname(  __FILE__  ));
	echo '<div class="wrap nothickbox">
	<h2>Extensions for Leaflet Map Options and Help</h2></div>'."\n";

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
	echo '">'. __('Elevation Profiles','extensions-leaflet-map'). '</a>'."\n";
	//
	if (current_user_can('manage_options')) {
		echo '<a href="?page='.$leafext_plugin_name.'&tab=filemgr" class="nav-tab';
		if ( strpos( $active_tab, 'filemgr' ) !== false ) {
			echo ' nav-tab-active';
		}
		echo '">'.__('Files for Leaflet Map',"extensions-leaflet-map").'</a>'."\n";
	} else {
		echo '<a href="?page='.$leafext_plugin_name.'&tab=filemgr-list" class="nav-tab';
		echo $active_tab == 'filemgr-list' ? ' nav-tab-active' : '';
		echo '">'.__('Files for Leaflet Map',"extensions-leaflet-map").'</a>'."\n";
	}
	//
	$tabs = array (
		array (
			'tab' => 'markercluster',
			'title' => __('Functions for Markers','extensions-leaflet-map'),
			'strpos' => 'marker',
		),
		array (
			'tab' => 'featuregroup',
			'title' => __('Grouping by options and features','extensions-leaflet-map'),
		),
		array (
			'tab' => 'leafletsearch',
			'title' => __('Search markers/features','extensions-leaflet-map'),
		),
		array (
			'tab' => 'tiles',
			'title' => __('Switching Tile Servers','extensions-leaflet-map'),
			'strpos' => 'tiles',
		),
		array (
			'tab' => 'hover',
			'title' => __('Hovering and Tooltips','extensions-leaflet-map'),
			'strpos' => 'hover',
		),
		array (
			'tab' => 'overviewmap',
			'title' => __('Overview Map','extensions-leaflet-map'),
		),
		array (
			'tab' => 'zoomhome',
			'title' => __('Reset the map','extensions-leaflet-map'),
		),
		array (
			'tab' => 'fullscreen',
			'title' => __('Fullscreen','extensions-leaflet-map'),
		),
		array (
			'tab' => 'gesture',
			'title' => __('Gesture Handling','extensions-leaflet-map'),
		),
		array (
			'tab' => 'choropleth',
			'title' => 'Choropleth Map',
		),
		// array (
		// 	'tab' => '',
		// 	'title' => '',
		// ),
	);

	foreach ( $tabs as $tab) {
		echo '<a href="?page='.$leafext_plugin_name.'&tab='.$tab['tab'].'" class="nav-tab';
		$active = ( $active_tab == $tab['tab'] ) ? ' nav-tab-active' : '' ;
		if (isset($tab['strpos'])) {
			if (strpos($active_tab,$tab['strpos']) !== false) {
				$active = ' nav-tab-active';
			}
		}
		echo $active;
		echo '">'.$tab['title'].'</a>'."\n";
	}
	//
	echo '</h3>';
}

function leafext_admin_style() {
	$page = isset($_GET['page']) ? $_GET['page'] : "";
	if ( $page == LEAFEXT_PLUGIN_SETTINGS ) {
		wp_enqueue_style('leafext_admin_css',
		plugins_url('css/leafext-admin.css',
		LEAFEXT_PLUGIN_FILE));
	}
}
add_action('admin_enqueue_scripts', 'leafext_admin_style');
