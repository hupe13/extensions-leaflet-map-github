<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [hover]
function leafext_geojsonhover_script($url){
	$text = '<script>
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		if ( WPLeafletMapPlugin.geojsons.length > 0 ) {
			var geojsons = window.WPLeafletMapPlugin.geojsons;
			var geocount = geojsons.length;
			for (var j = 0, len = geocount; j < len; j++) {
				var geojson = geojsons[j];

				//mouseover
				geojson.layer.on("mouseover", function (e) {
					//let i = 0;
					//e.target.eachLayer(function(){ i += 1; });
					//console.log("mouseover has", i, "layers.");
					var marker_popup_open = false;
					e.target._map.eachLayer(function(layer){
						if (typeof layer.options.icon != "undefined") {
							//console.log("icon defined");
							if (typeof layer.getPopup() != "undefined") {
								if (layer.getPopup().isOpen()) {
									marker_popup_open = true;
								}
							}
						}
					});
					if (i > 1) {
						if (typeof e.sourceTarget.options.style != "undefined") {
							';
							if ( $url ) $text=$text.'
								//console.log(geojson._url);
								if ( !geojson._url.match (/'.$url.'/)) {
									//console.log("url not matches");
									';
									$text=$text.'
									console.log("style1 defined");
									e.sourceTarget.setStyle({
										fillOpacity: 0.4,
										weight: 5
									});
									e.sourceTarget.bringToFront();
									';
									if ( $url ) $text=$text.'
								}
							';
							$text=$text.'
						// } else {
							// console.log("nostyle");
						}
					} else {
						//console.log("style2");
						e.target.eachLayer(function(layer) {
							if ( marker_popup_open ) {
								//console.log("mouseover handle marker popup");
								layer.unbindTooltip();
							} else {
								if (typeof layer.getPopup() != "undefined") {
									if (!layer.getPopup().isOpen() && layer.getTooltip() === null) {
										//console.log("need tooltip");
										var content = layer.getPopup().getContent();
										layer.bindTooltip(content);
									}
								}
							}
						});
						';
						if ( $url ) $text=$text.'
						//console.log(geojson._url);
						if ( !geojson._url.match (/'.$url.'/)) {
							//console.log("url not matches");
							';
							$text=$text.'
							e.target.eachLayer(function(layer) {
								//console.log("style3");
								//console.log(layer);
								if (typeof layer.options.style != "undefined") {
									layer.setStyle({
										fillOpacity: 0.4,
										weight: 5
									});
									layer.bringToFront();
								}
							});
							';
							if ( $url ) $text=$text.'
						}
						';
						$text=$text.'
					}
				});
				//mouseover end

				//mouseout
				geojson.layer.on("mouseout", function (e) {
					//let i = 0;
					//e.target.eachLayer(function(){ i += 1; });
					//console.log("mouseout has", i, "layers.");
					if (i > 1) {
						geojson.resetStyle();
					} else {
						//resetStyle is only working with a geoJSON Group.
						e.target.eachLayer(function(layer) {
							//console.log(layer);
							if (typeof layer.options.style != "undefined") {
								layer.setStyle({
									fillOpacity: 0.2,
									weight: 3
								});
							}
						});
					}
				});
				//mouseout end

				//mouse click
				geojson.layer.on("click", function (e) {
					//console.log("click");
					e.target.eachLayer(function(layer) {
						if (typeof layer.getPopup() != "undefined") {
							if (layer.getPopup().isOpen())
								layer.unbindTooltip();
						}
					});
				});
				//mouse click end

				//mousemove
				geojson.layer.on("mousemove", function (e) {
				 	//let i = 0;
					//e.target.eachLayer(function(){ i += 1; });
					//console.log("mousemove has", i, "layers.");
					marker_popup_open = false;
					e.target._map.eachLayer(function(layer){
						if (typeof layer.options.icon != "undefined") {
							//console.log("icon defined");
							if (typeof layer.getPopup() != "undefined" ) {
								if (layer.getPopup().isOpen()) {
									//console.log("mousemove popup is open");
									marker_popup_open = true;
								}
							}
						}
					});
					if (i > 1) {
						//marker as geojson
						if ( !e.sourceTarget.getPopup().isOpen()) {
							map.closePopup();
							var content = e.sourceTarget.getPopup().getContent();
							e.sourceTarget.bindTooltip(content);
							e.sourceTarget.openTooltip(e.latlng);
						}
					} else {
						e.target.eachLayer(function(layer) {
							if (typeof layer.getPopup() != "undefined") {
								if ( !layer.getPopup().isOpen() && !marker_popup_open) {
									map.closePopup();
									if ( typeof layer.getTooltip() == "undefined") {
										var content = layer.getPopup().getContent();
										//console.log(content);
										layer.bindTooltip(content);
									}
									layer.openTooltip(e.latlng);
								}
							}
						});
					}
				});
				//mousemove end
			}
		}
		//geojson end

		var markers = window.WPLeafletMapPlugin.markers;
		if (markers.length > 0) {
			//console.log("hover markers "+markers.length);
			for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
				var a = WPLeafletMapPlugin.markers[i];
				a.on("mouseover", function (e) {
					//console.log("marker mouseover");
					if ( ! e.sourceTarget.getPopup().isOpen()) {
						map.closePopup();
						var content = e.sourceTarget.getPopup().getContent();
						e.sourceTarget.bindTooltip(content);
						e.sourceTarget.openTooltip(e.latlng);
					// } else {
					//
					}
				});
				a.on("click", function (e) {
					//console.log("click");
					e.sourceTarget.unbindTooltip();
				});
			}
		}
	});
	</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_geojsonhover_function($atts){
	$exclude = shortcode_atts( array('exclude' => false), $atts);
	$exclude['exclude']= str_replace ( '/' , '\/' , $exclude['exclude'] );
	$text=leafext_geojsonhover_script($exclude['exclude']);
	return $text;
}
add_shortcode('hover', 'leafext_geojsonhover_function' );
?>
