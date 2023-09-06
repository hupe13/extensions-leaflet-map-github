<?php
/**
* Functions for hover shortcode
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_hoverlap_params() {
	$params = array(
		array(
			'param' => 'exclude',
			'desc' => __('exclude any geojson, gpx, kml with a defined substring in the src url from changing its style on hovering, the tooltip is not affected','extensions-leaflet-map'),
			'default' => '',
			'values' => __('substring from url to geojson, gpx, kml file',"extensions-leaflet-map"),
			'element' => false,
			'only' => false,
		),
		array(
			'param' => 'tolerance',
			'desc' => __('determines how much to extend click tolerance round an object on the map','extensions-leaflet-map'),
			'default' => 0,
			'values' => __('a number',"extensions-leaflet-map"),
			'element' => false,
			'only' => false,
		),
		array(
			'param' => 'class',
			'desc' => __('className for the tooltip','extensions-leaflet-map'),
			'default' => 'leafext-tooltip',
			'values' => __('a className',"extensions-leaflet-map"),
			'element' => false,
			'only' => false,
		),
		// array(
		// 	'param' => '',
		// 	'desc' => __('',"extensions-leaflet-map"),
		// 	'default' => '',
		// 	'values' => __('',"extensions-leaflet-map"),
		// ),
	);
	return $params;
}

function leafext_hoverlap_function($atts,$content,$shortcode) {
	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		leafext_enqueue_js();
		leafext_enqueue_geometry();
		leafext_enqueue_turf();
		leafext_enqueue_leafext("hoverlap",'leafextturf');
		$defaults=array();
		$params = leafext_hoverlap_params();
		foreach($params as $param) {
			$defaults[$param['param']] = $param['default'];
		}
		$settings = shortcode_atts(	$defaults, get_option( 'leafext_canvas' ));
		$options  = shortcode_atts( $settings, leafext_clear_params($atts));
		//var_dump($settings,$atts,leafext_clear_params($atts),$options); wp_die();
		$text = "";
		if ($options['tolerance'] != 0) {
			$text = $text.leafext_canvas_script( $options['tolerance'] );
		}
		$text = $text.leafext_hoverlap_script($options);
		return $text;
	}
}
add_shortcode('hoverlap', 'leafext_hoverlap_function');

//Shortcode: [hoverlap]
function leafext_hoverlap_script($options){
  $text = '<script><!--';
  ob_start();
  ?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
  window.WPLeafletMapPlugin.push(function () {
    let all_options = <?php echo json_encode($options);?>;
    // console.log("leafext_hoverlap_script");
    // console.log(all_options);
    leafext_hoverlap_js(all_options);
  });
  <?php
  $javascript = ob_get_clean();
  $text = $text . $javascript . '//-->'."\n".'</script>';
  $text = \JShrink\Minifier::minify($text);
  return "\n".$text."\n";
}
