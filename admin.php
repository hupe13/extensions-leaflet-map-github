<?php
/**
 * File admin.php
 *
 * @package Extensions for Leaflet Map
 */

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

require __DIR__ . '/admin/deleting.php';
require __DIR__ . '/admin/elevation/main.php';
require __DIR__ . '/admin/marker/main.php';
require __DIR__ . '/admin/gesture.php';
require __DIR__ . '/admin/tiles/main.php';
require __DIR__ . '/admin/filemgr/main.php';
require __DIR__ . '/admin/hover/main.php';
require __DIR__ . '/admin/overviewmap/overview-map.php';
require __DIR__ . '/admin/overviewmap/featured-map.php';
require __DIR__ . '/admin/grouping/main.php';
require __DIR__ . '/admin/awesome.php';
require __DIR__ . '/admin/zoomhome.php';

/**
 * Add menu page for admin
 */
function leafext_add_page() {
	// Add Submenu.
	$leafext_admin_page = add_submenu_page(
		'leaflet-map',
		esc_html__( 'Extensions for Leaflet Map Options', 'extensions-leaflet-map' ),
		esc_html__( 'Extensions for Leaflet Map', 'extensions-leaflet-map' ),
		'manage_options',
		LEAFEXT_PLUGIN_SETTINGS,
		'leafext_do_page'
	);
}
add_action( 'admin_menu', 'leafext_add_page', 80 );

/**
 * Draw the menu page itself.
 */
function leafext_do_page() {
	$tab        = filter_input(
		INPUT_GET,
		'tab',
		FILTER_CALLBACK,
		array( 'options' => 'esc_html' )
	);
	$active_tab = $tab ? $tab : 'help';

	echo '<div style="max-width: 1000px;">';
	echo '<div class="wrap nothickbox">';
	echo '<h2>' . esc_html__( 'Extensions for Leaflet Map - Help and Documentation', 'extensions-leaflet-map' ) . '</h2></div>' . "\n";
	echo '</div>' . "\n";

	if ( leafext_plugin_active( 'leaflet-map' ) ) {
		leafext_admin_tabs();
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
		} elseif ( $active_tab === 'gesture' ) {
			echo '<form method="post" action="options.php">';
			settings_fields( 'leafext_settings_gesture' );
			do_settings_sections( 'leafext_settings_gesture' );
			if ( current_user_can( 'manage_options' ) ) {
				wp_nonce_field( 'leafext_gesture', 'leafext_gesture_nonce' );
				submit_button();
			}
			echo '</form>';
		} elseif ( $active_tab === 'zoomhome' ) {
			leafext_help_awesome();
			echo '<form method="post" action="options.php">';
			settings_fields( 'leafext_settings_awesome' );
			do_settings_sections( 'leafext_settings_awesome' );
			if ( current_user_can( 'manage_options' ) ) {
				wp_nonce_field( 'leafext_awesome', 'leafext_awesome_nonce' );
				submit_button();
			}
			echo '</form>';
			//
			leafext_zoomhome_help_head();
			leafext_zoomhome_help_shortcode();

			echo '<h2>' . esc_html__( 'Options', 'extensions-leaflet-map' ) . '</h2>';
			echo '<form method="post" action="options.php">';
			settings_fields( 'leafext_settings_zoomhome' );
			do_settings_sections( 'leafext_settings_zoomhome' );
			if ( current_user_can( 'manage_options' ) ) {
				wp_nonce_field( 'leafext_zoomhome', 'leafext_zoomhome_nonce' );
				submit_button();
				submit_button( __( 'Reset', 'extensions-leaflet-map' ), 'delete', 'delete', false );
			}
			echo '</form>';
			leafext_zoomhome_help_table();
		} elseif ( $active_tab === 'help' ) {
			if ( function_exists( 'leafext_updates_from_github' ) ) {
				leafext_updates_from_github();
			}
			include __DIR__ . '/admin/help.php';
			echo '<form method="post" action="options.php">';
			settings_fields( 'leafext_settings_deleting' );
			do_settings_sections( 'leafext_settings_deleting' );
			if ( current_user_can( 'manage_options' ) ) {
				wp_nonce_field( 'leafext_deleting', 'leafext_deleting_nonce' );
				submit_button();
			}
			echo '</form>';
			leafext_help_table( LEAFEXT_PLUGIN_SETTINGS );
		} elseif ( $active_tab === 'fullscreen' ) {
			include __DIR__ . '/admin/fullscreen.php';
			echo wp_kses_post( leafext_help_fullscreen() );
		} elseif ( $active_tab === 'choropleth' ) {
			include __DIR__ . '/admin/choropleth.php';
			echo wp_kses_post( leafext_choropleth_help() );
		} elseif ( strpos( $active_tab, 'group' ) !== false ) {
			leafext_admin_grouping( $active_tab );
		} elseif ( $active_tab === 'leafletsearch' ) {
			include __DIR__ . '/admin/leaflet-search.php';
			leafext_leafletsearch_help();
		} elseif ( $active_tab === 'overviewmap' ) {
			echo '<form method="post" action="options.php">';
			settings_fields( 'leafext_settings_overviewmap' );
			do_settings_sections( 'leafext_settings_overviewmap' );
			if ( current_user_can( 'manage_options' ) ) {
				wp_nonce_field( 'leafext_overviewmap', 'leafext_overviewmap_nonce' );
				submit_button();
			}
			echo '</form>';
		} elseif ( $active_tab === 'featuredmap' ) {
			leafext_featuredmap_admin();
		}
	}
}

