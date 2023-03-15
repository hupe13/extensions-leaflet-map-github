<?php
/**
* Functions for hover shortcode
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [hover]
function leafext_geojsontooltip_script($options){
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/

	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		let all_options = <?php echo json_encode($options);?>;
		console.log("leafext_geojsontooltip_script");
		console.log(all_options);

		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var map_id = map._leaflet_id;
		//console.log(map_id);
		var maps=[];
		maps[map_id] = map;

		if ( WPLeafletMapPlugin.geojsons.length > 0 ) {
			var geojsons = window.WPLeafletMapPlugin.geojsons;
			var geocount = geojsons.length;

			for (var j = 0, len = geocount; j < len; j++) {
				var geojson = geojsons[j];
				//console.log(geojson);
				if (map_id == geojsons[j]._map._leaflet_id) {
					let exclude = -1;
					// console.log(geojson._url);
					extension = geojson._url.split(".").pop();
				  //console.log(extension);
					//console.log(all_options[extension]);
					if (!(all_options[extension] == true || all_options[extension] == 'tooltip' || all_options['geojsontooltip'])) {
						exclude = 99;
					}
					//console.log(exclude);
					if (exclude == -1) {
						geojson.layer.on("click", function (e) {
							//console.log("click");
							e.target.eachLayer(function(layer) {
								if ( layer.getPopup() ) {
									if (layer.getPopup().isOpen())
									layer.unbindTooltip();
								}
							});
						});
						//mouse click end

						//mousemove
						geojson.layer.on("mousemove", function (e) {
							let i = 0;
							e.target.eachLayer(function(){ i += 1; });
							//console.log("mousemove has", i, "layers.");

							e.target.eachLayer(function(layer){
								// console.log(layer.feature.geometry.type);
								// console.log(typeof layer.getPopup());
								// console.log(layer.getPopup().isOpen());

								let popup_open = false;
								if ( layer.getPopup() ) {
									if ( layer.getPopup().isOpen()) {
										popup_open = true;
										if ( layer.getTooltip() ) {
											if (layer.feature.geometry.type == "MultiPoint") {
												// console.log("Multipoint");
												layer.closeTooltip();
											} else {
												layer.unbindTooltip();
											}
										}
									}
								}

								if (i == 1) {
									if (popup_open == false) {
										//console.log("popup_open == false");
										//console.log(layer);
										if ( layer.getPopup() ) {
											if ( !layer.getPopup().isOpen()) {
												map.closePopup();
											}
											var content = layer.getPopup().getContent();
											layer.bindTooltip(content);
											layer.openTooltip(e.latlng);
										}
									}
								} else {
									//kml, gpx
									if ( e.sourceTarget.getPopup() ) {
										if ( !e.sourceTarget.getPopup().isOpen()) {
											map.closePopup();
											var content = e.sourceTarget.getPopup().getContent();
											e.sourceTarget.bindTooltip(content);
											e.sourceTarget.openTooltip(e.latlng);
										}
									}
								}
							});
						});
						//mousemove end
					} // exclude
				}//geojson foreach
			}
		}
		//geojson end
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}
