/**
* Javascript functions for Extensions for Leaflet Map
* extensions-leaflet-map
*/

function leafext_featuregroup_js(att_property,att_option,groups,visible,substr,alle) {
  var map = window.WPLeafletMapPlugin.getCurrentMap();
  var map_id = map._leaflet_id;

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
    cluster[map_id] = alle;
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
  console.log("Round "+durchlauf[map_id]+" on map "+map_id+"; Option:",att_option,"; Property:",att_property,"; substr: "+substr+";"+"Groups",groups,"visible",visible);

  //
  if ( WPLeafletMapPlugin.markers.length > 0 ) {
    //console.log("markers "+WPLeafletMapPlugin.markers.length);
    for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
      if ( WPLeafletMapPlugin.markers[i]._map !== null ) {
        if (map_id == WPLeafletMapPlugin.markers[i]._map._leaflet_id) {
          let a = WPLeafletMapPlugin.markers[i];
          // console.log("Marker");
          // console.log(a.options);
          // console.log("Icon");
          // console.log(a.getIcon().options);
          let found = false;

          let this_options = a.getIcon().options;
          if (this_options.hasOwnProperty(att_option)) {
            if ( a.options[att_option] ) {
              if (this_options[att_option] != a.options[att_option] && typeof a.options[att_option] == "string") {
                this_options[att_option]=a.options[att_option];
                console.log("changed "+att_option+' '+this_options[att_option]);
              }
            }
          } else {
            console.log("has not "+att_option);
            // console.log (a.options[att_option]);
            if (typeof a.options[att_option] == "string") {
              //console.log (a.options);
              if ( a.options[att_option] != "") {
                this_options[att_option]=a.options[att_option];
                console.log("has now "+att_option+' '+this_options[att_option]);
              }
            }
          }

          for (const key in this_options) {
            if (this_options.hasOwnProperty(key)) {
              if (key == att_option) {
                found = true;
                var is_key = false;
                var this_option = `${this_options[key]}`;
                //console.log("Suche nach "+this_option);
                if (this_option in groups) {
                  console.log("Found Marker on map "+map_id+" option exact "+key+" "+this_option+" for "+groups[this_option]);
                  maps[map_id].removeLayer(a);
                  a.addTo(featGroups[map_id][groups[this_option]]);
                  is_key = true;
                } else {
                  if (substr == true) {
                    for (group in groups) {
                      //console.log(group,this_option.match(group));
                      if (this_option.match(group)) {
                        console.log("Found Marker on map "+map_id+" option substring "+key+" "+group+" for "+groups[group]);
                        maps[map_id].removeLayer(a);
                        a.addTo(featGroups[map_id][groups[group]]);
                        is_key = true;
                        break;
                      }
                    }
                  }
                }
                if (is_key == false) {
                  if ("others" in groups) {
                    maps[map_id].removeLayer(a);
                    a.addTo(featGroups[map_id][groups["others"]]);
                  } else {
                    console.log("marker option "+att_option+" not matched.");
                    console.log(a.getIcon().options);
                  }
                }
              }
            } // hasOwnProperty(key)
          } // key in this_options
          if (found == false) {
            if ("unknown" in groups) {
              maps[map_id].removeLayer(a);
              a.addTo(featGroups[map_id][groups["unknown"]]);
            } else {
              console.log("marker option "+att_option+" is not available.");
              console.log(a.options);
            }
          }
        } // _map._leaflet_id
      } // has markers[i]._map
    } // loop markers
  } // markers.length

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
            // console.log("markergroup");

            let found = false;
            this_options = layer.options;
            for (const key in this_options) {
              if (this_options.hasOwnProperty(key)) {
                if (key == att_option) {
                  found = true;
                  var is_key = false;
                  var this_option = `${this_options[key]}`;
                  //console.log("Suche nach "+this_option);
                  if (this_option in groups) {
                    console.log("Found Polygon/Circle/Line on map "+map_id+" option exact "+key+" "+this_option+" for "+groups[this_option]);
                    maps[map_id].removeLayer(layer);
                    layer.addTo(featGroups[map_id][groups[this_option]]);
                    is_key = true;
                  } else {
                    if (substr == true) {
                      for (group in groups) {
                        //console.log(group,this_option.match(group));
                        if (this_option.match(group)) {
                          console.log("Found Polygon/Circle/Line on map "+map_id+" option substring "+key+" "+group+" for "+groups[group]);
                          maps[map_id].removeLayer(layer);
                          layer.addTo(featGroups[map_id][groups[group]]);
                          is_key = true;
                          break;
                        }
                      }
                    }
                  }
                  if (is_key == false) {
                    if ("others" in groups) {
                      maps[map_id].removeLayer(layer);
                      layer.addTo(featGroups[map_id][groups["others"]]);
                    } else {
                      console.log("Polygon/Circle/Line option "+att_option+" not matched.");
                      console.log(layer.options);
                    }
                  }
                }
              } // hasOwnProperty(key)
            } // key in this_options

            if (found == false) {
              if ("unknown" in groups) {
                maps[map_id].removeLayer(layer);
                layer.addTo(featGroups[map_id][groups["unknown"]]);
              } else {
                console.log("Polygon/Circle/Line option "+att_option+" is not available.");
                console.log(layer.options);
              }
            }

          } else {
            //console.log("other");
            //console.log(layer);
          }
        });
      }
    }
  });

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
            if (att_option != '') {
              // console.log("layer.options");
              // console.log(layer.options);
              // console.log(att_option);
              // console.log(layer.options[att_option]);
              let found = false;
              if ( layer.options[att_option] ) {
                if (layer.options[att_option] in groups) {
                  console.log("Found geojson on map "+map_id+" option exact "+layer.options[att_option]+" "+groups[layer.options[att_option]]);
                  maps[map_id].removeLayer(layer);
                  layer.addTo(featGroups[map_id][groups[layer.options[att_option]]]);
                  found = true;
                } else {
                  if ( substr == true) {
                    //console.log("substr geojson option "+substr);
                    for (group in groups) {
                      //console.log("group "+group);
                      if (layer.options[att_option].match(group)) {
                        console.log("Found geojson on map "+map_id+" option substr "+layer.options[att_option]+" "+groups[group]);
                        maps[map_id].removeLayer(layer);
                        layer.addTo(featGroups[map_id][groups[group]]);
                        found = true;
                        break;
                      }
                    }
                  }
                }
                if ( found == false ) {
                  if ("others" in groups) {
                    maps[map_id].removeLayer(layer);
                    layer.addTo(featGroups[map_id][groups["others"]]);
                  } else {
                    console.log("geojson Option "+att_option+" not in groups");
                    console.log(layer.options[att_option]);
                  }
                }
              } else {
                if ("unknown" in groups) {
                  maps[map_id].removeLayer(layer);
                  layer.addTo(featGroups[map_id][groups["unknown"]]);
                } else {
                  console.log("geojson Option "+att_option+" is undefined.");
                  console.log(layer.options);
                }
              }
            } // end att_option != ''
            else if (att_property != '') {
              let found = false;
              //console.log(layer.feature.properties);
              if ( layer.feature.properties[att_property] ) {
                let prop = layer.feature.properties[att_property];
                if (prop in groups) {
                  console.log("Found geojson on map "+map_id+" property exact "+prop+" for "+groups[prop]);
                  maps[map_id].removeLayer(layer);
                  layer.addTo(featGroups[map_id][groups[prop]]);
                  found = true;
                } else {
                  if ( substr == true) {
                    // console.log("property substr "+substr);
                    for (group in groups) {
                      if (prop.match(group)) {
                        console.log("Found geojson on map "+map_id+" property substring "+prop+" "+group+" for "+groups[group]);
                        maps[map_id].removeLayer(layer);
                        layer.addTo(featGroups[map_id][groups[group]]);
                        found = true;
                        break;
                      }
                    }
                  }
                }
                if (found == false) {
                  if ("others" in groups) {
                    maps[map_id].removeLayer(layer);
                    layer.addTo(featGroups[map_id][groups["others"]]);
                  } else {
                    console.log("Property "+prop+" not in groups");
                  }
                }
              } else {
                if ("unknown" in groups) {
                  maps[map_id].removeLayer(layer);
                  layer.addTo(featGroups[map_id][groups["unknown"]]);
                } else {
                  console.log("Feature "+att_property+" for this leaflet-geojson is undefined.");
                  console.log(layer.feature.properties);
                }
              }
            } // end feat.match("properties")
          }); // eachLayer
          //geojson asynchron
          for (group in featGroups[map_id]) {
            control[map_id].removeLayer(featGroups[map_id][group]);
            control[map_id].addOverlay(featGroups[map_id][group], group);
          }
        }); // geojson ready
      }
    }
  }

  if (durchlauf[map_id] == 0 ) {
    for (group in featGroups[map_id]) {
      control[map_id].addOverlay(featGroups[map_id][group], group);
    }
  } else {
    for (group in featGroups[map_id]) {
      control[map_id].removeLayer(featGroups[map_id][group]);
      control[map_id].addOverlay(featGroups[map_id][group], group);
    }
  }

  control[map_id].addTo(map);
  cluster[map_id].addTo(map);

  for (key in featGroups[map_id]) {
    if (displayed[map_id][key] == "1") {
      featGroups[map_id][key].addTo(map);
    }
  }
}
