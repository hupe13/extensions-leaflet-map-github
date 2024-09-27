<?php
/**
 * Plugin Name:       GIS Map Visualizer
 * Plugin URI:        
 * Description:       Visualize tiles and GEO json data exported GIS.
 * Version:           100.0
 * Requires Plugins:  leaflet-map
 * Requires PHP:      7.4
 * Author:            Ibuki Fukasawa (@XNOVA Tech)
 * Author URI:        
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
define( 'LEAFEXT_PLUGIN_SETTINGS', dirname( plugin_basename( __FILE__ ) ) ); // extensions-leaflet-map .

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
require_once LEAFEXT_PLUGIN_DIR . '/php/targetmarker.php';

/**
 * Add settings to plugin page.
 */
function leafext_add_action_links( $actions ) {
	$actions[] = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=' . LEAFEXT_PLUGIN_SETTINGS ) ) . '">' . esc_html__( 'Settings' ) . '</a>';
	return $actions;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'leafext_add_action_links' );

/**
 * For translating a plugin.
 */
function leafext_extra_textdomain() {
	if ( file_exists( LEAFEXT_PLUGIN_SETTINGS . '/lang/extensions-leaflet-map-' . get_locale() . '.mo' ) ) {
		load_plugin_textdomain( 'extensions-leaflet-map', false, LEAFEXT_PLUGIN_SETTINGS . '/lang/' );
	}
}
add_action( 'plugins_loaded', 'leafext_extra_textdomain' );

// WP < 6.5 
global $wp_version;
if ( $wp_version < '6.5' ) {
	function leafext_plugin_init() {
		if ( is_admin() ) {
			if ( ! defined( 'LEAFLET_MAP__PLUGIN_DIR' ) ) {
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
					// register_activation_hook( __FILE__, 'leafext_require_leaflet_map_plugin' ); //?
				}
			}
		}
	}
	add_action( 'plugins_loaded', 'leafext_plugin_init' );
}
