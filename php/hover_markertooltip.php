<?php
/**
* Functions for hover shortcode markertooltip
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_markertooltip_script($options){
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/

	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		console.log("leafext_markertooltip_script");
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
					a.on("mouseover", function (e) {
						//console.log("marker mouseover");
						//console.log(e);
						if (typeof e.sourceTarget.getPopup() != "undefined") {
							if ( ! e.sourceTarget.getPopup().isOpen()) {
								map.closePopup();
								//console.log(e.sourceTarget.options);
								if ( typeof e.sourceTarget.getPopup().getContent() != "undefined" )
								var content = e.sourceTarget.getPopup().getContent();
								if ( typeof content != "undefined" ) {
									//console.log(e.sourceTarget);
									e.sourceTarget.bindTooltip(content);
									e.sourceTarget.openTooltip(e.latlng);
								}
							}
						}
					});
					a.on("click", function (e) {
						//console.log("click");
						e.sourceTarget.unbindTooltip();
					});
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
