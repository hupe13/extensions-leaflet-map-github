<?php
/**
* Functions for hover shortcode geojsonstyle
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
					extension = extension.toLowerCase();
					if (extension == 'json') extension = 'geojson';
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
							// console.log("mouseover has", i, "layers.");
							if (i > 1) {
								// z.B leaflet-gpx mit Track und Marker
								leafext_make_overstyle(e.sourceTarget);
							} else {
								e.target.eachLayer(function(layer) {
									//console.log(layer);
									leafext_make_overstyle(layer);
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
								e.target.eachLayer(function(layer){
									leafext_make_styleback(layer);
								});
							} else {
								//resetStyle is only working with a geoJSON Group.
								e.target.eachLayer(function(layer) {
									leafext_make_styleback(layer);
								});
								geojson.resetStyle();
							}
						});
						//mouseout end
					} else { //exclude
						geojson.layer.on('mouseout', function () {
							this.bringToBack();
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
