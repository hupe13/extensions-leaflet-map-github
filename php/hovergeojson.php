<?php
//Shortcode: [hover]
function leafext_hover_function(){
	// custom js
	wp_enqueue_script('hovergeojson_custom', plugins_url('js/hovergeojson.min.js',LEAFEXT_PLUGIN_FILE), array('wp_leaflet_map'), null);
}
add_shortcode('hover', 'leafext_hover_function' );
?>
