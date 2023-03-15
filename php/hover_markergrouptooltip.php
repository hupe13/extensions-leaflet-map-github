<?php
/**
* Functions for hover shortcode markergrouptooltip
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [hover]
function leafext_markergrouptooltip_script($options){
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/

	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		let all_options = <?php echo json_encode($options);?>;
		console.log("leafext_markergrouptooltip_script");
		console.log(all_options);

		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var map_id = map._leaflet_id;
		//console.log(map_id);
		var maps=[];
		maps[map_id] = map;

		var markergroups = window.WPLeafletMapPlugin.markergroups;
		Object.entries(markergroups).forEach(([key, value]) => {
			if ( markergroups[key]._map !== null ) {
				if (map_id == markergroups[key]._map._leaflet_id) {
					//console.log("markergroups loop");
					markergroups[key].eachLayer(function(layer) {
						//console.log(layer);
						if (layer instanceof L.Marker){
							//console.log("is_marker");
						} else if (
							(layer instanceof L.Polygon && all_options['polygon'] == true || all_options['polygon'] == 'tooltip' || all_options['markergrouptooltip']) ||
							(layer instanceof L.Circle && all_options['circle'] == true || all_options['circle'] == 'tooltip' || all_options['markergrouptooltip']) ||
							(layer instanceof L.Polyline && all_options['line'] == true || all_options['line'] == 'tooltip' || all_options['markergrouptooltip'])
						) {
							//console.log("is_Polygon or circle or polyline");

							layer.on("mousemove", function (e) {
								//console.log("mousemove");
								let popup_open = false;
								if ( e.sourceTarget.getPopup() ) {
									if ( e.sourceTarget.getPopup().isOpen()) {
										popup_open = true;
										if ( e.sourceTarget.getTooltip() ) {
											layer.unbindTooltip();
										}
									}
								}
								if (popup_open == false) {
									//console.log("popup_open == false");
									if ( e.sourceTarget.getPopup() ) {
										if ( !e.sourceTarget.getPopup().isOpen()) {
											map.closePopup();
										}
										var content = e.sourceTarget.getPopup().getContent();
										e.sourceTarget.bindTooltip(content);
										e.sourceTarget.openTooltip(e.latlng);
									}
								}
							});

							layer.on("click", function (e) {
								//console.log("click");
								e.sourceTarget.unbindTooltip();
							});
						} else {
							//console.log("other");
							//console.log(layer);
						}
					});
				}
			}
		});

	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}
