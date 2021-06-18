<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [hover]
function leafext_geojsonhover_script($url){
	$text = '<script>';
	$text = $text . 'var url = "'.$url.'";';
	$text = $text . file_get_contents(LEAFEXT_PLUGIN_DIR . '/js/hovergeojson.js');
	$text = $text .'</script>';
	//var_dump($text); wp_die();
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_geojsonhover_function($atts){
	$exclude = shortcode_atts( array('exclude' => false), $atts);
	$exclude['exclude']= str_replace ( '/' , '\/' , $exclude['exclude'] );
	$text=leafext_geojsonhover_script($exclude['exclude']);
	return $text;
}
add_shortcode('hover', 'leafext_geojsonhover_function' );
?>
