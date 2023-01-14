<?php
/**
* Functions for choropleth shortcode
* extensions-leaflet-map
*/

/*
*	Doku choropleth deutsch https://doc.arcgis.com/de/insights/latest/create/choropleth-maps.htm
* https://gisgeography.com/choropleth-maps-data-classification/
*/

// mode: q for quantile, e for equidistant, k for k-means
// quantile maps try to arrange groups so they have the same quantity.
// equidistant: divide the classes into equal groups.
// k-means: each standard deviation becomes a class (?)

// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_choropleth_params() {
	$params = array(
		array('valueproperty', __('which property in the features to use',"extensions-leaflet-map"), "", 'property'),
		array('scale', __('a comma separated list of colors for scale - include as many as you like',"extensions-leaflet-map"), "white, red", '"white, red, blue"'),
		array('fillopacity', __('opacity of the colors in scale',"extensions-leaflet-map"), "0.8", "0.8"),
		array('steps', __('number of breaks or steps in range',"extensions-leaflet-map"), "5", "5"),
		array('mode', __('q for quantile, e for equidistant, k for k-means',"extensions-leaflet-map"), "q", "q"),
		array('legend', __('show legend',"extensions-leaflet-map"), true, "!legend"),
		array('hover', __('get a tooltip on mouse over',"extensions-leaflet-map"), true, "!hover"),
	);
	return $params;
}

//Shortcode: [choropleth]
function leafext_choropleth_script($atts,$content) {
	if (is_singular() || is_archive()) {
		$text = '
		<script>
		window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
		window.WPLeafletMapPlugin.push(function () {
			var map = window.WPLeafletMapPlugin.getCurrentMap();
			var att_valueProperty = '.json_encode($atts['valueproperty']).';
			var att_scale = '.json_encode($atts['scale']).'.split(",");
			var att_steps = '.json_encode($atts['steps']).';
			var att_mode = '.json_encode($atts['mode']).';
			var att_popup = '.json_encode($content).';
			var att_legend = '.json_encode((bool)$atts['legend']).';
			var att_hover = '.json_encode((bool)$atts['hover']).';
			var att_fillOpacity = '.json_encode($atts['fillopacity']).';
			console.log(att_valueProperty,att_scale,att_steps,att_mode,att_legend,att_hover,att_fillOpacity);
			console.log(att_popup);
		';
		$text = $text.file_get_contents(LEAFEXT_PLUGIN_URL.'/js/choropleth.js');
		$text = $text.'
		});
		</script>';
		$text = \JShrink\Minifier::minify($text);
		return "\n".$text."\n";
	} else {
		$text = "[choropleth]";
		return $text;
	}
}

function leafext_choropleth_function($atts,$content){
	if (is_singular() || is_archive()) {
		leafext_enqueue_choropleth ();
		$params = leafext_choropleth_params();
		$defaults=array();
		foreach($params as $param) {
			$defaults[$param[0]] = $param[2];
		}
		//var_dump($params,$defaults);
		$options = shortcode_atts($defaults,leafext_clear_params($atts));
		$options['scale'] = str_replace(' ', '', $options['scale']);
		if ($content == "") $content = $options['valueproperty'].": {".$options['valueproperty']."}";
		$content = str_replace('{', "+{", $content);
		$content = str_replace('}', "}+", $content);
		$content = preg_split('/\+/',$content);
		//var_dump($atts); wp_die("test");
		return leafext_choropleth_script($options,$content);
	} else {
		$text = "[choropleth ";
		if (is_array($atts)) {
			foreach ($atts as $key=>$item) {
				$text = $text. "$key=$item ";
			}
		}
		$text = $text. "]";
		return $text;
	}
}
add_shortcode('choropleth', 'leafext_choropleth_function');
