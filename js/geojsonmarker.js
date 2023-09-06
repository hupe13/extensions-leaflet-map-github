/**
* Javascript functions for Extensions for Leaflet Map
* extensions-leaflet-map geojsonmarker
*/

function leafext_geojsonmarker_js(property,iconprops,icondefault,auto,groups,visible,clmarkers,extramarkericon) {
  var map = window.WPLeafletMapPlugin.getCurrentMap();
  var map_id = map._leaflet_id;

  if ( auto ) {
    groups = [];
  }

  if (groups !== null) {
    if (typeof durchlauf == "undefined" ) {
      maps=[];
      durchlauf=[];
      featGroups=[];
      autogroups=[];
      iconproperties=[];
      icondefaults=[];
      control=[];
      displayed=[];
      cluster=[];
    }
    if (typeof durchlauf[map_id] == "undefined" ) {
      maps[map_id] = map;
      durchlauf[map_id] = 0;
      featGroups[map_id] = [];
      autogroups[map_id]=groups;
      iconproperties[map_id]=iconprops;
      icondefaults[map_id]=icondefault;
      displayed[map_id] = [];
      cluster[map_id] = clmarkers;
      control[map_id] =  L.control.layers(null, null, { collapsed: false });
      for (key in autogroups[map_id]) {
        featGroups[map_id][autogroups[map_id][key]] = new L.featureGroup.subGroup(cluster[map_id]);
        //console.log("visible",key,visible[key]);
        displayed[map_id][autogroups[map_id][key]] = visible[key];
        //console.log(displayed[map_id]);
      }
    } else {
      durchlauf[map_id] = durchlauf[map_id] +1;
      for (key in autogroups[map_id]) {
        if ( autogroups[map_id][key] in featGroups[map_id] ) {
          //console.log(autogroups[map_id][key]+" schon in featGroups[map_id]");
        } else {
          featGroups[map_id][autogroups[map_id][key]] = new L.featureGroup.subGroup(cluster[map_id]);
          displayed[map_id][autogroups[map_id][key]] = visible[key];
        }
      }
    }
    console.log("Round "+durchlauf[map_id]+" on map "+map_id+"; Property:",property,"; "+"Groups",autogroups[map_id],"visible",visible);
  }

  var geojsons = window.WPLeafletMapPlugin.geojsons;
  if (geojsons.length > 0) {
    //console.log("geojsons "+geojsons.length);
    var geocount = geojsons.length;
    for (var j = 0, len = geocount; j < len; j++) {
      var geojson = geojsons[j];
      //console.log(geojson);
      if (map_id == geojson._map._leaflet_id) {
        // console.log("geojson");

        geojson.on("ready", function () {
          //console.log("ready");
          var a = this.layer;
          //console.log(a);

          if ( iconproperties[map_id].length == 0 || auto) {
            var properties = [];
            var icontype = ''; //  iconUrl or circleMarker or ExtraMarker
            a.eachLayer(function(layer) {
              if (layer.feature.geometry.type == "Point" ) {
                if ( layer.feature.properties[property] ) {
                  if ( ! properties.includes(layer.feature.properties[property]) ) {
                    properties.push(layer.feature.properties[property]);
                  }
                }
                if (layer.options.radius && layer.options.color) {
                  icontype = "circle";
                } else if ( layer.options.iconUrl ) {
                  icontype = "iconUrl";
                } else {
                  icontype = "extra";
                }
              }
            });

            if (! properties.includes("others")) properties.push("others");
            // console.log("gesammelte Werke");
            // console.log(properties);

            if ( icontype != "iconUrl") {

              if ( properties.length > 0 ) {
                if (icontype == "circle") {
                  // default html colors see Wikipedia
                  var colors = [
                    'red','maroon','yellow','green',
                    'blue','fuchsia','aqua','purple',
                    'olive','teal','navy','lime',
                    'white','silver','gray','black',
                  ];
                } else if ( icontype == "extra") {
                  //valid markerColor for ExtraMarkers
                  colors = [
                    'red','orange','yellow','blue-dark',
                    'cyan','pink','green-dark','orange-dark',
                    'green','purple','green-light','violet',
                    'black','white',
                  ];
                } else {
                  //
                }

                //icondefault rausschmeissen
                colors = colors.filter(function (col) {
                  return col !== icondefaults[map_id];
                });

                if (colors.length < properties.length) {
                  // for (var c = 0, len = properties.length - colors.length; c < len; c++) {
                  // 	colors.push ( '#'+Math.floor(Math.random()*16777215).toString(16) );
                  // }
                  console.log(properties);
                  console.log("to many properties, truncate ...");
                  properties.length = colors.length;
                  if (! properties.includes("others")) {
                    properties.length = colors.length - 1;
                    properties.push("others");
                  }
                  console.log(properties);
                } else {
                  colors.length = properties.length;
                }

                // bringe iconprops und colors zusammen
                iconproperties[map_id] = colors.reduce(function(result, field, index) {
                  result[properties[index]] = field;
                  return result;
                }, {})

                // console.log("created");
                // console.log(colors);
                // console.log(iconproperties[map_id]);
              }
            } else {
              console.log("Colors collection is not available for Marker with iconUrl. Please specify iconprops.")
            }
          }

          if (auto) {
            for (var c = 0, len = properties.length; c < len; c++) {
              autogroups[map_id][properties[c]] = properties[c];
            }
            for (key in autogroups[map_id]) {
              featGroups[map_id][autogroups[map_id][key]] = new L.featureGroup.subGroup(cluster[map_id]);
            }
          }

          a.eachLayer(function(layer) {
            if (layer.feature.geometry.type == "Point" ) {
              if (layer.options.radius && layer.options.color) {
                //circleMarker
                layer.options.color = icondefaults[map_id];
                thisproperty = 'others';
                if ( layer.feature.properties[property] ) {
                  thisproperty=layer.feature.properties[property];
                  if (iconproperties[map_id][thisproperty]) {
                    layer.options.color = iconproperties[map_id][thisproperty];
                  } else {
                    thisproperty = 'others';
                    if (iconproperties[map_id][thisproperty]) {
                      if (iconproperties[map_id][thisproperty] != icondefaults[map_id]) {
                        layer.options.color = iconproperties[map_id][thisproperty];
                      }
                    }
                  }
                }
                //
              } else if (layer instanceof L.Marker) {
                if (layer.options.iconUrl) {
                  //console.log("has iconUrl");
                  let markeroptions = layer.getIcon().options;
                  var markericon = L.Icon.extend({
                    options: markeroptions
                  });
                  //console.log(layer.getIcon().options);
                  markericon.options = Object.entries(layer.getIcon().options);
                  thisproperty = 'others';
                  if ( layer.feature.properties[property] ) {
                    thisproperty=layer.feature.properties[property];
                    // console.log(thisproperty,icondefaults[map_id],iconproperties[map_id][thisproperty]);
                    if (iconproperties[map_id][thisproperty]) {
                      thismarker = new markericon({
                        iconUrl: layer.options.iconUrl.replace(icondefaults[map_id], iconproperties[map_id][thisproperty]),
                      });
                      layer.setIcon(thismarker);
                    } else {
                      thisproperty = 'others';
                      if (iconproperties[map_id][thisproperty]) {
                        if (iconproperties[map_id][thisproperty] != icondefaults[map_id]) {
                          thismarker = new markericon({
                            iconUrl: layer.options.iconUrl.replace(icondefaults[map_id], iconproperties[map_id][thisproperty]),
                          });
                          layer.setIcon(thismarker);
                        }
                      }
                    }
                  }

                } else {
                  //console.log("Extramarker");
                  console.log(extramarkericon);
                  thisproperty = 'others';
                  if ( layer.feature.properties[property] ) {
                    thisproperty=layer.feature.properties[property];
                    if (iconproperties[map_id][thisproperty]) {
                      var markericon = leafext_geojsonmarker_extramarker_js(iconproperties[map_id][thisproperty]);
                    } else {
                      thisproperty = 'others';
                      if (iconproperties[map_id][thisproperty] != icondefaults[map_id]) {
                        var markericon = leafext_geojsonmarker_extramarker_js(iconproperties[map_id][thisproperty]);
                      }
                    }
                  }
                  if (thisproperty == 'others'){
                    var markericon = leafext_geojsonmarker_extramarker_js(icondefaults[map_id]);
                  }
                  //console.log(markericon);
                  layer.setIcon(markericon);
                }
              }

              if (autogroups[map_id] !== null && !auto) {
                if (thisproperty in autogroups[map_id]) {
                  //console.log("Found geojson on map "+map_id+" "+thisproperty+" "+autogroups[map_id][thisproperty]);
                  maps[map_id].removeLayer(layer);
                  layer.addTo(featGroups[map_id][autogroups[map_id][thisproperty]]);
                } else {
                  maps[map_id].removeLayer(layer);
                  layer.addTo(featGroups[map_id][autogroups[map_id]["others"]]);
                }
              } else if (auto) {
                maps[map_id].removeLayer(layer);
                layer.addTo(featGroups[map_id][thisproperty]);
              } else {
                map.removeLayer(layer);
                clmarkers.addLayer(layer);
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
          if (autogroups[map_id] !== null) {
            for (group in featGroups[map_id]) {
              control[map_id].removeLayer(featGroups[map_id][group]);
              control[map_id].addOverlay(featGroups[map_id][group], group);
            }
          } else {
            //clmarkers.addTo( map );
          }
          if (auto) {
            for (key in featGroups[map_id]) {
              featGroups[map_id][key].addTo(map);
            }
          }
          //console.log(Object.entries(featGroups[map_id]['others']).length);
          if (Object.entries(featGroups[map_id]['others']) && Object.entries(featGroups[map_id]['others']).length == 0) {
            control[map_id].removeLayer(featGroups[map_id]['others']);
          }
        }); // geojson ready
      }
    }
  }

  if (autogroups[map_id] !== null) {
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
}
