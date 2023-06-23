/**
* Javascript Functions for hover shortcode
* extensions-leaflet-map
*/

function leafext_get_tooltip(layer, tooltip) {
  //console.log(layer.options);

  if (typeof tooltip == "string" ) {
    if (!layer.options.tooltip) {
      if (tooltip.indexOf('{') !== -1) {

        //https://gist.github.com/forcewake/82e4e646c41bb638a3db
        var tipprops = [],          // an array to collect the strings that are found
        rxp = /{([^}]+)}/g,
        str = tooltip,
        curMatch;
        while( curMatch = rxp.exec( str ) ) {
          tipprops.push( curMatch[1] );
        }
        //console.log( tipprops );

        var thistooltip = tooltip;
        for (const tipprop of tipprops) {
          if (layer.feature.properties[tipprop]) {
            thistooltip = thistooltip.replace('{'+tipprop+'}',layer.feature.properties[tipprop]);
          }
        }
        content = thistooltip;
        layer.options.tooltip = thistooltip;
      } else {
        content = tooltip;
        layer.options.tooltip = tooltip;
      }
    } else {
      content = layer.options.tooltip;
    }
  } else {
    content = layer.getPopup().getContent();
  }
  return content;
}

function leafext_tooltip_snap (e,map) {
  var elements = [];
  e.sourceTarget._map.eachLayer(function(layer){
    if ( layer.getPopup() ) {
      if ( layer.getPopup().isOpen()) {
        //console.log("is open");
        //console.log(layer.getPopup().getLatLng());
        elements.push(new L.Marker(layer.getPopup().getLatLng()));
      }
    }
  });
  //console.log(elements);
  var result = L.GeometryUtil.closestLayerSnap(
    e.sourceTarget._map,
    elements, // alle Marker
    e.latlng, // mouse position.
    50 // distance in pixels under which snapping occurs.
  );
  //console.log(result);
  if (!result) {
    map.closePopup();
  }
}

function leafext_hover_geojsonstyle_js(all_options) {
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
}

function leafext_hover_markergroupstyle_js(all_options) {
  var map = window.WPLeafletMapPlugin.getCurrentMap();
  var map_id = map._leaflet_id;
  //console.log(map_id);
  var maps=[];
  maps[map_id] = map;

  var markergroups = window.WPLeafletMapPlugin.markergroups;
  Object.entries(markergroups).forEach(([key, value]) => {
    if ( markergroups[key]._map !== null ) {
      if (map_id == markergroups[key]._map._leaflet_id) {
        //console.log("markergroups loop");
        markergroups[key].eachLayer(function(layer) {
          //console.log(layer);
          if (layer instanceof L.Marker) {
            //console.log("is_marker");
          } else
          if (
            (layer instanceof L.Polygon && all_options['polygon'] == true || all_options['polygon'] == 'style' || all_options['markergroupstyle']) ||
            (layer instanceof L.Circle && all_options['circle'] == true || all_options['circle'] == 'style' || all_options['markergroupstyle']) ||
            (layer instanceof L.Polyline && all_options['line'] == true || all_options['line'] == 'style' || all_options['markergroupstyle'])
          ) {
            //console.log("is_Polygon or circle or polyline");
            layer.on("mouseover", function (e) {
              //console.log("mouseover");
              leafext_make_overstyle(e.sourceTarget);
            });
            layer.on("mouseout", function (e) {
              leafext_make_styleback(e.sourceTarget);
            });
          } else {
            //console.log("other");
            //console.log(layer);
          }
        });
      }
    }
  });
}

