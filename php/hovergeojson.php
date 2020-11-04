<?php
//Shortcode: [hover]
function hover_function(){
	global $post;
	if ( ! is_page() ) return;
	// custom js
	wp_enqueue_script('hovergeojson_custom', plugins_url('js/hovergeojson.js',LEAFEXT__PLUGIN_FILE), array('wp_leaflet_map'), '1.0', true);
}
add_shortcode('hover', 'hover_function' );
?>
