<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

// For use with any map on a webpage
function leafext_gestures_script(){
	$text = '
	// For use with any map on a webpage
	//GestureHandling disables the following map attributes.
	//dragging
	//tap
	//scrollWheelZoom
	(function() {
		function main() {
			var maps = window.WPLeafletMapPlugin.maps;
			//console.log("gesture");
			for (var i = 0, len = maps.length; i < len; i++) {
				var map = maps[i];
				if ( map.dragging.enabled()
						|| map.scrollWheelZoom.enabled()
					) {
					//console.log("enabled");
					map.gestureHandling.enable();
				}
			}
		}
		window.addEventListener("load", main);
	})();
	';
$text = \JShrink\Minifier::minify($text);
return "\n".$text."\n";
}

// For use with one map on a webpage
function leafext_gesture_script(){
	$text = '
	<script>
		window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
		window.WPLeafletMapPlugin.push(function () {
			var map = window.WPLeafletMapPlugin.getCurrentMap();
			if ( map.dragging.enabled() || map.scrollWheelZoom.enabled() ) {
				//console.log("enabled");
				map.gestureHandling.enable();
			}
	});
	</script>';
$text = \JShrink\Minifier::minify($text);
return "\n".$text."\n";
}

function leafext_gestures_function() {
	$defaults = array(
		'on'     => true,
	);
	$options = shortcode_atts($defaults, get_option('leafext_gesture'));
	if ( (bool) $options['on'] ) {
		wp_enqueue_script('gestures_leaflet',
			plugins_url('leaflet-plugins/leaflet-gesture-handling-1.3.5/js/leaflet-gesture-handling-leafext.min.js',LEAFEXT_PLUGIN_FILE),
			array('wp_leaflet_map'), null);
		wp_enqueue_style('gestures_leaflet_styles',
			plugins_url('leaflet-plugins/leaflet-gesture-handling-1.3.5/css/leaflet-gesture-handling.min.css',LEAFEXT_PLUGIN_FILE),
			array('leaflet_stylesheet'),null);
		wp_add_inline_script( 'gestures_leaflet', leafext_gestures_script(), 'after' );
	}
}
//add_action( 'wp_enqueue_scripts', 'leafext_gestures_function' );

add_filter('pre_do_shortcode_tag', function ( $output, $shortcode ) {
  if ( 'leaflet-map' == $shortcode ) {
    //add_action( 'wp_enqueue_scripts', 'leafext_gestures_function' );
	leafext_gestures_function();
  }
  return $output;
}, 10, 2);

function leafext_gesture_shortcode(){
	$defaults = array(
		'on'     => true,
	);
	$options = shortcode_atts($defaults, get_option('leafext_gesture'));
	if ( ! (bool) $options['on'] ) {
		wp_enqueue_script('gestures_leaflet',
		plugins_url('leaflet-plugins/leaflet-gesture-handling-1.3.4/js/leaflet-gesture-handling-leafext.min.js',	LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'), null);
		wp_enqueue_style('gestures_leaflet_styles',
		plugins_url('leaflet-plugins/leaflet-gesture-handling-1.3.4/css/leaflet-gesture-handling.min.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
		return leafext_gesture_script();
	}
}
add_shortcode('gestures', 'leafext_gesture_shortcode' );
?>