function leafext_hover_geojsontooltip_js(tooltip) {
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
        geojson.layer.on("click", function (e) {
          //console.log("click");
          e.target.eachLayer(function(layer) {
            if ( layer.getPopup() ) {
              if (layer.getPopup().isOpen()) {
                //console.log(layer.feature.geometry.type);
                if (layer.feature.geometry.type == "MultiPoint" || layer.feature.geometry.type == "Point") {
                  //console.log("Multipoint");
                  layer.unbindTooltip();
                  layer.bindTooltip("", {visibility: 'hidden', opacity: 0}).closeTooltip();
                }  else {
                  layer.unbindTooltip();
                }
              }
            }
          });
        });
        //mouse click end

        //mousemove
        geojson.layer.on("mousemove", function (e) {
          let i = 0;
          e.target.eachLayer(function(){ i += 1; });
          //console.log("mousemove has", i, "layers.");

          e.target.eachLayer(function(layer){
            // console.log(layer.feature.geometry.type);
            // console.log(typeof layer.getPopup());
            // console.log(layer.getPopup().isOpen());

            let popup_open = false;
            if ( layer.getPopup() ) {
              if ( layer.getPopup().isOpen()) {
                popup_open = true;
                if ( layer.getTooltip() ) {
                  if (layer.feature.geometry.type == "MultiPoint" || layer.feature.geometry.type == "Point") {
                    //console.log("Multipoint");
                    //layer.closeTooltip();
                    layer.unbindTooltip();
                    layer.bindTooltip("", {visibility: 'hidden', opacity: 0}).closeTooltip();
                  } else {
                    layer.unbindTooltip();
                  }
                }
              }
            }

            if (i == 1) {
              if (popup_open == false) {
                //console.log("popup_open == false");
                //console.log(layer);
                if ( layer.getPopup() ) {
                  if ( !layer.getPopup().isOpen()) {
                    leafext_tooltip_snap (e,map);
                  }
                  var content = leafext_get_tooltip(layer, tooltip);
                  layer.bindTooltip(content);
                  layer.openTooltip(e.latlng);
                }
              }
            } else {
              //kml, gpx, mehrere Elemente in geojson
              if ( e.sourceTarget.getPopup() ) {
                if ( !e.sourceTarget.getPopup().isOpen()) {
                  leafext_tooltip_snap (e,map);
                  var content = leafext_get_tooltip(e.sourceTarget, tooltip);
                  e.sourceTarget.bindTooltip(content);
                  e.sourceTarget.openTooltip(e.latlng);
                }
              }
            }
          });
        });
        //mousemove end
      }//geojson foreach
    }
  }//geojson end
}

function leafext_hover_markergrouptooltip_js(all_options) {
  var map = window.WPLeafletMapPlugin.getCurrentMap();
  var map_id = map._leaflet_id;
  //console.log(map_id);
  var maps=[];
  maps[map_id] = map;

  var markergroups = window.WPLeafletMapPlugin.markergroups;
  Object.entries(markergroups).forEach(([key, value]) => {
    if ( markergroups[key]._map !== null ) {
      if (map_id == markergroups[key]._map._leaflet_id) {
        //console.log("markergroups loop");
        markergroups[key].eachLayer(function(layer) {
          //console.log(layer);
          if (layer instanceof L.Marker){
            //console.log("is_marker");
          } else if (
            (layer instanceof L.Polygon && all_options['polygon'] == true || all_options['polygon'] == 'tooltip' || all_options['markergrouptooltip']) ||
            (layer instanceof L.Circle && all_options['circle'] == true || all_options['circle'] == 'tooltip' || all_options['markergrouptooltip']) ||
            (layer instanceof L.Polyline && all_options['line'] == true || all_options['line'] == 'tooltip' || all_options['markergrouptooltip'])
          ) {
            //console.log("is_Polygon or circle or polyline");

            layer.on("mousemove", function (e) {
              //console.log("mousemove");
              let popup_open = false;
              if ( e.sourceTarget.getPopup() ) {
                if ( e.sourceTarget.getPopup().isOpen()) {
                  popup_open = true;
                  if ( e.sourceTarget.getTooltip() ) {
                    layer.unbindTooltip();
                  }
                }
              }
              if (popup_open == false) {
                //console.log("popup_open == false");
                if ( e.sourceTarget.getPopup() ) {
                  if ( !e.sourceTarget.getPopup().isOpen()) {
                    var elements = [];
                    e.sourceTarget._map.eachLayer(function(layer){
                      if ( layer.getPopup() ) {
                        if ( layer.getPopup().isOpen()) {
                          //console.log("is open");
                          //console.log(layer.getPopup().getLatLng());
                          elements.push(new L.Marker(layer.getPopup().getLatLng()));
                        }
                      }
                    });
                    //console.log(elements);
                    var result = L.GeometryUtil.closestLayerSnap(
                      e.sourceTarget._map,
                      elements, // alle Marker
                      e.latlng, // mouse position.
                      50 // distance in pixels under which snapping occurs.
                    );
                    //console.log(result);
                    if (!result) {
                      map.closePopup();
                    }
                  }
                  var content = e.sourceTarget.getPopup().getContent();
                  e.sourceTarget.bindTooltip(content);
                  e.sourceTarget.openTooltip(e.latlng);
                }
              }
            });

            layer.on("click", function (e) {
              //console.log("click");
              e.sourceTarget.unbindTooltip();
            });
          } else {
            //console.log("other");
            //console.log(layer);
          }
        });
      }
    }
  });
}

