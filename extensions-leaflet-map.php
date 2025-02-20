<?php
/**
 * Plugin Name:       Extensions for Leaflet Map Github Version
 * Description:       Extends the WordPress Plugin <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a> with Leaflet Plugins and other functions.
 * Plugin URI:        https://leafext.de/en/
 * Version:           4.4-250220
 * Requires PHP:      7.4
 * Requires Plugins:  leaflet-map
 * Author:            hupe13
 * Author URI:        https://leafext.de/en/
 * License:           GPL v2 or later
 * Text Domain:       extensions-leaflet-map
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
$plugin_data = get_plugin_data( __FILE__, true, false );
define( 'LEAFEXT_VERSION', $plugin_data['Version'] );

if ( is_admin() ) {
	require_once LEAFEXT_PLUGIN_DIR . 'admin.php';
}

if ( ! function_exists( 'leafext_plugin_active' ) ) {
	function leafext_plugin_active( $slug ) {
		$plugins = glob( WP_PLUGIN_DIR . '/*/' . $slug . '.php' );
		foreach ( $plugins as $plugin ) {
			$split = array_map( 'strrev', explode( '/', strrev( $plugin ) ) );
			if ( is_plugin_active( trailingslashit( $split[1] ) . $split[0] ) ) {
				if ( $split[1] === 'leafext-update-github' ) {
					return true;
				}
				if ( $split[1] !== $slug ) {
					return 'github';
				}
				return true;
			}
		}
		return false;
	}
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
function leafext_extra_textdomain() {
	if ( file_exists( LEAFEXT_PLUGIN_DIR . '/lang/extensions-leaflet-map-' . get_locale() . '.mo' ) ) {
		load_plugin_textdomain( 'extensions-leaflet-map', false, LEAFEXT_PLUGIN_SETTINGS . '/lang/' );
	}
}
add_action( 'plugins_loaded', 'leafext_extra_textdomain' );

// WP < 6.5
global $wp_version;
if ( version_compare( $wp_version, '6.5', '<' ) ) {
	function leafext_extensions_require() {
		if ( ! is_plugin_active( 'leaflet-map/leaflet-map.php' ) ) {
			if ( ( is_multisite() && ! is_main_site() ) || ! is_multisite() ) {
				function leafext_require_leaflet_map_plugin() {
					echo '<div class="notice notice-error" ><p> ';
					printf(
						/* translators: %s are plugin names. */
						esc_html__( 'Please install and activate %1$s before using %2$s.', 'extensions-leaflet-map' ),
						'<a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map</a>',
						'Extensions for Leaflet Map'
					);
					echo '</p></div>';
				}
				add_action( 'admin_notices', 'leafext_require_leaflet_map_plugin' );
			}
		}
	}
	add_action( 'plugins_loaded', 'leafext_extensions_require' );
}

// Github
if ( is_admin() ) {
	require_once LEAFEXT_PLUGIN_DIR . 'github-backend-extensions.php';
}
