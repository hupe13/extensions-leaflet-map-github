<?php
/**
* Functions for geojsonmarker
* extensions-leaflet-map
*/

// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [geojsonmarker]
function leafext_geojsonmarker_script($property,$defaultproperty,$extramarkericon,$clusteroptions,$featuregroupoptions){
	$text = '<script><!--';
	ob_start();
	?>/*<script>*/
	window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
	window.WPLeafletMapPlugin.push(function () {
		let property = <?php echo json_encode($property);?>;
		let defaultproperty  = <?php echo json_encode($defaultproperty);?>;
		//
		let groups  = <?php echo json_encode($featuregroupoptions['groups']);?>;
		let visible = <?php echo json_encode($featuregroupoptions['visible']);?>;
		//
		let clmarkers = L.markerClusterGroup({
			<?php echo leafext_java_params ($clusteroptions);?>
		});

		var map = window.WPLeafletMapPlugin.getCurrentMap();
		var map_id = map._leaflet_id;

		if (groups !== null) {
			if (typeof durchlauf == "undefined" ) {
				maps=[];
				durchlauf=[];
				featGroups=[];
				control=[];
				displayed=[];
				cluster=[];
			}
			if (typeof durchlauf[map_id] == "undefined" ) {
				maps[map_id] = map;
				durchlauf[map_id] = 0;
				featGroups[map_id] = [];
				displayed[map_id] = [];
				cluster[map_id] = clmarkers;
				control[map_id] =  L.control.layers(null, null, { collapsed: false });
				for (key in groups) {
					featGroups[map_id][groups[key]] = new L.featureGroup.subGroup(cluster[map_id]);
					//console.log("visible",key,visible[key]);
					displayed[map_id][groups[key]] = visible[key];
					//console.log(displayed[map_id]);
				}
			} else {
				durchlauf[map_id] = durchlauf[map_id] +1;
				for (key in groups) {
					if ( groups[key] in featGroups[map_id] ) {
						//console.log(groups[key]+" schon in featGroups[map_id]");
					} else {
						featGroups[map_id][groups[key]] = new L.featureGroup.subGroup(cluster[map_id]);
						displayed[map_id][groups[key]] = visible[key];
					}
				}
			}
			//console.log("Round "+durchlauf[map_id]+" on map "+map_id+"; Property:",property,"; "+"Groups",groups,"visible",visible);
		}

		var geojsons = window.WPLeafletMapPlugin.geojsons;
		if (geojsons.length > 0) {
			//console.log("geojsons "+geojsons.length);
			var geocount = geojsons.length;
			for (var j = 0, len = geocount; j < len; j++) {
				var geojson = geojsons[j];
				//console.log(geojson);
				if (map_id == geojson._map._leaflet_id) {
					geojson.on("ready", function () {
						var a = this.layer;
						//console.log(a);
						a.eachLayer(function(layer) {
							if (layer.feature.geometry.type == "Point" ) {
								if (layer.options.radius && layer.options.color) {
									// console.log("it is a circleMarker");
									// console.log(layer.options);
									// attribution: null
									// bubblingMouseEvents: true
									// color: "red"
									// dashArray: null
									// dashOffset: null
									// fill: true
									// fillColor: null
									// fillOpacity: 0.2
									// fillRule: "evenodd"
									// interactive: true
									// lineCap: "round"
									// lineJoin: "round"
									// opacity: 1
									// pane: "overlayPane"
									// radius: 10
									// stroke: true
									// weight: 3
									if ( layer.feature.properties[property] ) {
										layer.options.color = layer.feature.properties[property];
									} else {
										layer.options.color = defaultproperty;
									}
									//
									if (groups !== null) {
										if (layer.options.color in groups) {
											console.log("Found geojson on map "+map_id+" option exact "+layer.options.color+" "+groups[layer.options.color]);
											maps[map_id].removeLayer(layer);
											layer.addTo(featGroups[map_id][groups[layer.options.color]]);
										} else {
											if ("others" in groups) {
												maps[map_id].removeLayer(layer);
												layer.addTo(featGroups[map_id][groups["others"]]);
											}
										}
									} else {
										map.removeLayer(layer);
										clmarkers.addLayer(layer);
									}
								} else if (layer instanceof L.Marker) {
									if (layer.options.iconUrl) {
										console.log("has iconUrl");
										let markeroptions = layer.getIcon().options;
										var markericon = L.Icon.extend({
											options: markeroptions
										});
										//console.log(layer.getIcon().options);
										markericon.options = Object.entries(layer.getIcon().options);
										if ( layer.feature.properties[property] ) {
											//console.log(layer.feature.properties[property]);
											thismarker = new markericon({
												iconUrl: layer.options.iconUrl.replace(defaultproperty, layer.feature.properties[property]),
											});
											layer.setIcon(thismarker);
										} else {
											//console.log("default");
										}
										//console.log(layer.getIcon());

									} else {
										//console.log("Extramarker");
										if ( layer.feature.properties[property] ) {
											//console.log(layer.feature.properties[property]);
											var markericon = L.ExtraMarkers.icon({<?php echo $extramarkericon;?>,markerColor:layer.feature.properties[property]});
										} else {
											var markericon = L.ExtraMarkers.icon({<?php echo $extramarkericon;?>,markerColor:defaultproperty});
										}
										//console.log(markericon);
										layer.setIcon(markericon);
									}

									if (groups !== null) {
										found = false;
										if ( layer.feature.properties[property] ) {
											let prop = layer.feature.properties[property];
											if (prop in groups) {
												//console.log("Found geojson on map "+map_id+" property exact "+prop+" for "+groups[prop]);
												maps[map_id].removeLayer(layer);
												layer.addTo(featGroups[map_id][groups[prop]]);
												found = true;
											}
										}
										if (!found) {
											if ("others" in groups) {
												maps[map_id].removeLayer(layer);
												layer.addTo(featGroups[map_id][groups["others"]]);
											}
										}
									} else {
										map.removeLayer(layer);
										clmarkers.addLayer(layer);
									}

								}

								//cluster
								var content = layer.feature.properties.name;
								//console.log(layer.getPopup());
								if ( layer.getPopup() ) {
									//
								} else if (typeof content != "undefined") {
									layer.bindTooltip(content);
									layer.bindPopup(content);
								} else {
									layer.bindTooltip("Point");
									layer.bindPopup("Point");
								}
							}
						});
						//geojson asynchron
						if (groups !== null) {
							for (group in featGroups[map_id]) {
								control[map_id].removeLayer(featGroups[map_id][group]);
								control[map_id].addOverlay(featGroups[map_id][group], group);
							}
						} else {
							//clmarkers.addTo( map );
						}
					}); // geojson ready
				}
			}
		}
		if (groups !== null) {
			control[map_id].addTo(map);
			cluster[map_id].addTo(map);
			for (key in featGroups[map_id]) {
				if (displayed[map_id][key] == "1") {
					featGroups[map_id][key].addTo(map);
				}
			}
		} else {
			clmarkers.addTo( map );
		}
	});
	<?php
	$javascript = ob_get_clean();
	$text = $text . $javascript . '//-->'."\n".'</script>';

	//$text = \JShrink\Minifier::minify($text);
	return "\n".$text."\n";
}

