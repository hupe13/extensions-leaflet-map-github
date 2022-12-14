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
		window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
		window.WPLeafletMapPlugin.push(function () {
			var map = window.WPLeafletMapPlugin.getCurrentMap();
			var map_id = map._leaflet_id;
			var feat    = '.json_encode($featuregroups['feat']).';
			var groups  = '.json_encode($featuregroups['groups']).';
			var visible = '.json_encode($featuregroups['visible']).';
			console.log(feat,groups,visible);
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
			        //console.log(a.options);
			        //console.log(a.getIcon());
			        //console.log(a.getIcon().options);

			        var this_options = a.getIcon().options;
			        var is_feat = false;
			        for (const key in this_options) {
			          if (this_options.hasOwnProperty(key)) {
			            if (key == feat) {
			              is_feat = true;
			              //console.log("option "+`${feat}: ${this_options[key]}`);
			              var is_key = false;
			              for (group in groups) {
			                //console.log(group,`${this_options[key]}`.match(group));
			                if (`${this_options[key]}`.match(group)) {
			                  a.addTo(featGroups[group]);
			                  map.removeLayer(a);
			                  is_key = true;
			                }
			              }
			              if (is_key == false) {
			                if ("others" in groups) {
			                  a.addTo(featGroups["others"]);
			                  map.removeLayer(a);
			                } else {
			                  console.log("marker feat not matched.");
			                  console.log(a.getIcon().options);
			                }
			              }
			            }
			          } // hasOwnProperty(key)
			        } // key in this_options
			        if (is_feat == false) {
			          if ("unknown" in groups) {
			            a.addTo(featGroups["unknown"]);
			            map.removeLayer(a);
			          } else {
			            console.log(feat+" is not available.");
			            console.log(a.options);
			          }
			        }
			      } // _map._leaflet_id
			    } // has markers[i]._map
			  } // loop markers
			} // markers.length

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
			          //console.log(layer.feature.properties);
								//console.log(typeof layer.setIcon);
			          //console.log(layer.getIcon().options);

			          if (feat.match("properties")) {
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
			          } // end feat.match("properties")
			          else {
									if ( typeof layer.setIcon !== "undefined") {
			            var this_options = layer.getIcon().options;
			            var is_feat = false;
			            for (const key in this_options) {
			              if (this_options.hasOwnProperty(key)) {
			                if (key == feat) {
			                  is_feat = true;
			                  //console.log("option "+`${feat}: ${this_options[key]}`);
			                  var is_key = false;
			                  for (group in groups) {
			                    //console.log(group,`${this_options[key]}`.match(group));
			                    if (`${this_options[key]}`.match(group)) {
			                      a.addTo(featGroups[group]);
			                      map.removeLayer(a);
			                      is_key = true;
			                    }
			                  }
			                  if (is_key == false) {
			                    if ("others" in groups) {
			                      a.addTo(featGroups["others"]);
			                      map.removeLayer(a);
			                    } else {
			                      console.log("geojson feat not matched.");
			                      console.log(layer.getIcon().options);
			                    }
			                  }
			                }
			              } // hasOwnProperty(key)
			            } // key in this_options
								}
			            if (is_feat == false) {
			              if ("unknown" in groups) {
			                a.addTo(featGroups["unknown"]);
			                map.removeLayer(a);
			              } else {
			                console.log(feat+" is not available.");
			                console.log(a.options);
			              }
			            }
			          }
			        }); // eachLayer
			      }); // geojson ready
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
				//console.log("visible "+visible[key]);
				if (visible[key] == "1") {
					featGroups[key].addTo(map);
				}
			}
		});
		</script>';
		$text = \JShrink\Minifier::minify($text);
		return "\n".$text."\n";
}

function leafext_clustergroup_function( $atts ){
	if (is_singular() || is_archive()) {
		//var_dump($atts); wp_die();
		leafext_enqueue_markercluster ();
		leafext_enqueue_clustergroup ();
		$featuregroups = shortcode_atts(
			array(
				'feat' => false,
				'strings' => false,
				'groups' => false,
				'visible' => false
			), $atts
		);
		//var_dump($featuregroups); wp_die();

		$cl_strings= array_map('trim', explode( ',', $featuregroups['strings'] ));
		$cl_groups = array_map('trim', explode( ',', $featuregroups['groups'] ));
		if ($featuregroups['visible'] === false) {
			$featuregroups['visible'] = array_fill(0, count($cl_strings), '1');
			$cl_on = array_fill(0, count($cl_strings), '1');
		} else {
			$cl_on = array_map('trim', explode( ',', $featuregroups['visible'] ));
			if (count($cl_on) == 1) {
				$cl_on = array_fill(0, count($cl_strings), '0');
			}
		}

		if ( count($cl_strings) != count($cl_groups) && count($cl_strings) != count($cl_on) ) {
			$text = "[markerClusterGroup ";
			foreach ($atts as $key=>$item){
				$text = $text. "$key=$item ";
			}
			$text = $text. "]";
			$text = $text." - strings and groups (and visible) do not match. ";
			return $text;
		}

		$featuregroups = array(
			'feat' => sanitize_text_field($featuregroups['feat']),
			'groups'  => array_combine($cl_strings, $cl_groups),
			'visible' => array_combine($cl_strings, $cl_on),
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
