<?php
/**
 * Functions for removing wp-gpx-maps plugin and using elevation instead
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_dequeue_sgpx() {
	wp_dequeue_script('leaflet');
	wp_dequeue_style('leaflet.fullscreen');
	wp_dequeue_style('leaflet');
	wp_dequeue_style('leaflet.markercluster');
	wp_dequeue_script('leaflet.markercluster');
	wp_dequeue_style('leaflet.Photo');
	wp_dequeue_script('leaflet.Photo');
	wp_dequeue_script('leaflet.fullscreen');
   	wp_dequeue_script('WP-GPX-Maps');
	wp_dequeue_script('chartjs');
//	wp_dequeue_script('jquery');
	wp_deregister_script('leaflet');
	wp_deregister_style('leaflet.fullscreen');
	wp_deregister_style('leaflet');
	wp_deregister_style('leaflet.markercluster');
	wp_deregister_script('leaflet.markercluster');
	wp_deregister_style('leaflet.Photo');
	wp_deregister_script('leaflet.Photo');
	wp_deregister_script('leaflet.fullscreen');
   	wp_deregister_script('WP-GPX-Maps');
	wp_deregister_script('chartjs');
//	wp_deregister_script('jquery');
	remove_action('wp_print_styles', 'print_WP_GPX_Maps_styles' );
	unload_textdomain( "wp-gpx-maps" );
}

function leafext_dequeue_all_sgpx(){
	leafext_dequeue_sgpx();
}
add_action( 'wp_enqueue_scripts', 'leafext_dequeue_all_sgpx' , 100);

function leafext_sgpx_function( $atts ) {
	$options=get_option('leafext_eleparams');
	if ( ! $options['sgpx'] ) {
		$text = __("You are using the sgpx shortcode from plugin wp-gpx-maps. wp-gpx-maps and leaflet-map don't work at the same page or post.","extensions-leaflet-map");
		$text = $text."<p>[sgpx ";
		foreach ($atts as $key=>$item){
			$text = $text. "$key = $item ";
		}
		$text = $text. "]</p>";
		return $text;
	} else {
		//$text = '[leaflet-map][elevation gpx="'.$atts['gpx'].'"]';
		return do_shortcode('[leaflet-map zoomcontrol][elevation gpx="'.$atts['gpx'].'"]');
	}
}
//add_shortcode('sgpx', 'leafext_sgpx_function' );

function leafext_remove_sgpx_shortcode() {
    remove_shortcode( 'sgpx' );
	add_shortcode('sgpx', 'leafext_sgpx_function' );
}
add_action( 'init', 'leafext_remove_sgpx_shortcode',20 );

function leafext_sgpx_script( ){
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		
	
	});
	</script>';
	//$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}