function leafext_geojsonmarker_function($atts,$content,$shortcode) {
	$text = leafext_should_interpret_shortcode($shortcode,$atts);
	if ( $text != "" ) {
		return $text;
	} else {
		$propertyoptions = shortcode_atts(array('property' => "color", 'default' => "blue"), $atts);
		//
		leafext_enqueue_extramarker ();
		$extramarker = leafext_case(array_keys(leafext_extramarker_defaults()),leafext_clear_params($atts));
		$extramarkeroptions = shortcode_atts(leafext_extramarker_defaults(), $extramarker);
		unset($extramarkeroptions['markerColor']);
		$extramarkericon = leafext_extramarkers_params ($extramarkeroptions).'tooltipAnchor:[12,-24]';
		//
		leafext_enqueue_markercluster ();
		$clusteroptions = leafext_cluster_atts ($atts);
		//
		$groupingoptions = shortcode_atts(
			array(
				//'property' => '',
				'values' => '',
				'groups' => '',
				'visible' => false,
			), leafext_clear_params($atts)
		);
		if ( substr_count($groupingoptions['values'],',') != substr_count($groupingoptions['groups'],',') ) {
			$text = "['.$shortcode.' ";
			if (is_array($atts)){
				foreach ($atts as $key=>$item){
					$text = $text. "$key=$item ";
				}
			}
			$text = $text." - values and groups do not match. ";
			$text = $text. "]";
			return $text;
		}

		if ($groupingoptions['values'] != "") {

			$cl_values= array_map('trim', explode( ',', $groupingoptions['values'] ));
			$cl_groups = array_map('trim', explode( ',', $groupingoptions['groups'] ));

			if ($groupingoptions['visible'] === false) {
				$groupingoptions['visible'] = array_fill(0, count($cl_values), '1');
				$cl_on = array_fill(0, count($cl_values), '1');
			} else {
				$cl_on = array_map('trim', explode( ',', $groupingoptions['visible'] ));
				if (count($cl_on) == 1) {
					$cl_on = array_fill(0, count($cl_values), '0');
				} else {
					if ( count($cl_values) != count($cl_on) ) {
						$text = "['.$shortcode.' ";
						foreach ($atts as $key=>$item){
							$text = $text. "$key=$item ";
						}
						$text = $text." - groups and visible do not match. ";
						$text = $text. "]";
						return $text;
					}
				}
			}

			$featuregroupoptions = array(
				//'property' => sanitize_text_field($groupingoptions['property']),
				'values' => sanitize_text_field($groupingoptions['values']),
				'groups'  => array_combine($cl_values, $cl_groups),
				'visible' => array_combine($cl_values, $cl_on),
			);
			leafext_enqueue_clustergroup ();
		} else {
			$featuregroupoptions = array();
		}
		//
		return leafext_geojsonmarker_script($propertyoptions['property'],$propertyoptions['default'],$extramarkericon,$clusteroptions,$featuregroupoptions);
	}
}
add_shortcode('geojsonmarker', 'leafext_geojsonmarker_function');
