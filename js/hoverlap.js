/**
* Javascript functions for shortcode hoverlap
* extensions-leaflet-map
*/

function leafext_is_point_in_layer(map,point,layer) {
  //console.log(turf);
  turfpoint = turf.helpers.point([point.lng,point.lat]);
  let debug = false;
  //if (debug) console.log(layer);
  //if (debug) console.log(layer.type);
  if (layer instanceof L.Circle) {
    if (debug) console.log("leafext_is_point_in_layer is L.Circle");
    let center = layer.getLatLng();
    let radius = layer.getRadius();
    if (center.distanceTo(point) < radius) {
      if (debug) console.log("ist drin");
      return true;
    }
  } else
  if (layer instanceof L.Polyline && !(layer instanceof L.Polygon)) {
    // Polygons are a Polyline also, but a Polyline is not a Polygon.
    if (debug) console.log("leafext_is_point_in_layer is L.Polyline");
    if ( L.GeometryUtil.closest(map,layer,point).distance < 5 ) {
      if (debug) console.log("ist drin");
      return true;
    }
  } else
  if (layer instanceof L.Polygon ) {
    if (debug) console.log("leafext_is_point_in_layer is L.Polygon");
    if (turf.booleanPointInPolygon(turfpoint,layer.toGeoJSON())) {
      if (debug) console.log("ist drin");
      return true;
    }
  } else
  if (layer instanceof L.Path) {
    if (debug) console.log("leafext_is_point_in_layer is L.Path");
    let center = layer.getLatLng();
    let radius = layer.getRadius();
    var elements = [];
    elements.push(new L.Marker(center));
    let result = L.GeometryUtil.layersWithin(map, elements, point, radius);
    if (result.length > 0) {
      if (debug) console.log("ist drin");
      return true;
    }
  } else
  if (layer instanceof L.Marker) {
    if (debug) console.log("leafext_is_point_in_layer is L.Marker");
    //
  } else
  if ( layer.type == 'MultiPolygon' || layer.type == 'Polygon') {
    if (debug) console.log("leafext_is_point_in_layer is MultiPolygon or Polygon");
    if (turf.booleanPointInPolygon(turfpoint,layer)) {
      if (debug) console.log("ist drin");
      return true;
    }
  } else
  if ( layer.type == 'LineString') {
    if (debug) console.log("leafext_is_point_in_layer is LineString");
    let polylatlngs = [];
    for (var i=0;i<layer.coordinates.length;i++) {
      polylatlngs.push([layer.coordinates[i][1],layer.coordinates[i][0]]);
    }
    var polyline = L.polyline(polylatlngs);
    if (L.GeometryUtil.closest(map,polyline,point).distance < 5) {
      if (debug) console.log("ist drin");
      return true;
    }
  } else
  if ( layer.type == 'Point') {
    if (debug) console.log("leafext_is_point_in_layer is Point");
    // console.log(layer);
  } else
  if ( layer.feature && layer.feature.geometry && layer.feature.geometry.type == 'GeometryCollection') {
    if (debug) console.log("leafext_is_point_in_layer is GeometryCollection");
    turf.meta.flattenEach(layer.toGeoJSON(), function (currentFeature, featureIndex, multiFeatureIndex) {
      if (debug) console.log("leafext_is_point_in_layer is in GeometryCollection", currentFeature.geometry.type);
      if (leafext_is_point_in_layer(map,point,currentFeature.geometry)) {
        if (debug) console.log("ist drin");
        return true;
      }
    });
  } else
  if ( layer.feature && layer.feature.geometry && layer.feature.geometry.type == 'MultiPoint') {
    if (debug) console.log("leafext_is_point_in_layer is MultiPoint");
    //
  } else {
    if (debug) console.log("leafext_is_point_in_layer is other layer");
    if (debug) console.log(layer);
  }
  if (debug) console.log("ist nicht drin");
  return false;
}