function leafext_hover_markertitle_js() {
  var map = window.WPLeafletMapPlugin.getCurrentMap();
  var map_id = map._leaflet_id;
  //console.log(map_id);
  var maps=[];
  maps[map_id] = map;
  var markers = window.WPLeafletMapPlugin.markers;
  if (markers.length > 0) {
    for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
      var a = WPLeafletMapPlugin.markers[i];
      if (( a._map != null && a._map._leaflet_id == map_id) || a._map == null ) {
        // console.log(a.options);
        // console.log(a.options.title);
        if ( a.options.title ) {
          // console.log("has title - deleted");
          a.options.title = "";
        }
        if ( a._icon ) {
          // console.log("has _icon - title deleted");
          a._icon.title = "";
        }
        //console.log(a);
        a.unbindTooltip();
        a.bindTooltip("", {visibility: 'hidden', opacity: 0}).closeTooltip();
      }
    }
  }
}

function leafext_hover_markertooltip_js() {
  var map = window.WPLeafletMapPlugin.getCurrentMap();
  var map_id = map._leaflet_id;
  //console.log(map_id);
  var maps=[];
  maps[map_id] = map;

  var markers = window.WPLeafletMapPlugin.markers;
  if (markers.length > 0) {
    for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
      var a = WPLeafletMapPlugin.markers[i];
      if (( a._map != null && a._map._leaflet_id == map_id) || a._map == null ) {
        // console.log(a.options);
        // console.log(a.options.title);
        if ( a.options.title ) {
          // console.log("has title - deleted");
          a.options.title = "";
        }
        if ( a._icon ) {
          // console.log("has _icon - title deleted");
          a._icon.title = "";
        }
        //console.log(a);
        a.on("mouseover", function (e) {
          //console.log("marker mouseover");
          //console.log(e);
          if (typeof e.sourceTarget.getPopup() != "undefined") {
            if ( ! e.sourceTarget.getPopup().isOpen()) {
              map.closePopup();
              //console.log(e.sourceTarget.options);
              if ( typeof e.sourceTarget.getPopup().getContent() != "undefined" )
              var content = e.sourceTarget.getPopup().getContent();
              if ( typeof content != "undefined" ) {
                //console.log(e.sourceTarget);
                e.sourceTarget.unbindTooltip();
                e.sourceTarget.bindTooltip(content);
                e.sourceTarget.openTooltip(e.latlng);
              }
            } else {
              e.sourceTarget.unbindTooltip();
              e.sourceTarget.bindTooltip("", {visibility: 'hidden', opacity: 0}).closeTooltip();
            }
          }
        });
        a.on("click", function (e) {
          //console.log("click");
          if (typeof e.sourceTarget.getPopup() != "undefined") {
            if ( e.sourceTarget.getPopup().isOpen()) {
              e.sourceTarget.unbindTooltip();
              e.sourceTarget.bindTooltip("", {visibility: 'hidden', opacity: 0}).closeTooltip();
            } else {
              if ( typeof e.sourceTarget.getPopup().getContent() != "undefined" )
              var content = e.sourceTarget.getPopup().getContent();
              if ( typeof content != "undefined" ) {
                //console.log("bind tooltip");
                //console.log(e.sourceTarget);
                e.sourceTarget.bindTooltip(content);
                e.sourceTarget.openTooltip(e.latlng);
              }
            }
          }
        });
        a.on("popupclose", function (e) {
          //console.log("popup close");
          if ( typeof e.sourceTarget.getPopup().getContent() != "undefined" )
          var content = e.sourceTarget.getPopup().getContent();
          if ( typeof content != "undefined" ) {
            //console.log(e.sourceTarget);
            e.sourceTarget.bindTooltip(content);
            e.sourceTarget.openTooltip(e.latlng);
            e.sourceTarget.closeTooltip();
          }
        });
      }
    }
  }
}
