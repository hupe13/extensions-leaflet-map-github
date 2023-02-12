<?php
/**
* Functions for fullscreen shortcode
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [fullscreen]
function leafext_fullscreen_script() {
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		// create fullscreen control
		var fsControl = new L.Control.FullScreen();
		// add fullscreen control to the map
		map.addControl(fsControl);
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_fullscreen_function(){
	$text = leafext_should_interpret_shortcode('fullscreen',0);
	if ( $text != "" ) {
		return $text;
	} else {
		leafext_enqueue_fullscreen ();
		return leafext_fullscreen_script();
	}
}
add_shortcode('fullscreen', 'leafext_fullscreen_function' );