function leafext_marker_click(e,map,map_id,layer,all_options) {
  // console.log("Marker "+e.type);
  // console.log(layer);
  let event = e.type;
  var mouselayers = [];
  // console.log(overlaplayers[map_id]);
  // console.log(mouselayers);

  if ( event == "click" ) {
    overlaplayers[map_id].eachLayer(function(layer) {
      leafext_make_styleback(layer);
    });
  }

  overlaplayers[map_id].eachLayer(function(layer) {
    // console.log(layer);
    if (leafext_is_point_in_layer(map,e.latlng,layer)) {
      if ( event == "click") {
        // console.log("make overstyle");
        // console.log(layer);
        leafext_make_overstyle(layer);
      }
      if ( layer.getPopup().getContent() ) {
        mouselayers.push(layer.getPopup().getContent());
      }
    }
  });
  // Marker Popup Inhalt hinzufuegen
  // console.log(layer.getPopup().getContent());
  // console.log(layer.options.popupContent);
  if ( layer.options.popupContent ) {
    mouselayers.push(layer.options.popupContent);
  }
  // console.log(mouselayers.join(', '));
  leafext_close_tooltips(map);

  let ele_popup_open = false;
  if (e.sourceTarget.getPopup()) {
    if (e.sourceTarget.getPopup().isOpen()) {
      ele_popup_open = true;
    }
  }
  if (event == "mouseover" && leafext_map_popups(map) == false && ele_popup_open == false) {
    // console.log("marker hover");
    e.sourceTarget.unbindTooltip();
    e.sourceTarget.bindTooltip(mouselayers.join('<br>'),{className: all_options['class']});
    e.sourceTarget.openTooltip(e.latlng);
    //console.log(e.sourceTarget);
  }
  if ( event == "click" ) {
    // console.log("click");
    // console.log(e.sourceTarget);
    //e.sourceTarget.unbindTooltip();
    e.sourceTarget.bindTooltip("", {visibility: 'hidden', opacity: 0}).closeTooltip();
    //e.sourceTarget.unbindPopup();
    // console.log(mouselayers.join(', '));
    e.sourceTarget.bindPopup(mouselayers.join('<br>'));
    e.sourceTarget.openPopup();
  }
  // console.log("Ende Marker "+e.type);
}

