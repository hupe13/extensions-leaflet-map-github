<?php
/**
 * Backend Menus
 *
 * @package Update Management for Leaflet Map and its Extensions Github
 **/

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// Repos on Github
if ( ! leafext_plugin_active( 'leafext-update-github' ) ) {
	if ( ! is_main_site() ) {
		function leafext_extensions_goto_main_site() {
			$leafext_updates_active = false;
			if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}
			if ( is_plugin_active_for_network( LEAFEXT_PLUGIN_SETTINGS . '/extensions-leaflet-map.php' ) ) {
				$leafext_updates_active = true;
			} else {
				switch_to_blog( get_main_site_id() );
				if ( is_plugin_active( LEAFEXT_PLUGIN_SETTINGS . '/extensions-leaflet-map.php' ) ) {
					$leafext_updates_active = true;
				}
				restore_current_blog();
			}
			if ( ! $leafext_updates_active ) {
				echo '<h3>' . esc_html__( 'Updates in WordPress way', 'leafext-update-github' ) . '</h3>';
				printf(
				/* translators: %s is a link. */
					esc_html__(
						'If you want to receive updates in WordPress way, go to the %1$smain site dashboard%2$s and activate the plugin here or install and activate %3$s.',
						'leafext-update-github'
					),
					'<a href="' . esc_url( get_site_url( get_main_site_id() ) ) . '/wp-admin/plugins.php">',
					'</a>',
					'<a href="https://github.com/hupe13/leafext-update-github">Manage Updates of Leaflet Map Extensions and DSGVO Github Versions</a>'
				);
			}
		}
	}
}

// for translating a plugin
function leafext_extensions_textdomain() {
	if ( get_locale() === 'de_DE' ) {
		load_plugin_textdomain( 'leafext-update-github', false, LEAFEXT_PLUGIN_SETTINGS . '/lang/' );
		load_plugin_textdomain( 'extensions-leaflet-map', false, LEAFEXT_PLUGIN_SETTINGS . '/lang/' );
	}
}
add_action( 'plugins_loaded', 'leafext_extensions_textdomain' );

function leafext_extensions_leaflet_map_to_github( $slug ) {
	if ( 'extensions-leaflet-map' === $slug ) {
		$slug = 'extensions-leaflet-map-github';
	}
	return $slug;
}
add_filter( 'wp_plugin_dependencies_slug', 'leafext_extensions_leaflet_map_to_github' );

// prevent unnecessary API calls to wordpress.org
function leafext_prevent_requests( $res, $action, $args ) {
	if ( 'plugin_information' !== $action ) {
		return $res;
	}
	if ( $args->slug !== 'extensions-leaflet-map-github' ) {
		return $res;
	}
	$plugin_data = get_plugin_data( __FILE__, true, false );
	$res         = new stdClass();
	$res->name   = $plugin_data['Name'];
	return $res;
}
add_filter( 'plugins_api', 'leafext_prevent_requests', 10, 3 );
