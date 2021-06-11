<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [hover]

function leafext_hover_script(){
	$text = '
	<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		if ( WPLeafletMapPlugin.geojsons.length > 0 ) {
			var geojsons = window.WPLeafletMapPlugin.geojsons;
			var geocount = geojsons.length;
			for (var j = 0, len = geocount; j < len; j++) {
				var geojson = geojsons[j];
				//console.log(geojson);
				geojson.layer.on("mouseover", function () {
					//console.log("over");
					this.setStyle({
						fillOpacity: 0.4,
						weight: 5
					});
					this.bringToFront();
				});
				geojson.layer.on("mouseout", function () {
					//console.log("out");
					this.setStyle({
						fillOpacity: 0.2,
						weight: 3
					});
				});
			}
		}
	});
	</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_hover_function(){
	return leafext_hover_script();
}
add_shortcode('hover', 'leafext_hover_function' );
?>
