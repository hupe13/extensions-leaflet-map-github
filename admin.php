<?php
/**
 * File admin.php
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

require LEAFEXT_PLUGIN_DIR . '/admin/deleting.php';
require LEAFEXT_PLUGIN_DIR . '/admin/elevation/main.php';
require LEAFEXT_PLUGIN_DIR . '/admin/marker/main.php';
require LEAFEXT_PLUGIN_DIR . '/admin/gesture.php';
require LEAFEXT_PLUGIN_DIR . '/admin/tiles/main.php';
require LEAFEXT_PLUGIN_DIR . '/admin/filemgr/main.php';
require LEAFEXT_PLUGIN_DIR . '/admin/hover/main.php';
require LEAFEXT_PLUGIN_DIR . '/admin/check-update.php';

/**
 * Add menu page for admin
 */
function leafext_add_page() {
	$leafext_plugin_name = basename( __DIR__ );
	// Add Submenu.
	$leafext_admin_page = add_submenu_page(
		'leaflet-map',
		'Extensions for Leaflet Map Options',
		'Extensions for Leaflet Map',
		'manage_options',
		$leafext_plugin_name,
		'leafext_do_page'
	);
}
add_action( 'admin_menu', 'leafext_add_page', 99 );

/**
 * Draw the menu page itself.
 */
function leafext_do_page() {
	$leafext_plugin_name = basename( __DIR__ );
	$get                 = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	$active_tab          = isset( $get['tab'] ) ? $get['tab'] : 'help';

	if ( is_plugin_active( 'leaflet-map/leaflet-map.php' ) ) {
		leafext_admin_tabs();
	}
	if ( strpos( $active_tab, 'elevation' ) !== false ) {
		leafext_admin_elevation( $active_tab );
	} elseif ( strpos( $active_tab, 'filemgr' ) !== false ) {
		leafext_admin_filemgr( $active_tab );
	} elseif ( strpos( $active_tab, 'marker' ) !== false ) {
		leafext_admin_marker( $active_tab );
	} elseif ( strpos( $active_tab, 'tiles' ) !== false ) {
		leafext_admin_tiles( $active_tab );
	} elseif ( strpos( $active_tab, 'hover' ) !== false ) {
		leafext_admin_hover( $active_tab );
	} elseif ( $active_tab == 'gesture' ) {
		echo '<form method="post" action="options.php">';
		settings_fields( 'leafext_settings_gesture' );
		do_settings_sections( 'leafext_settings_gesture' );
		if ( current_user_can( 'manage_options' ) ) {
			wp_nonce_field( 'leafext_gesture', 'leafext_gesture_nonce' );
			submit_button();
		}
		echo '</form>';
	} elseif ( $active_tab == 'zoomhome' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/zoomhome.php';
		leafext_zoomhome_help();
	} elseif ( $active_tab == 'help' ) {
		if ( is_plugin_active( 'leaflet-map/leaflet-map.php' ) ) {
			include LEAFEXT_PLUGIN_DIR . '/admin/help.php';
			echo '<form method="post" action="options.php">';
			settings_fields( 'leafext_settings_deleting' );
			do_settings_sections( 'leafext_settings_deleting' );
			if ( current_user_can( 'manage_options' ) ) {
				wp_nonce_field( 'leafext_deleting', 'leafext_deleting_nonce' );
				submit_button();
			}
			echo '</form>';
		}
		if ( leafext_is_github() ) {
			if ( is_main_site() ) {
				echo '<form method="post" action="options.php">';
				settings_fields( 'leafext_settings_updating' );
				do_settings_sections( 'leafext_settings_updating' );
				if ( current_user_can( 'manage_options' ) ) {
					wp_nonce_field( 'leafext_updating', 'leafext_updating_nonce' );
					submit_button();
				}
				echo '</form>';
			} else {
				$main_site_id  = get_main_site_id();
				$main_site_url = get_site_url( $main_site_id );
				printf(
					__(
						'If you want to receive updates in WordPress way, go to the %1$smain site dashboard%2$s, activate the plugin there and set a Github token if necessary.',
						'extensions-leaflet-map'
					),
					'<a href="' . $main_site_url . '/wp-admin/plugins.php">',
					'</a>'
				);
			}
		}
		if ( is_plugin_active( 'leaflet-map/leaflet-map.php' ) ) {
			leafext_help_table( $leafext_plugin_name );
		}
	} elseif ( $active_tab == 'fullscreen' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/fullscreen.php';
		leafext_help_fullscreen();
	} elseif ( $active_tab == 'choropleth' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/choropleth.php';
		leafext_choropleth_help();
	} elseif ( $active_tab == 'featuregroup' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/featuregroup.php';
	} elseif ( $active_tab == 'leafletsearch' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/leaflet-search.php';
		leafext_leafletsearch_help();
	} elseif ( $active_tab == 'overviewmap' ) {
		include LEAFEXT_PLUGIN_DIR . '/admin/overview-map.php';
		leafext_overviewmap_help();
	}
}

