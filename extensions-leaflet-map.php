<?php
/**
 * Plugin Name: Extensions for Leaflet Map Test
 * Description: Extensions for the Wordpress plugin Leaflet Map
 * Version: 1.1
 * Author: hupe13
 * GitHub Plugin URI: https://github.com/hupe13/extensions-leaflet-map
 * Primary Branch: main
**/

// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

define('LEAFEXT_PLUGIN_FILE', __FILE__);
define('LEAFEXT_PLUGIN_DIR', plugin_dir_path(__FILE__));

if ( ! function_exists( 'is_plugin_active' ) )
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

if ( ! is_plugin_active( 'leaflet-map/leaflet-map.php' ) ) {
  function leafext_require_leaflet_map_plugin(){?>
    <div class="notice notice-error" >
      <p> Please install and activate <a href="https://wordpress.org/plugins/leaflet-map/">Leaflet Map Plugin</a> before using Extensions for Leaflet Map.</p>
    </div><?php
  }
  add_action('admin_notices','leafext_require_leaflet_map_plugin');
  register_activation_hook(__FILE__, 'leafext_require_leaflet_map_plugin');
}

if (is_admin()) {
  include_once LEAFEXT_PLUGIN_DIR . 'admin.php';
} else {
  include_once LEAFEXT_PLUGIN_DIR . '/php/elevation.php';
  include_once LEAFEXT_PLUGIN_DIR . '/php/fullscreen.php';
  include_once LEAFEXT_PLUGIN_DIR . '/php/gesture.php';
  include_once LEAFEXT_PLUGIN_DIR . '/php/hide-markers.php';
  include_once LEAFEXT_PLUGIN_DIR . '/php/hovergeojson.php';
  include_once LEAFEXT_PLUGIN_DIR . '/php/markercluster.php';
  include_once LEAFEXT_PLUGIN_DIR . '/php/zoomhome.php';
  //
  include_once LEAFEXT_PLUGIN_DIR . '/php/layerswitch.php';
  include_once LEAFEXT_PLUGIN_DIR . '/php/featuregroup.php';
  //include_once LEAFEXT_PLUGIN_DIR . '/php/elevation_inline.php';
}

// Add settings to plugin page
function leafext_add_action_links ( $actions ) {
	$actions[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=extensions-leaflet-map') ) .'">'. esc_html__( "Settings").'</a>';
  return $actions;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'leafext_add_action_links' );
?>
