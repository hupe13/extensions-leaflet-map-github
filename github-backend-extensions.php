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

if ( ! function_exists( 'leafext_get_repos' ) ) {
	require_once LEAFEXT_PLUGIN_DIR . 'github/github-functions.php';
}
if ( is_admin() && is_main_site() && ! leafext_plugin_active( 'leafext-update-github' ) ) {
	require_once LEAFEXT_PLUGIN_DIR . 'github/github-settings.php';
	require_once LEAFEXT_PLUGIN_DIR . 'github/github-check-update.php';
}
