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
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var att_valueProperty = <?php echo json_encode($atts['valueproperty']);?>;
		var att_scale = <?php echo json_encode($atts['scale']);?>.split(",");
		var att_steps = <?php echo json_encode($atts['steps']);?>;
		var att_mode = <?php echo json_encode($atts['mode']);?>;
		var att_popup = <?php echo json_encode($content);?>;
		var att_legend = <?php echo json_encode((bool)$atts['legend']);?>;
		var att_hover = <?php echo json_encode((bool)$atts['hover']);?>;
		var att_fillOpacity = <?php echo json_encode($atts['fillopacity']);?>;
		console.log(att_valueProperty,att_scale,att_steps,att_mode,att_legend,att_hover,att_fillOpacity);
		console.log(att_popup);
		leafext_choropleth_js(map,att_valueProperty,att_scale,att_steps,att_mode,att_popup,att_legend,att_hover,att_fillOpacity);
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_choropleth_function($atts,$content,$shortcode) {
	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		leafext_enqueue_leafext("choropleth");
		leafext_enqueue_choropleth ();
		$params = leafext_choropleth_params();
		$defaults = array();
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
	}
}
add_shortcode('choropleth', 'leafext_choropleth_function');
