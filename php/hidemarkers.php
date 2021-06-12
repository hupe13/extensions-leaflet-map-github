<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [hidemarkers]
function leafext_hidemarkers_function(){
	$text = '
	<script>
		window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
		window.WPLeafletMapPlugin.push(function () {
			var map = window.WPLeafletMapPlugin.getCurrentMap();
			map.eachLayer(function(layer) {
				if (layer.options.type == "gpx" ) {
					//console.log("gpx");
					layer.options.pointToLayer = function (feature, latlng) {
						//console.log(feature);
						return L.circleMarker(latlng,{"radius": 0 });
					}; // layer.options
				}; // if
			}); //map.eachLayer
		});
	</script>
	';
	$text = \JShrink\Minifier::minify($text);
	return $text;
}
add_shortcode('hidemarkers', 'leafext_hidemarkers_function' );
?>
