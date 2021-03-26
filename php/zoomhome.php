<?php
//Shortcode: [zoomhomemap]

//https://stackoverflow.com/questions/43228007/check-if-font-awesome-exists-before-enqueueing-to-wordpress/43229715
function leafext_plugin_stylesheet_installed($array_css) {
    global $wp_styles;
    foreach( $wp_styles->queue as $style ) {
        foreach ($array_css as $css) {
            if (false !== strpos( $wp_styles->registered[$style]->src, $css ))
                return 1;
        }
    }
    return 0;
}

function leafext_plugin_zoomhome_function(){
	wp_enqueue_script('zoomhome',
		plugins_url('leaflet-plugins/leaflet.zoomhome/leaflet.zoomhome.min.js',LEAFEXT_PLUGIN_FILE),
			array('wp_leaflet_map'), null);
	wp_enqueue_style('zoomhome',
		plugins_url('leaflet-plugins/leaflet.zoomhome/leaflet.zoomhome.css',LEAFEXT_PLUGIN_FILE),
			array('leaflet_stylesheet'), null);
	// Font awesome
	$font_awesome = array('font-awesome', 'fontawesome');
	if (leafext_plugin_stylesheet_installed($font_awesome) === 0) {
			wp_enqueue_style('font-awesome',
        plugins_url('css/font-awesome.min.css',LEAFEXT_PLUGIN_FILE),
          array('zoomhome'), null);
	}
	// custom js
	wp_enqueue_script('myzoomhome',
		plugins_url('js/zoomhome.min.js',LEAFEXT_PLUGIN_FILE), array('zoomhome'), null);
	return "";
}
add_shortcode('zoomhomemap', 'leafext_plugin_zoomhome_function' );
