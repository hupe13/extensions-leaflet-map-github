<?php
// For use with any map on a webpage

function leafext_gestures_script(){
	include_once LEAFEXT_PLUGIN_DIR . '/pkg/JShrink/Minifier.php';
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

function leafext_gestures_function() {
	wp_enqueue_script('gestures_leaflet',
		plugins_url('leaflet-plugins/leaflet-gesture-handling-1.2.1/js/leaflet-gesture-handling.min.js',LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'), null);
	wp_enqueue_style('gestures_leaflet_styles',
		plugins_url('leaflet-plugins/leaflet-gesture-handling-1.2.1/css/leaflet-gesture-handling.min.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_add_inline_script( 'gestures_leaflet', leafext_gestures_script(), 'after' );
}
add_action( 'wp_enqueue_scripts', 'leafext_gestures_function' );

?>
