<?php
/**
* Functions for hover shortcode hide title
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_markertitle_script($options){
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/

	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		console.log("leafext_markertitle_script");
		let all_options = <?php echo json_encode($options);?>;
		console.log(all_options);
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var map_id = map._leaflet_id;
		//console.log(map_id);
		var maps=[];
		maps[map_id] = map;

		var markers = window.WPLeafletMapPlugin.markers;
		if (markers.length > 0) {
			for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
				var a = WPLeafletMapPlugin.markers[i];
				if (( a._map != null && a._map._leaflet_id == map_id) || a._map == null ) {
					// console.log(a.options);
					// console.log(a.options.title);
					if ( a.options.title ) {
						// console.log("has title - deleted");
						a.options.title = "";
					}
					if ( a._icon ) {
						// console.log("has _icon - title deleted");
						a._icon.title = "";
					}
					//console.log(a);
					a.unbindTooltip();
					a.bindTooltip("", {visibility: 'hidden', opacity: 0}).closeTooltip();
				}
			}
		}
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}
