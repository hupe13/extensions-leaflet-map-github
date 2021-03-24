<?php
/**
 * Plugin Name: Extensions for Leaflet Map
 * Description: Extensions of the Wordpress plugin Leaflet Map
 * Version: 0.0.55
 * Author: hupe13
 * Plugin URI: https://wordpress.org/plugins/extensions-leaflet-map/
**/

// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

define('LEAFEXT__PLUGIN_FILE', __FILE__);
define('LEAFEXT__PLUGIN_DIR', plugin_dir_path(__FILE__));

if ( ! function_exists( 'is_plugin_active' ) )
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

if ( ! is_plugin_active( 'leaflet-map/leaflet-map.php' ) ) {
  function require_leaflet_map_plugin(){?>
    <div class="notice notice-error" >
      <p> Please enable Leaflet-Map Plugin before using Extensions for Leaflet Map.</p>
    </div><?php
  }
  add_action('admin_notices','require_leaflet_map_plugin');
  register_activation_hook(__FILE__, 'require_leaflet_map_plugin');
}

if (is_admin()) include_once LEAFEXT__PLUGIN_DIR . 'admin.php';
include_once LEAFEXT__PLUGIN_DIR . '/php/elevation.php';
include_once LEAFEXT__PLUGIN_DIR . '/php/fullscreen.php';
include_once LEAFEXT__PLUGIN_DIR . '/php/gesture.php';
include_once LEAFEXT__PLUGIN_DIR . '/php/hide-markers.php';
include_once LEAFEXT__PLUGIN_DIR . '/php/hovergeojson.php';
include_once LEAFEXT__PLUGIN_DIR . '/php/markercluster.php';
include_once LEAFEXT__PLUGIN_DIR . '/php/zoomhome.php';
?>
