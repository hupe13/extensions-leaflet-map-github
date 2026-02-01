<?php
/**
 * Plugin Name:       Extensions for Leaflet Map Github Version
 * Description:       Extends the WordPress Plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a> with Leaflet Plugins and other functions.
 * Plugin URI:        https://leafext.de/en/
 * Update URI:        https://github.com/hupe13/extensions-leaflet-map-github
 * Version:           4.9-260201
 * Requires PHP:      8.1
 * Requires Plugins:  leaflet-map
 * Author:            hupe13
 * Author URI:        https://leafext.de/en/
 * License:           GPL v2 or later
 *
 * @package Extensions for Leaflet Map
 **/

// Direktzugriff auf diese Datei verhindern.
defined( 'ABSPATH' ) || die();

define( 'LEAFEXT_PLUGIN_FILE', __FILE__ ); // /pfad/wp-content/plugins/extensions-leaflet-map/extensions-leaflet-map.php .
define( 'LEAFEXT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); // /pfad/wp-content/plugins/extensions-leaflet-map-github/ .
define( 'LEAFEXT_PLUGIN_URL', WP_PLUGIN_URL . '/' . basename( LEAFEXT_PLUGIN_DIR ) ); // https://url/wp-content/plugins/extensions-leaflet-map-github/ .
define( 'LEAFEXT_PLUGIN_PICTS', LEAFEXT_PLUGIN_URL . '/pict/' ); // https://url/wp-content/plugins/extensions-leaflet-map-github/pict/ .
define( 'LEAFEXT_PLUGIN_SETTINGS', dirname( plugin_basename( __FILE__ ) ) ); // extensions-leaflet-map-github .

if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
// string $plugin_file, bool $markup = true, bool $translate = true
$leafext_plugin_data = get_plugin_data( __FILE__, true, false );
define( 'LEAFEXT_VERSION', $leafext_plugin_data['Version'] );

if ( ! function_exists( 'leafext_plugin_active' ) ) {
	function leafext_plugin_active( $slug ) {
		$plugins   = get_option( 'active_plugins' );
		$is_active = preg_grep( '/^.*\/' . $slug . '\.php$/', $plugins );
		if ( count( $is_active ) === 1 ) {
			return true;
		}
		$plugins = get_site_option( 'active_sitewide_plugins' );
		if ( is_array( $plugins ) ) {
			$is_active = preg_grep( '/^.*\/' . $slug . '\.php$/', array_flip( $plugins ) );
			if ( count( $is_active ) === 1 ) {
				return true;
			}
		}
		return false;
	}
}

if ( is_admin() ) {
	require_once LEAFEXT_PLUGIN_DIR . 'admin.php';
}

require_once LEAFEXT_PLUGIN_DIR . '/php/enqueue-leafletplugins.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/functions.php';
require_once LEAFEXT_PLUGIN_DIR . '/pkg/JShrink/Minifier.php';

require_once LEAFEXT_PLUGIN_DIR . '/php/elevation.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/sgpx.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/multielevation.php';

require_once LEAFEXT_PLUGIN_DIR . '/php/fullscreen.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/gesture.php';

require_once LEAFEXT_PLUGIN_DIR . '/php/hover.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/hoverlap.php';

require_once LEAFEXT_PLUGIN_DIR . '/php/markercluster.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/placementstrategies.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/clustergroup.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/featuregroup.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/parentgroup.php';

require_once LEAFEXT_PLUGIN_DIR . '/php/extramarker.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/geojsonmarker.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/hidemarkers.php';

require_once LEAFEXT_PLUGIN_DIR . '/php/choropleth.php';

require_once LEAFEXT_PLUGIN_DIR . '/php/zoomhome.php';

require_once LEAFEXT_PLUGIN_DIR . '/php/tileserver.php';

require_once LEAFEXT_PLUGIN_DIR . '/php/leaflet-search.php';

require_once LEAFEXT_PLUGIN_DIR . '/php/leaflet-directory.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/managefiles.php';

require_once LEAFEXT_PLUGIN_DIR . '/php/overview-map.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/featured-map.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/targetmarker.php';
require_once LEAFEXT_PLUGIN_DIR . '/php/listmarker.php';

/**
 * Add settings to plugin page.
 */
function leafext_add_action_links( $actions ) {
	$actions[] = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=' . LEAFEXT_PLUGIN_SETTINGS ) ) . '">' . esc_html__( 'Settings', 'extensions-leaflet-map' ) . '</a>';
	return $actions;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'leafext_add_action_links' );

/**
 * For translating elevation.
 */
function leafext_extra_textdomain( $mofile, $domain ) {
	if ( 'extensions-leaflet-map' === $domain ) {
		if ( file_exists( LEAFEXT_PLUGIN_DIR . '/lang/extensions-leaflet-map-' . get_locale() . '.mo' ) ) {
			$mofile = LEAFEXT_PLUGIN_DIR . '/lang/extensions-leaflet-map-' . get_locale() . '.mo';
		}
	}
	return $mofile;
}
add_filter( 'load_textdomain_mofile', 'leafext_extra_textdomain', 10, 2 );

// WP < 6.5, ClassicPress
if ( function_exists( 'classicpress_version' ) ||
( function_exists( 'wp_get_wp_version' ) && version_compare( '6.5', wp_get_wp_version(), '>' ) ) ) {
	function leafext_leaflet_require() {
		if ( ! leafext_plugin_active( 'leaflet-map' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			$message = '<div><p>' . wp_sprintf(
			/* translators: %s are plugin names. */
				esc_html__( 'Please install and activate %1$s before using %2$s.', 'extensions-leaflet-map' ),
				'<a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a>',
				'Extensions for Leaflet Map'
			) . '</p><p><a href="' . esc_html( network_admin_url( 'plugins.php' ) ) . '">' .
				__( 'Manage plugins', 'extensions-leaflet-map' ) . '</a>.</p></div>';
			$error = new WP_Error( 'error', $message );
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- it is an WP Error
			wp_die( $error, '', wp_kses_post( $error->get_error_data() ) );
		}
	}
	register_activation_hook( __FILE__, 'leafext_leaflet_require' );
}

// Disable activation the other of WP / Github Version
if ( ! function_exists( 'leafext_disable_extensions_activation' ) ) {
	function leafext_disable_extensions_activation( $actions, $plugin_file ) {
		if ( array_key_exists( 'activate', $actions ) ) {
			if ( basename( $plugin_file ) === basename( __FILE__ ) ) {
				$actions['activate'] = wp_strip_all_tags( $actions['activate'] );
			}
		}
		return $actions;
	}
}
add_filter( 'plugin_action_links', 'leafext_disable_extensions_activation', 10, 4 );

// Github
if ( is_admin() ) {
	require_once LEAFEXT_PLUGIN_DIR . 'github-backend-extensions.php';
}
