<?php
/**
 * Functions for zoomhomemap shortcode
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [zoomhomemap]
function leafext_zoomhome_script($options){
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
	  var map_id = map._leaflet_id;
	  var maps=[];
	  maps[map_id] = map;
		// parameter fit: only when map !fitbound and ele fitbounds then set zoomhome to map,
		// not in elevation
		// 0: home = ele fitbounds (default)
		// 1: home = map
		var allfit = [];
		if (<?php echo wp_json_encode((bool)$options['fit']); ?> && typeof maps[map_id]._shouldFitBounds === "undefined" ) {
			allfit[map_id] = new L.latLngBounds();
		}
		var position = <?php echo wp_json_encode($options['position']); ?>;
		leafext_zoomhome_js(maps,map_id,allfit,position);
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_zoomhome_function($atts,$content,$shortcode) {
	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		leafext_enqueue_zoomhome ();
		leafext_enqueue_leafext("zoomhome","zoomhome");
		//
		$defaults = array(
			'fit' => 1,
			'position' => "topleft",
		);
		$atts1 = leafext_clear_params($atts);
		$params = shortcode_atts($defaults, $atts1);
		if (!leafext_check_position_control($params['position'])) $params['position'] = "topleft";
		return leafext_zoomhome_script($params);
	}
}
add_shortcode('zoomhomemap', 'leafext_zoomhome_function' );
