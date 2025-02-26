<?php
/**
 * Backend Menus
 *
 * @package Extensions for Leaflet Map Github Version
 **/

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

// for translating a plugin
function leafext_extensions_textdomain() {
	if ( get_locale() === 'de_DE' ) {
		load_plugin_textdomain( 'leafext-update-github', false, LEAFEXT_PLUGIN_SETTINGS . '/github/lang/' );
		load_plugin_textdomain( 'extensions-leaflet-map', false, LEAFEXT_PLUGIN_SETTINGS . '/lang/' );
	}
}
add_action( 'plugins_loaded', 'leafext_extensions_textdomain' );

// https://make.wordpress.org/core/2024/03/05/introducing-plugin-dependencies-in-wordpress-6-5/
function leafext_extensions_leaflet_map_to_github( $slug ) {
	if ( 'extensions-leaflet-map' === $slug ) {
		$slug = LEAFEXT_PLUGIN_SETTINGS;
	}
	return $slug;
}
add_filter( 'wp_plugin_dependencies_slug', 'leafext_extensions_leaflet_map_to_github' );

// prevent unnecessary API calls to wordpress.org
function leafext_prevent_requests( $res, $action, $args ) {
	if ( 'plugin_information' !== $action ) {
		return $res;
	}
	if ( $args->slug !== LEAFEXT_PLUGIN_SETTINGS ) {
		return $res;
	}
	$plugin_data = get_plugin_data( __FILE__, true, false );
	$res         = new stdClass();
	$res->name   = $plugin_data['Name'];
	return $res;
}
add_filter( 'plugins_api', 'leafext_prevent_requests', 10, 3 );

if ( ! function_exists( 'leafext_get_repos' ) ) {
	require_once LEAFEXT_PLUGIN_DIR . 'github/github-functions.php';
}
if ( is_main_site() && ! function_exists( 'leafext_update_puc_error' ) ) {
	require_once LEAFEXT_PLUGIN_DIR . 'github/github-settings.php';
	require_once LEAFEXT_PLUGIN_DIR . 'github/github-check-update.php';
}
