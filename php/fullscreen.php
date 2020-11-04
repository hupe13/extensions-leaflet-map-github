<?php
//Shortcode: [fullscreen]
function fc_function(){
	global $post;
	if ( ! is_page() ) return;
	wp_enqueue_style( 'leaflet.fullscreen',
		'https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.css',
		array('leaflet_stylesheet'));
	wp_enqueue_script('leaflet.fullscreen',
		'https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.js',
		array('leaflet_js'));
	// my
	wp_enqueue_script('myfullscreen',
		plugins_url('js/fullscreen.js',LEAFEXT__PLUGIN_FILE), array('leaflet.fullscreen'), '1.0', true);
}
add_shortcode('fullscreen', 'fc_function' );
?>
