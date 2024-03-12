<?php
/**
 * Plugin Name:       Extensions for Leaflet Map Github
 * Plugin URI:        https://leafext.de/en/
 * GitHub Plugin URI: https://github.com/hupe13/extensions-leaflet-map-github
 * Primary Branch:    main
 * Description:       Extensions for the WordPress plugin Leaflet Map Github Version
 * Version:           4.2-24031x
 * Requires PHP:      7.4
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
define( 'LEAFEXT_PLUGIN_SETTINGS', dirname( plugin_basename( __FILE__ ) ) ); // extensions-leaflet-map .

function leafext_plugin_init() {
	if ( is_admin() ) {
		if ( ! defined( 'LEAFLET_MAP__PLUGIN_DIR' ) && ! is_main_site() ) {
			function leafext_require_leaflet_map_plugin() {
				echo '<div class="notice notice-error" ><p> ';
				printf(
					__( 'Please install and activate %1$s before using %2$s.', 'extensions-leaflet-map' ),
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
add_action( 'plugins_loaded', 'leafext_plugin_init' );

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
	if ( get_locale() === 'sv_SE' || get_locale() === 'it_IT' ) {
		load_plugin_textdomain( 'extensions-leaflet-map', false, LEAFEXT_PLUGIN_SETTINGS . '/lang/' );
	}
}
add_action( 'plugins_loaded', 'leafext_extra_textdomain' );

function leafext_is_github() {
	$local = get_file_data(
		LEAFEXT_PLUGIN_DIR . 'extensions-leaflet-map.php',
		array(
			'Title' => 'Plugin Name',
		)
	);
	if ( strpos( $local['Title'], 'Github' ) !== false ) {
		return true;
	} else {
		return false;
	}
}

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
use YahnisElsts\PluginUpdateChecker\v5p4\Vcs\GitHubApi;
if ( is_admin() && leafext_is_github() ) {
	if ( is_main_site() ) {
		require_once LEAFEXT_PLUGIN_DIR . '/pkg/plugin-update-checker/plugin-update-checker.php';
		$perm_denied = get_transient( 'leafext_github_403' );
		$setting     = get_option( 'leafext_updating', array( 'token' => '' ) );
		if ( $setting && isset( $setting['token'] ) && $setting['token'] !== '' ) {
			$token = $setting['token'];
		} else {
			$token = '';
		}
		if ( false === $perm_denied || $token !== '' ) {
			$github_update_checker = PucFactory::buildUpdateChecker(
				'https://github.com/hupe13/extensions-leaflet-map-github/',
				__FILE__,
				LEAFEXT_PLUGIN_SETTINGS
			);

			$github_update_checker->addFilter(
				'vcs_update_detection_strategies',
				function ( $strategies ) {
					unset( $strategies[ GitHubApi::STRATEGY_LATEST_RELEASE ] );
					return $strategies;
				}
			);

			// Set the branch that contains the stable release.
			$github_update_checker->setBranch( 'main' );

			if ( $token !== '' ) {
				// Optional: If you're using a private repository, specify the access token like this:
				$github_update_checker->setAuthentication( $token );
			}

			function leafext_github_puc_error( $error, $response = null, $url = null, $slug = null ) {
				if ( isset( $slug ) && $slug !== LEAFEXT_PLUGIN_SETTINGS ) {
					return;
				}
				if ( wp_remote_retrieve_response_code( $response ) === 403 ) {
					var_dump("Permission denied");
					set_transient( 'leafext_github_403', true, DAY_IN_SECONDS );
				}
			}
			add_action( 'puc_api_error', 'leafext_github_puc_error', 10, 4 );
		}
	}
}
