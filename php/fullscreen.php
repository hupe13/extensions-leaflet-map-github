<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [fullscreen]

function leafext_fullscreen_script(){
	include_once LEAFEXT_PLUGIN_DIR . '/pkg/JShrink/Minifier.php';
	$text = '
	<script>
		window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
		window.WPLeafletMapPlugin.push(function () {
			var map = window.WPLeafletMapPlugin.getCurrentMap();
			// create fullscreen control
			var fsControl = new L.Control.FullScreen();
			// add fullscreen control to the map
			map.addControl(fsControl);
		});
	</script>';
$text = \JShrink\Minifier::minify($text);
return "\n".$text."\n";
}

function leafext_fullscreen_function(){
	wp_enqueue_style( 'leaflet.fullscreen',
		plugins_url('leaflet-plugins/leaflet.fullscreen-2.0.0/Control.FullScreen.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_script('leaflet.fullscreen',
		plugins_url('leaflet-plugins/leaflet.fullscreen-2.0.0/Control.FullScreen.min.js',LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'),null);
	return leafext_fullscreen_script();
}
add_shortcode('fullscreen', 'leafext_fullscreen_function' );
?>
