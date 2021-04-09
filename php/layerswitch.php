<?php
//Shortcode: [layerswitch]
function layerswitch_function(){
	$options = get_option('leafext_maps');
	//var_dump($options);
	if (!is_array($options )) return;

	// custom js
	wp_enqueue_script('layerswitch_custom', esc_url( plugins_url( 'js/layerswitch.js',
		dirname(__FILE__) ) ), Array('wp_leaflet_map'), null);

	//Uebergabe der php Variablen an Javascript
	wp_localize_script( 'layerswitch_custom', 'mylayers', $options);
}
add_shortcode('layerswitch', 'layerswitch_function' );
?>