function leafext_admin_tabs() {
	$leafext_plugin_name = basename( __DIR__ );
	echo '<div class="wrap nothickbox">
	<h2>Extensions for Leaflet Map Options and Help</h2></div>' . "\n";

	$get        = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	$active_tab = isset( $get['tab'] ) ? $get['tab'] : 'help';

	echo '<h3 class="nav-tab-wrapper">';
	echo '<a href="?page=' . $leafext_plugin_name . '&tab=help" class="nav-tab';
	echo $active_tab == 'help' ? ' nav-tab-active' : '';
	echo '">' . __( 'Help', 'extensions-leaflet-map' ) . '</a>' . "\n";
	echo '<a href="?page=' . $leafext_plugin_name . '&tab=elevation" class="nav-tab';
	if ( strpos( $active_tab, 'elevation' ) !== false ) {
		echo ' nav-tab-active';
	}
	echo '">' . __( 'Elevation Profiles', 'extensions-leaflet-map' ) . '</a>' . "\n";
	if ( current_user_can( 'manage_options' ) ) {
		echo '<a href="?page=' . $leafext_plugin_name . '&tab=filemgr" class="nav-tab';
		if ( strpos( $active_tab, 'filemgr' ) !== false ) {
			echo ' nav-tab-active';
		}
		echo '">' . __( 'Files for Leaflet Map', 'extensions-leaflet-map' ) . '</a>' . "\n";
	} else {
		echo '<a href="?page=' . $leafext_plugin_name . '&tab=filemgr-list" class="nav-tab';
		echo $active_tab == 'filemgr-list' ? ' nav-tab-active' : '';
		echo '">' . __( 'Files for Leaflet Map', 'extensions-leaflet-map' ) . '</a>' . "\n";
	}
	$tabs = array(
		array(
			'tab'    => 'markercluster',
			'title'  => __( 'Markers and Icons', 'extensions-leaflet-map' ),
			'strpos' => 'marker',
		),
		array(
			'tab'   => 'featuregroup',
			'title' => __( 'Grouping by options and features', 'extensions-leaflet-map' ),
		),
		array(
			'tab'   => 'leafletsearch',
			'title' => __( 'Search markers/features', 'extensions-leaflet-map' ),
		),
		array(
			'tab'    => 'tiles',
			'title'  => __( 'Switching Tile Servers', 'extensions-leaflet-map' ),
			'strpos' => 'tiles',
		),
		array(
			'tab'    => 'hover',
			'title'  => __( 'Hovering and Tooltips', 'extensions-leaflet-map' ),
			'strpos' => 'hover',
		),
		array(
			'tab'   => 'overviewmap',
			'title' => __( 'Overview Map', 'extensions-leaflet-map' ),
		),
		array(
			'tab'   => 'zoomhome',
			'title' => __( 'Reset the map', 'extensions-leaflet-map' ),
		),
		array(
			'tab'   => 'fullscreen',
			'title' => __( 'Fullscreen', 'extensions-leaflet-map' ),
		),
		array(
			'tab'   => 'gesture',
			'title' => __( 'Gesture Handling', 'extensions-leaflet-map' ),
		),
		array(
			'tab'   => 'choropleth',
			'title' => 'Choropleth Map',
		),
		// array(
		// 'tab' => '',
		// 'title' => '',
		// ),
	);

	foreach ( $tabs as $tab ) {
		echo '<a href="?page=' . $leafext_plugin_name . '&tab=' . $tab['tab'] . '" class="nav-tab';
		$active = ( $active_tab == $tab['tab'] ) ? ' nav-tab-active' : '';
		if ( isset( $tab['strpos'] ) ) {
			if ( strpos( $active_tab, $tab['strpos'] ) !== false ) {
				$active = ' nav-tab-active';
			}
		}
		echo $active;
		echo '">' . $tab['title'] . '</a>' . "\n";
	}
	echo '</h3>';
}

function leafext_admin_style() {
	$get  = map_deep( wp_unslash( $_GET ), 'sanitize_text_field' );
	$page = isset( $get['page'] ) ? $get['page'] : '';
	if ( $page == LEAFEXT_PLUGIN_SETTINGS ) {
		wp_enqueue_style(
			'leafext_admin_css',
			plugins_url( 'css/leafext-admin.min.css', LEAFEXT_PLUGIN_FILE )
		);
	}
}
add_action( 'admin_enqueue_scripts', 'leafext_admin_style' );
