<?php
/**
* Functions for hover shortcode
* Part geojson style
* extensions-leaflet-map
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [hover]
function leafext_geojsonstyle_script($options){
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/

	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		let all_options = <?php echo json_encode($options);?>;
		console.log("leafext_geojsonstyle_script");
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
					// console.log(extension);
					// console.log(all_options[extension]);

					if (!(all_options[extension] == true || all_options[extension] == 'style' || all_options['geojsonstyle'])) {
						exclude = 99;
					}

					if ( all_options['exclude'] !== "" ) {
						//console.log("set exclude "+all_options['exclude']);
						exclude = geojson._url.indexOf(all_options['exclude']);
					}
					//console.log(exclude);
					if (exclude == -1) {
						//mouseover
						geojson.layer.on("mouseover", function (e) {
							let i = 0;
							e.target.eachLayer(function(){ i += 1; });
							//console.log("mouseover has", i, "layers.");

							if (i > 1) {
								// z.B leaflet-gpx mit Track und Marker
								if ( e.sourceTarget.setStyle ) {
									//console.log(e);
									if ( ! e.sourceTarget.options.fillOpacity ) {
										var highfillOpacity = 0.4; //leaflet default + 0.2
									} else {
										e.sourceTarget.options.origfillOpacity = e.sourceTarget.options.fillOpacity;
										var highfillOpacity = e.sourceTarget.options.fillOpacity + 0.2;
									}
									if ( ! e.sourceTarget.options.weight ) {
										var highweight = 5; //leaflet default +2
									} else {
										e.sourceTarget.options.origweight = e.sourceTarget.options.weight;
										var highweight = e.sourceTarget.options.weight + 2;
									}
									e.sourceTarget.setStyle({
										"fillOpacity" : highfillOpacity,
										"weight" : highweight,
									});
									e.sourceTarget.bringToFront();
								}
							} else {
								e.target.eachLayer(function(layer) {
									//console.log(layer);
									if ( layer.setStyle ) {
										//console.log(layer.options.fillOpacity);
										//console.log(layer.options.weight);
										if (! layer.options.fillOpacity ) {
											var highfillOpacity = 0.4; //leaflet default + 0.2
										} else {
											layer.options.origfillOpacity = layer.options.fillOpacity;
											var highfillOpacity = layer.options.fillOpacity + 0.2;
										}
										if ( ! layer.options.weight ) {
											var highweight = 5; //leaflet default +2
										} else {
											layer.options.origweight = layer.options.weight;
											var highweight = layer.options.weight + 2;
										}
										layer.setStyle({
											"fillOpacity" : highfillOpacity,
											"weight" : highweight,
										});
										layer.bringToFront();
									}
								});

							} //end else i
						});
						//mouseover end

						//mouseout
						geojson.layer.on("mouseout", function (e) {
							let i = 0;
							e.target.eachLayer(function(){ i += 1; });
							//console.log("mouseout has", i, "layers.");
							if (i > 1) {
								//console.log("resetStyle");
								e.target.eachLayer(function(layer){
									if ( layer.setStyle ) {
										//console.log(layer);
										if ( layer.options.origweight ) {
											var origweight = layer.options.origweight;
										} else {
											var origweight = 3; //leaflet default
										}
										if ( layer.options.origfillOpacity ) {
											var origfillOpacity = layer.options.origfillOpacity;
										} else {
											var origfillOpacity = 0.2; //leaflet default
										}
										layer.setStyle({
											"fillOpacity" : origfillOpacity,
											"weight" : origweight,
										});
									}
								});
								geojson.resetStyle();
							} else {
								//resetStyle is only working with a geoJSON Group.
								e.target.eachLayer(function(layer) {
									//console.log(layer);
									if ( layer.setStyle ) {
										//console.log(layer);
										if ( layer.options.origweight ) {
											var origweight = layer.options.origweight;
										} else {
											var origweight = 3; //leaflet default
										}
										if ( layer.options.origfillOpacity ) {
											var origfillOpacity = layer.options.origfillOpacity;
										} else {
											var origfillOpacity = 0.2; //leaflet default
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
					} else { //exclude
						geojson.layer.on('mouseover', function () {
							this.bringToFront();
						});
					}
				}//map_id
			}//geojson foreach
		}//geojson end
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';
	$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}
