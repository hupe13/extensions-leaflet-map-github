<?php
/**
* Functions for hidemarkers shortcode
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [hidemarkers]
function leafext_hidemarkers_function(){
	$text = leafext_should_interpret_shortcode('hidemarkers',0);
	if ( $text != "" ) {
		return $text;
	} else {
		$text = '<script><!--';
		ob_start();
		?>/*<script>*/
		window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
		window.WPLeafletMapPlugin.push(function () {
			var map = window.WPLeafletMapPlugin.getCurrentMap();
			map.eachLayer(function(layer) {
				if (layer.options.type == "gpx" ) {
					//console.log("gpx");
					layer.options.filter = function (geoJsonFeature) {
						if (geoJsonFeature.geometry.type == "Point" ) {
							//console.log("wpt");
							return false;
						} else {
							//console.log("kein wpt");
							return true;
						}
					} //layer.options.filter
				}; // if
			}); //map.eachLayer
		});
		<?php
		$javascript = ob_get_clean();
		$text = $text . $javascript . '//-->'."\n".'</script>';
		$text = \JShrink\Minifier::minify($text);
		return $text;
	}
}
add_shortcode('hidemarkers', 'leafext_hidemarkers_function' );
