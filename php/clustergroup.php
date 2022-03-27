<?php
/**
 * Functions for markerClusterGroup shortcode
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Shortcode: [markerClusterGroup]
function leafext_clustergroup_script($featuregroups,$params){
	$text = '
	<script>

		var feat  = '.json_encode($featuregroups['feat']).';
		var groups= '.json_encode($featuregroups['groups']).';
		//console.log(feat,groups);

		window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
		window.WPLeafletMapPlugin.push(function () {
			var map = window.WPLeafletMapPlugin.getCurrentMap();
			var map_id = map._leaflet_id;
			//
			var alle = new L.markerClusterGroup({';
				$text=$text.leafext_java_params ($params);
				$text = $text.'
			});
			let featGroups = [];
			for (key in groups) {
				featGroups[key] = new L.featureGroup.subGroup(alle);
			}
			//featGroups["others"] = new L.featureGroup.subGroup(alle);
			//featGroups["unknown"] = new L.featureGroup.subGroup(alle);

			var control = new L.control.layers(null, null, { collapsed: false });

			//
			if ( WPLeafletMapPlugin.markers.length > 0 ) {
				//console.log("markers "+WPLeafletMapPlugin.markers.length);
				for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
					if ( WPLeafletMapPlugin.markers[i]._map !== null ) {
						if (map_id == WPLeafletMapPlugin.markers[i]._map._leaflet_id) {
							let a = WPLeafletMapPlugin.markers[i];
							//console.log(a);
							//console.log("popup");
							//console.log(a.getPopup());
							//console.log("icon");
							//console.log(a.getIcon());
							//console.log(a.getIcon().options.iconUrl);
							//console.log("title");
							//console.log(a.options.title);
							switch (feat) {
								case "iconUrl":
								var is_key = false;
								for (key in groups) {
									if (a.getIcon().options.iconUrl.match(key)) {
										a.addTo(featGroups[key]);
										map.removeLayer(a);
										is_key = true;
									}
								}
								if (is_key == false) {
									if ("others" in groups) {
										a.addTo(featGroups["others"]);
										map.removeLayer(a);
									} else {
										console.log("iconUrl not matched.");
										console.log(a.getIcon().options);
									}
								}
								break;
								case "title":
								if ( a.options.title ) {
									var is_key = false;
									for (key in groups) {
										if (a.options.title.match(key)) {
											a.addTo(featGroups[key]);
											map.removeLayer(a);
											is_key = true;
										}
									}
									if (is_key == false) {
										if ("others" in groups) {
											a.addTo(featGroups["others"]);
											map.removeLayer(a);
										} else {
											console.log("title is not in groups.");
											console.log(a.options.title);
										}
									}
								} else {
									if ("unknown" in groups) {
										a.addTo(featGroups["unknown"]);
										map.removeLayer(a);
									} else {
										console.log("Options title is not available.");
										console.log(a.options);
									}
								}
								break;
								default:
								console.log(feat+" for leaflet-marker is not valid.");
							}
						}
					}
				}
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
								//console.log(layer);
								//console.log("layer.feature.properties");
								//console.log(layer.feature.properties);
								//console.log(layer.feature.properties.niveau); //test
								//console.log("popup");
								//console.log(layer.getPopup());
								//console.log("icon");
								//console.log(layer.getIcon());
								//console.log(layer.getIcon().options.iconUrl);
								//console.log("title"); //makes no sense
								//console.log(layer.options.title);

								if (feat == "iconUrl") {
									var is_key = false;
									for (key in groups) {
										//console.log(key);
										if (layer.getIcon().options.iconUrl.match(key)) {
											//console.log("matched");
											layer.addTo(featGroups[key]);
											map.removeLayer(layer);
											is_key = true;
										}
									}
									if (is_key == false) {
										if ("others" in groups) {
											layer.addTo(featGroups["others"]);
											map.removeLayer(layer);
										} else {
											console.log("iconUrl not matched.");
											console.log(layer.getIcon().options);
										}
									}
								} else if (feat.match("properties")) {
									//console.log(layer.feature.properties);
									//console.log(feat.substr(11));
									//console.log(layer.feature.properties[feat.substr(11)]);
									if (typeof layer.feature.properties[feat.substr(11)] != "undefined") {
										let prop = layer.feature.properties[feat.substr(11)];
										if (prop in groups) {
											map.removeLayer(layer);
											layer.addTo(featGroups[prop]);
										} else {
											if ("others" in groups) {
												layer.addTo(featGroups["others"]);
												map.removeLayer(layer);
											} else {
												console.log(prop+" not in groups");
											}
										}
									} else {
										if ("unknown" in groups) {
											map.removeLayer(layer);
											layer.addTo(featGroups["unknown"]);
										} else {
											console.log("Feature "+feat+" for this leaflet-geojson marker is undefined.");
											console.log(layer.feature.properties);
										}
									}
								} else {
										console.log(feat+" for leaflet-geojson markers is not valid.");
										console.log(layer.feature.properties);
								}

								if (map.hasLayer(featGroups["others"]) && "others" in groups) {
									//console.log("others count is "+featGroups["others"].getLayers().length);
									control.removeLayer(featGroups["others"]);
									if (featGroups["others"].getLayers().length > 0) {
										control.addOverlay(featGroups["others"], groups["others"]);
									}
								}
								if (map.hasLayer(featGroups["unknown"]) && "unknown" in groups) {
									//console.log("unknown count is "+featGroups["unknown"].getLayers().length);
									control.removeLayer(featGroups["unknown"]);
									if (featGroups["unknown"].getLayers().length > 0) {
										control.addOverlay(featGroups["unknown"], groups["unknown"]);
									}
								}

							});
						});
					}
				}
			}

			//
			for (key in groups) {
				control.addOverlay(featGroups[key], groups[key]);
			}
			control.addTo(map);
			alle.addTo(map);
			//
			for (key in groups) {
				featGroups[key].addTo(map);
			}

			if (map.hasLayer(featGroups["others"]) && "others" in groups) {
				//console.log("others count is "+featGroups["others"].getLayers().length);
				if (featGroups["others"].getLayers().length == 0) {
					control.removeLayer(featGroups["others"]);
				}
			}
			if (map.hasLayer(featGroups["unknown"]) && "unknown" in groups) {
				//console.log("others count is "+featGroups["others"].getLayers().length);
				if (featGroups["unknown"].getLayers().length == 0) {
					control.removeLayer(featGroups["unknown"]);
			 	}
			}
		});
		</script>';
		//$text = \JShrink\Minifier::minify($text);
		return "\n".$text."\n";
}

function leafext_clustergroup_function( $atts ){
	if (is_singular()|| is_archive() ) {
		//var_dump($atts); wp_die();
		leafext_enqueue_markercluster ();
		leafext_enqueue_clustergroup ();
		$featuregroups = shortcode_atts( array('feat' => false, 'strings' => false, 'groups' => false), $atts);
		//var_dump($featuregroups); wp_die();

		$cl_strings= array_map('trim', explode( ',', $featuregroups['strings'] ));
		$cl_groups = array_map('trim', explode( ',', $featuregroups['groups'] ));
		if ( count( $cl_strings ) != count( $cl_groups ) ) {
			$text = "[markerClusterGroup ";
			foreach ($atts as $key=>$item){
				$text = $text. "$key=$item ";
			}
			$text = $text. "]";
			$text = $text." - strings and groups do not match. ";
			return $text;
		}
		$featuregroups = array(
			'feat' => sanitize_text_field($featuregroups['feat']),
			'groups' => array_combine($cl_strings, $cl_groups),
		);

		$options = leafext_cluster_atts ($atts);
		return leafext_clustergroup_script($featuregroups,$options);
	} else {
		$text = "[markerClusterGroup ";
		foreach ($atts as $key=>$item){
			$text = $text. "$key=$item ";
		}
		$text = $text. "]";
		return $text;
	}
}
add_shortcode('markerClusterGroup', 'leafext_clustergroup_function' );
?>
