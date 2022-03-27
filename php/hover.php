<?php
/**
 * Functions for hover shortcode
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [hover]
function leafext_geojsonhover_script($url){
	$text = '<script>';
	ob_start();
  ?>/*<script>*/

	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var map_id = map._leaflet_id;
		var maps=[];
		maps[map_id] = map;

		if ( WPLeafletMapPlugin.geojsons.length > 0 ) {
			var geojsons = window.WPLeafletMapPlugin.geojsons;
			var geocount = geojsons.length;

			for (var j = 0, len = geocount; j < len; j++) {
				var geojson = geojsons[j];
				//console.log(geojson);
				if (map_id == geojsons[j]._map._leaflet_id) {
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

					//mouseout
					geojson.layer.on("mouseout", function (e) {
						let i = 0;
						e.target.eachLayer(function(){ i += 1; });
						//console.log("mouseout has", i, "layers.");
						if (i > 1) {
							//console.log("resetStyle");
							geojson.resetStyle();
						} else {
							//resetStyle is only working with a geoJSON Group.
							e.target.eachLayer(function(layer) {
								//console.log(layer);
								if (typeof layer.setStyle != "undefined") {
									//console.log("setstyle");
									//console.log(layer);
									//console.log(layer.options.fillOpacity);
									//console.log(layer.options.weight);
									if (typeof layer.options.fillOpacity == "undefined") {
										origfillOpacity = 0.2; //leaflet default
									} else {
										origfillOpacity = layer.options.fillOpacity;
									}
									if (typeof layer.options.weight == "undefined") {
										origweight = 3; //leaflet default
									} else {
										origweight = layer.options.weight;
									}
									layer.setStyle({
										"fillOpacity" : origfillOpacity,
										"weight" : origweight,
									});
								}
							});
							geojson.resetStyle();
						}
					});
					//mouseout end

					let excludeurl = new RegExp(".*" + "<?php echo $url; ?>" + ".*");
					//console.log("excludeurl", excludeurl,geojson._url);

					if ( "<?php echo $url; ?>" == ""  || ( "<?php echo $url; ?>" != "" && !geojson._url.match (excludeurl)) ) {
						//console.log("layer event mouseover not matches");

						//mouseover
						geojson.layer.on("mouseover", function (e) {
							let i = 0;
							e.target.eachLayer(function(){ i += 1; });
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
								if (typeof e.sourceTarget.setStyle != "undefined") {
									//console.log("style4");
									//console.log(e.layer.options.fillOpacity);
									//console.log(e.layer.options.weight);
									e.sourceTarget.setStyle({
										"fillOpacity" : e.sourceTarget.options.fillOpacity+0.20,
										"weight" : e.sourceTarget.options.weight+2
									});
									e.sourceTarget.bringToFront();
									// } else {
									//console.log("nostyle");
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

								e.target.eachLayer(function(layer) {
									//console.log("style3");
									//console.log(layer);
									if (typeof layer.setStyle != "undefined") {
										//console.log(layer.options.fillOpacity);
										//console.log(layer.options.weight);
										if (typeof layer.options.fillOpacity == "undefined") {
											highfillOpacity = 0.4; //leaflet default + 0.2
										} else {
											highfillOpacity = layer.options.fillOpacity + 0.2;
										}
										if (typeof layer.options.weight == "undefined") {
											highweight = 5; //leaflet default +2
										} else {
											highweight = layer.options.weight + 2;
										}
										layer.setStyle({
											"fillOpacity" : highfillOpacity,
											"weight" : highweight,
										});
										//layer.setStyle(leafext_highlightstyle);
										layer.bringToFront();
									}
								});

							} //end else i
						});
						//mouseover end

						//mousemove
						geojson.layer.on("mousemove", function (e) {
							let i = 0;
							e.target.eachLayer(function(){ i += 1; });
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
								if (typeof e.sourceTarget.getPopup() != "undefined") {
									if ( !e.sourceTarget.getPopup().isOpen()) {
										map.closePopup();
										var content = e.sourceTarget.getPopup().getContent();
										e.sourceTarget.bindTooltip(content);
										e.sourceTarget.openTooltip(e.latlng);
									}
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

					} else {
						//excludeurl
						//console.log("url exclude");
						//console.log(geojson);
						geojson.layer.on('mouseover', function () {
							this.bringToBack();
						});

						geojson.layer.on('mouseover', function (e) {
							e.target.eachLayer(function(layer) {
								//console.log(layer);
								if (typeof layer.getPopup() != "undefined") {
									//console.log("popup defined");
									if (!layer.getPopup().isOpen()) {
										//console.log("need tooltip");
										var content = layer.getPopup().getContent();
										layer.bindTooltip(content);
									}
								}
							});
						});

						geojson.layer.on("mousemove", function (e) {
							e.target.eachLayer(function(layer) {
								if (typeof layer.getPopup() != "undefined") {
									if ( !layer.getPopup().isOpen() ) {
										//map.closePopup();
										layer.openTooltip(e.latlng);
									}
								}
							});
						});

					} //else end excludeurl

				}//geojson foreach
			}
		}
		//geojson end

		var markers = window.WPLeafletMapPlugin.markers;
		if (markers.length > 0) {
			for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
				var a = WPLeafletMapPlugin.markers[i];
				//console.log(a);
				a.on("mouseover", function (e) {
					//console.log("marker mouseover");
					//console.log(e.sourceTarget.options.title);
					if (typeof e.sourceTarget.getPopup() != "undefined") {
						if ( ! e.sourceTarget.getPopup().isOpen()) {
							map.closePopup();
							if ( e.sourceTarget.options.title != "") {
								var content = e.sourceTarget.options.title;
							} else {
								var content = e.sourceTarget.getPopup().getContent();
							}
							e.sourceTarget.bindTooltip(content);
							e.sourceTarget.openTooltip(e.latlng);
							// } else {
							//
						}
					}
				});
				a.on("click", function (e) {
					//console.log("click");
					e.sourceTarget.unbindTooltip();
				});
			}
		}

		var markergroups = window.WPLeafletMapPlugin.markergroups;
		Object.entries(markergroups).forEach(([key, value]) => {
			if ( markergroups[key]._map !== null ) {
				if (map_id == markergroups[key]._map._leaflet_id) {
					//console.log("markergroups loop");
					markergroups[key].eachLayer(function(layer) {
						//console.log(layer);
						if (layer instanceof L.Marker){
							//console.log("is_marker");
						} else if (layer instanceof L.Polygon || layer instanceof L.Circle || layer instanceof L.Polyline ) {
							//console.log("is_Polygon or circle or polyline");
							if (typeof layer.getTooltip() == "undefined") {
								if (typeof layer.getPopup() != "undefined") {
									var content = layer.getPopup().getContent();
									layer.bindTooltip(content);
								}
							}
							layer.on("mouseover", function (e) {
								if (typeof e.sourceTarget.setStyle != "undefined") {
									//console.log("mouseover");
									//console.log(e.sourceTarget.options.fillOpacity);
									//console.log(e.sourceTarget.options.weight);
									e.sourceTarget.setStyle({
										"fillOpacity" : e.sourceTarget.options.fillOpacity+0.20,
										"weight" : e.sourceTarget.options.weight+2
									});
									e.sourceTarget.bringToFront();
								}
							});
							layer.on("mouseout", function (e) {
								if (typeof e.sourceTarget.setStyle != "undefined") {
									//console.log("mouseout");
									//console.log(e.sourceTarget.options.fillOpacity);
									//console.log(e.sourceTarget.options.weight);
									e.sourceTarget.setStyle({
										"fillOpacity" : e.sourceTarget.options.fillOpacity-0.20,
										"weight" : e.sourceTarget.options.weight-2
									});
								}
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
	$text = $text . $javascript . '</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_canvas_script($tolerance) {
	return '<script>
	  window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	  window.WPLeafletMapPlugin.push(function () {
	    var map = window.WPLeafletMapPlugin.getCurrentMap();
	    map.options.renderer=L.canvas({ tolerance: '.$tolerance.' });
	  });
	</script>';
}

function leafext_geojsonhover_function($atts){
	$settings = shortcode_atts(	array('exclude' => false,'tolerance' => 0), get_option( 'leafext_canvas' ));
	$options  = shortcode_atts( $settings, $atts);
	//var_dump($atts,get_option( 'leafext_canvas'),$settings,$options); wp_die();
	$text = "";
	if ($options['tolerance'] != 0) {
		$text = $text.leafext_canvas_script( $options['tolerance'] );
	}
	$text=$text.leafext_geojsonhover_script($options['exclude']);
	return $text;
}
add_shortcode('hover', 'leafext_geojsonhover_function' );