function leafext_admin_tabs() {
	$tab        = filter_input(
		INPUT_GET,
		'tab',
		FILTER_CALLBACK,
		array( 'options' => 'esc_html' )
	);
	$active_tab = $tab ? $tab : 'help';

	echo '<h3 class="nav-tab-wrapper">';
	echo '<a href="' . esc_url( '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=help' ) . '" class="nav-tab';
	echo $active_tab === 'help' ? ' nav-tab-active' : '';
	echo '">' . esc_html__( 'Help', 'extensions-leaflet-map' ) . '</a>' . "\n";
	if ( current_user_can( 'manage_options' ) ) {
		echo '<a href="' . esc_url( '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=filemgr' ) . '" class="nav-tab';
		if ( strpos( $active_tab, 'filemgr' ) !== false ) {
			echo ' nav-tab-active';
		}
		echo '">' . esc_html__( 'Manage Leaflet Map files', 'extensions-leaflet-map' ) . '</a>' . "\n";
	} else {
		echo '<a href="' . esc_url( '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=filemgr-list' ) . '" class="nav-tab';
		echo $active_tab === 'filemgr-list' ? ' nav-tab-active' : '';
		echo '">' . esc_html__( 'Manage Leaflet Map files', 'extensions-leaflet-map' ) . '</a>' . "\n";
	}
	echo '<a href="' . esc_url( '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=elevation' ) . '" class="nav-tab';
	if ( strpos( $active_tab, 'elevation' ) !== false ) {
		echo ' nav-tab-active';
	}
	echo '">' . esc_html__( 'Elevation Profiles', 'extensions-leaflet-map' ) . '</a>' . "\n";

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
			'tab'   => 'featuredmap',
			'title' => __( 'Featured Map', 'extensions-leaflet-map' ),
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
		echo '<a href="' . esc_url( '?page=' . LEAFEXT_PLUGIN_SETTINGS . '&tab=' . $tab['tab'] ) . '" class="nav-tab';
		$active = ( $active_tab === $tab['tab'] ) ? ' nav-tab-active' : '';
		if ( isset( $tab['strpos'] ) ) {
			if ( strpos( $active_tab, $tab['strpos'] ) !== false ) {
				$active = ' nav-tab-active';
			}
		}
		echo esc_attr( $active );
		echo '">' . esc_html( $tab['title'] ) . '</a>' . "\n";
	}
	echo '</h3>';
}

function leafext_admin_style( $hook ) {
	if ( strpos( $hook, LEAFEXT_PLUGIN_SETTINGS ) === false ) {
		return;
	}
	wp_enqueue_style(
		'leafext_admin_css',
		plugins_url( 'css/leafext-admin' . LEAFEXT_MINI . '.css', LEAFEXT_PLUGIN_FILE ),
		array(),
		LEAFEXT_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'leafext_admin_style' );

function leafext_leaflet_map_needed() {
	if ( ! leafext_plugin_active( 'leaflet-map' ) ) {
		$uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) );
		if ( strpos( $uri, 'plugins.php' ) !== false || strpos( $uri, 'admin.php' ) !== false ) {
			$message = '<div class="error notice is-dismissible"><p>' . sprintf(
			/* translators: %s is a link. */
				__( 'Please install and activate %1$sLeaflet Map%2$s before using %3$s.', 'extensions-leaflet-map' ),
				'<a href="https://wordpress.org/plugins/leaflet-map/">',
				'</a>',
				__( 'Extensions for Leaflet Map', 'extensions-leaflet-map' )
			) . '</p></div>';
			echo wp_kses_post( $message );
		}
	}
}
add_action( 'admin_notices', 'leafext_leaflet_map_needed' );
