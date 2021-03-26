<?php
//Shortcode: [fullscreen]
function leafext_fc_function(){
	wp_enqueue_style( 'leaflet.fullscreen',
		plugins_url('leaflet-plugins/leaflet.fullscreen-2.0.0/Control.FullScreen.css',LEAFEXT_PLUGIN_FILE),
		array('leaflet_stylesheet'),null);
	wp_enqueue_script('leaflet.fullscreen',
		plugins_url('leaflet-plugins/leaflet.fullscreen-2.0.0/Control.FullScreen.min.js',LEAFEXT_PLUGIN_FILE),
		array('wp_leaflet_map'),null);
	// my
	wp_enqueue_script('myfullscreen',
		plugins_url('js/fullscreen.min.js',LEAFEXT_PLUGIN_FILE), array('leaflet.fullscreen'),null);
}
add_shortcode('fullscreen', 'leafext_fc_function' );
?>