function leafext_hoverlap_js(all_options) {
  var map = window.WPLeafletMapPlugin.getCurrentMap();
  var map_id = map._leaflet_id;
  //console.log(map_id);
  var maps=[];
  maps[map_id] = map;

  if (typeof overlaplayers == "undefined" ) {
    overlaplayers=[];
  }
  if (typeof overlaplayers[map_id] == "undefined" ) {
    overlaplayers[map_id] = new L.LayerGroup();
    overlaplayers[map_id].addTo(map);
  }

  var markergroups = window.WPLeafletMapPlugin.markergroups;
  Object.entries(markergroups).forEach(([key, value]) => {
    if ( markergroups[key]._map !== null ) {
      if (map_id == markergroups[key]._map._leaflet_id) {
        //console.log("markergroups loop");
        markergroups[key].eachLayer(function(layer) {
          if ( layer.getPopup() ) {
            if (layer.getPopup().getContent()) {
              layer.options.popupContent = layer.getPopup().getContent();
            }
          }
          if (layer instanceof L.Marker) {
            //console.log("is_marker");
            layer.on("mouseover click", function (e) {
              leafext_marker_click(e,map,map_id,layer,all_options);
            });
          } else
          if (
            layer instanceof L.Polygon ||
            layer instanceof L.Circle  ||
            layer instanceof L.Polyline
          ) {
            overlaplayers[map_id].addLayer(layer);
            layer.off('click');
          } else {
            //console.log("other");
            //console.log(layer);
          }
        });
      }
    }
  }); //Object.entries(markergroups).forEach(([key, value])

  var geojsons = window.WPLeafletMapPlugin.geojsons;
  var geocount = geojsons.length;

  for (var j = 0, len = geocount; j < len; j++) {
    var geojson = geojsons[j];
    //console.log(geojson);

    if (map_id == geojsons[j]._map._leaflet_id) {
      let exclude = -1;
      if ( all_options['exclude'] !== "" ) {
        //console.log("set exclude "+all_options['exclude']);
        exclude = geojson._url.indexOf(all_options['exclude']);
      }
      // console.log(exclude);
      if (exclude == -1) {
        geojson.on("ready", function (e) {
          // console.log("geojson ready");
          // console.log(e);
          e.target.eachLayer(function(layer) {
            console.log(layer.feature.geometry.type);
            // console.log(layer.getPopup().getContent());
            if ( layer.feature.geometry.type == 'GeometryCollection') {
              turf.meta.flattenEach(layer.toGeoJSON(), function (currentFeature, featureIndex, multiFeatureIndex) {
                // console.log(currentFeature.geometry.type);
                if ( layer.getPopup() ) {
                  //console.log(layer.getPopup().getContent());
                  layer.options.popupContent = layer.getPopup().getContent();
                }
                if ( currentFeature.geometry.type == 'Point' || currentFeature.geometry.type == 'MultiPoint') {
                  // overlaplayers[map_id].addLayer(layer);
                } else {
                  overlaplayers[map_id].addLayer(layer);
                  layer.off('click');
                }
              });
              // console.log("GeometryCollection ENDE");
              layer.eachLayer(function(geometry) {
                // test if Marker (Geojson Point or MultiPoint) in GeometryCollection
                // console.log("L.Path ",geometry instanceof L.Path);
                // console.log("L.Polygon ",geometry instanceof L.Polygon); // Polygon is Path also true
                // console.log("L.Point ",geometry instanceof L.Point);
                // console.log("L.Marker ",geometry instanceof L.Marker); // is a Icon
                // console.log(geometry);

                if (geometry instanceof L.Path == true && geometry instanceof L.Polygon == false) {
                  // It is a CircleMarker
                  // console.log("is a CircleMarker");
                  // console.log(geometry);
                  if (layer.options.popupContent) {
                    geometry.bindPopup(layer.options.popupContent);
                    geometry.options.popupContent = layer.options.popupContent;
                    overlaplayers[map_id].addLayer(geometry);
                  }
                  geometry.off('click');
                } else
                if (geometry instanceof L.Point || geometry instanceof L.Marker) {
                  // console.log("is a icon");
                  if (layer.options.popupContent) {
                    geometry.bindPopup(layer.options.popupContent);
                    geometry.options.popupContent = layer.options.popupContent;
                    // console.log("is a icon with popup");
                    // Funktioniert nicht wirklich
                    geometry.off('click');
                    layer.off('click');
                    geometry.on("mouseover click", function (e) {
                      leafext_marker_click(e,map,map_id,geometry,all_options);
                    });
                  }
                }
              });
            } else
            if ( layer.feature.geometry.type == 'Point') {
              // console.log("Point");
              if (layer instanceof L.Marker) {
                if ( layer.getPopup() ) {
                  layer.options.popupContent = layer.getPopup().getContent();
                  layer.on("mouseover click", function (e) {
                    leafext_marker_click(e,map,map_id,layer,all_options);
                  });
                }
              } else {
                overlaplayers[map_id].addLayer(layer);
                layer.off('click');
              }
            } else
            if (layer.feature.geometry.type == 'MultiPoint') {
              // console.log("Multipoint");
              // console.log(layer);
              layer.eachLayer(function(point) {
                if (point instanceof L.Marker == true) {
                  layer.off('click');
                  //console.log("is icon Marker");
                  //console.log(point);
                  if ( layer.getPopup() ) {
                    // point.unbindTooltip();
                    // point.bindTooltip(layer.getPopup().getContent());
                    point.bindPopup(layer.getPopup().getContent());
                    point.options.popupContent = layer.getPopup().getContent();
                    point.off('click');
                    point.on("mouseover click", function (e) {
                      leafext_marker_click(e,map,map_id,point,all_options);
                    });
                  }
                } else {
                  //console.log("is CircleMarker");
                  if ( layer.getPopup() ) {
                    point.bindPopup(layer.getPopup().getContent());
                    overlaplayers[map_id].addLayer(point);
                    point.off('click');
                  }
                }
              });
            } else {
              if ( layer.getPopup() ) {
                layer.options.popupContent = layer.getPopup().getContent();
                layer.off('click');
                overlaplayers[map_id].addLayer(layer);
              }
            }
            //console.log(overlaplayers[map_id]);
          });
        });

      } else { //exclude
        geojson.layer.on('mouseout', function () {
          this.bringToBack();
        });
      }
    }//map_id
  }//geojson end

  map.on("mousemove click", function(e) {
    //console.log(e);
    if (!leafext_map_popups(map)) {
      var latlng = e.latlng;
      var containerPoint = e.containerPoint;
      overlaplayers[map_id].eachLayer(function(layer) {
        leafext_make_styleback(layer);
      });

      overlaplayers[map_id].eachLayer(function(layer) {
        //console.log(layer);
        if (layer.feature && layer.feature.geometry && layer.feature.geometry.type ) {
          //console.log(layer.feature.geometry.type);
          if ( layer.feature.geometry.type == 'GeometryCollection' ) {
            turf.meta.flattenEach(layer.toGeoJSON(), function (currentFeature, featureIndex, multiFeatureIndex) {
              if (leafext_is_point_in_layer(map,latlng,currentFeature.geometry)) {
                leafext_make_overstyle(layer);
              }
            });
          } else {
            if (leafext_is_point_in_layer(map,latlng,layer)) {
              leafext_make_overstyle(layer);
            }
          }
        } else {
          overlaplayers[map_id].eachLayer(function(layer) {
            if (leafext_is_point_in_layer(map,latlng,layer)) {
              leafext_make_overstyle(layer);
            }
          });
        }
      });
    // } else {
    //   console.log("popup open no style change");
    }
  }); // map mousemove end

  map.on("mousemove click", function(e) {
    // console.log("map");
    // console.log(e.type); //shows event type, i.e. "click", "dragstart" etc.
    let event = e.type;
    var latlng = e.latlng;
    var mouselayers = [];

    overlaplayers[map_id].eachLayer(function(layer) {

      if (layer.feature && layer.feature.geometry && layer.feature.geometry.type ) {
        // console.log(layer.feature.geometry.type);
        if ( layer.feature.geometry.type == 'GeometryCollection' ) {
          turf.meta.flattenEach(layer.toGeoJSON(), function (currentFeature, featureIndex, multiFeatureIndex) {
            if (leafext_is_point_in_layer(map,latlng,currentFeature.geometry)) {
              if ( layer.getPopup().getContent() ) {
                mouselayers.push(layer.getPopup().getContent());
              }
            }
          });
        } else {
          if (leafext_is_point_in_layer(map,latlng,layer)) {
            if ( layer.getPopup().getContent() ) {
              mouselayers.push(layer.getPopup().getContent());
            }
          }
        }
      } else {
        if (leafext_is_point_in_layer(map,latlng,layer)) {
          if ( layer.getPopup().getContent() ) {
            mouselayers.push(layer.getPopup().getContent());
          }
        }
      }
    });

    // console.log(mouselayers.join(', '));

    //console.log("leafext_markertooltip "+leafext_markertooltip(map));
    // if (!leafext_map_popups(map) && event == "mousemove" && leafext_markertooltip(map)) {
    //   console.log("mousemove and markertooltip");
    //   console.log(mouselayers);
    // }
    if (!leafext_map_popups(map) && event == "mousemove" && !leafext_markertooltip(map)) {
      //if (event == "mousemove") {
      leafext_close_tooltips(map);
      if (mouselayers.length > 0) {
        // console.log("tooltip map mouseover");
        var tooltip = L.tooltip({className: all_options['class']})
        .setLatLng(latlng)
        .setContent(mouselayers.join('<br>'))
        .openOn(map);
      }
    }
    if (event == "click") {
      // console.log("click open popup");
      leafext_close_tooltips(map);
      if (mouselayers.length > 0) {
        var popup = L.popup()
        .setLatLng(latlng)
        .setContent(mouselayers.join('<br>'))
        .openOn(map);
      }
    }
  }); //map "mousemove click"

  map.on("popupclose", function(e) {
    //console.log(leafext_markerpopup(map));
    overlaplayers[map_id].eachLayer(function(layer) {
      leafext_make_styleback(layer);
    });
  });

}

function leafext_close_tooltips(map) {
  map.eachLayer(function(layer) {
    if (layer.options.pane === "tooltipPane") {
      layer.removeFrom(map);
      //console.log("leafext_close_tooltips");
    }
  });
}

function leafext_markertooltip(map) {
  var markertooltip = false;
  map.eachLayer(function(layer) {
    if (layer.options.pane === "tooltipPane") {
      markertooltip = layer._source instanceof L.Marker;
    }
  });
  return markertooltip;
}
