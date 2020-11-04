<?php
//Shortcode: [zoomhomemap]

//https://stackoverflow.com/questions/43228007/check-if-font-awesome-exists-before-enqueueing-to-wordpress/43229715
function stylesheet_installed($array_css) {
    global $wp_styles;
    foreach( $wp_styles->queue as $style ) {
        foreach ($array_css as $css) {
            if (false !== strpos( $wp_styles->registered[$style]->src, $css ))
                return 1;
        }
    }
    return 0;
}

function home_function(){
	global $post;
	if ( ! is_page() ) return;
	wp_enqueue_script('zoomhome',
		plugins_url('leaflet-plugins/zoomhome.torfsen/leaflet.zoomhome.min.js',LEAFEXT__PLUGIN_FILE),
			array('wp_leaflet_map'), '1.0', true);
	wp_enqueue_style('zoomhome',
		plugins_url('leaflet-plugins/zoomhome.torfsen/leaflet.zoomhome.css',LEAFEXT__PLUGIN_FILE),
			array('leaflet_stylesheet'));
	// Font awesome
	$font_awesome = array('font-awesome', 'fontawesome');
	if (stylesheet_installed($font_awesome) === 0) {
			wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
	}
	// custom js
	wp_enqueue_script('myzoomhome',
		plugins_url('js/zoomhome.js',LEAFEXT__PLUGIN_FILE), array('zoomhome'), '1.0', true);
	return "";
}
add_shortcode('zoomhomemap', 'home_function' );
