<?php
function leafext_help () {
	$leafext_picts = WP_PLUGIN_URL.'/extensions-leaflet-map/pict/';
    $text = '<h3 id="shortcodes">Shortcodes</h3>';
		include 'help/elevation.php';
		include 'help/layerswitch.php';
		include 'help/markercluster.php';
		include 'help/markergroup.php';
		include 'help/zoomhome.php';
		include 'help/fullscreen.php';
		include 'help/hovergeojson.php';
		include 'help/gesture.php';
		include 'help/hidemarkers.php';
	return $text;
}
