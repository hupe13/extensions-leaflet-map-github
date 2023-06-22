function leafext_zoomhome_js(maps,map_id,allfit) {
  //console.log("map_id* "+map_id);

  if(typeof maps[map_id].zoomControl !== "undefined") {
    maps[map_id].zoomControl._zoomOutButton.remove();
    maps[map_id].zoomControl._zoomInButton.remove();
  }

  var bounds = [];
  bounds[map_id] = new L.latLngBounds();

  var zoomHome = [];
  zoomHome[map_id] = L.Control.zoomHome();
  zoomHome[map_id].addTo(maps[map_id]);

  // Some elements zooming to be ready on map
  var ende = [];

  //
  var markergroups = window.WPLeafletMapPlugin.markergroups;
  var mapmarkers = 0;
  var mappolygon = 0;
  var maplines = 0;
  var mapcircles = 0;
  Object.entries(markergroups).forEach(([key, value]) => {
    if ( markergroups[key]._map !== null ) {
      if (map_id == markergroups[key]._map._leaflet_id) {
        //console.log("markergroups loop");
        markergroups[key].eachLayer(function(layer) {
          //console.log(layer);
          if (layer instanceof L.Marker){
            //markers do not have fitbounds
            //console.log("is_marker");
            //console.log("_shouldFitBounds "+maps[map_id]._shouldFitBounds);
            //console.log("allfit[map_id] "+allfit[map_id]);
            mapmarkers++;
            if ( typeof maps[map_id]._shouldFitBounds !== "undefined") {
              //console.log("marker in all map");
              bounds[map_id].extend(layer._latlng);
            } else if ( typeof allfit[map_id] !== "undefined") {
              //console.log("marker should fit Homebutton");
              allfit[map_id].extend(layer._latlng);
              //console.log("allfit marker");
            } else {
              //console.log("marker was nun?");
            }
          } else if (layer instanceof L.Polygon) {
            //console.log("is_Polygon");
            mappolygon++;
            if ( typeof maps[map_id]._shouldFitBounds !== "undefined") {
              bounds[map_id].extend(layer.getBounds());
            } else if ( typeof allfit[map_id] !== "undefined") {
              //console.log("allfit polygon wird groesser");
              allfit[map_id].extend(layer.getBounds());
            } else {
              //console.log("polygon was nun?");
            }
          } else if (layer instanceof L.Polyline){
            //console.log("is_Line");
            maplines++;
            if ( typeof maps[map_id]._shouldFitBounds !== "undefined") {
              //console.log("all lines should fit to map");
              bounds[map_id].extend(layer.getBounds());
              ende[map_id] = 1;
              maps[map_id].on("zoomend", function(e) {
                if ( ende[map_id] ) {
                  //console.log("lines zooming");
                  maps[map_id].fitBounds(bounds[map_id]);
                }
                ende[map_id] = 0;
              });
            } else if ( typeof allfit[map_id] !== "undefined") {
              //console.log("allfit line wird groesser");
              allfit[map_id].extend(layer.getBounds());
            } else {
              //console.log("line was nun?");
              ende[map_id] = 1;
              maps[map_id].on("zoomend", function(e) {
                if ( ende[map_id] ) {
                  //console.log(map_id+ "ready zoomend");
                  //Uncaught Error: Attempted to load an infinite number of tiles
                  //zoomHome[map_id].setHomeZoom(maps[map_id].getBounds());
                  zoomHome[map_id].setHomeCoordinates(maps[map_id].getCenter());
                  zoomHome[map_id].setHomeZoom(maps[map_id].getZoom());
                  ende[map_id] = 0;
                }
              });
            }
          } else if (layer instanceof L.Circle){
            //console.log("is_Circle");
            //console.log(layer);
            mapcircles++;
            if ( typeof maps[map_id]._shouldFitBounds !== "undefined") {
              //https://github.com/Leaflet/Leaflet/issues/4978
              if ( layer._map ) {
                //console.log("has map");
                bounds[map_id].extend(layer.getBounds());
              } else {
                //console.log("has no map");
                maps[map_id].addLayer(layer);
                bounds[map_id].extend(layer.getBounds());
                maps[map_id].removeLayer(layer);
              }
            } else if ( typeof allfit[map_id] !== "undefined") {
              allfit[map_id].extend(layer.getBounds());
            }
          } else {
            //console.log(layer);
          }
        });
      }
    }
  });
  //console.log("markers "+mapmarkers);
  //console.log("polygon "+mappolygon);
  //console.log("lines "+maplines);
  //console.log("circles "+mapcircles);

  maps[map_id].whenReady ( function() {
    //console.log("map is ready");
    if (bounds[map_id].isValid()) {
      //console.log("ready map has bounds");
      zoomHome[map_id].setHomeBounds(bounds[map_id]);
      maps[map_id].fitBounds(bounds[map_id]);
    } else {
      //console.log(map_id+" ready map has no bounds");
      //console.log(allfit[map_id]);
      //console.log(bounds[map_id]);
      if (typeof allfit[map_id] !== "undefined") {
        //console.log("ready map allfit");
        if (allfit[map_id].isValid()) {
          //console.log("ready map has valid allfit");
          zoomHome[map_id].setHomeBounds(allfit[map_id]);
          //maps[map_id].fitBounds(bounds[map_id]);
        } else {
          //console.log("ready map has invalid allfit");
        }
      } else {
        //console.log("ready map has no allfit");
        //console.log(maps[map_id].getZoom());
      }
    }
  });

  //geojson asynchron
  var geojsons = window.WPLeafletMapPlugin.geojsons;
  if (geojsons.length > 0) {
    //console.log("geojsons "+geojsons.length);
    var geocount = geojsons.length;
    ende[map_id] = 1;
    for (var j = 0, len = geocount; j < len; j++) {
      var geojson = geojsons[j];
      if (map_id == geojsons[j]._map._leaflet_id) {
        geojson.on("ready", function () {
          //console.log(this._map._leaflet_id);
          //console.log(this);
          if ( typeof maps[map_id]._shouldFitBounds !== "undefined") { //leaflet-map fitbounds
            //console.log("geojson maps"+map_id+"._shouldFitBounds "+maps[map_id]._shouldFitBounds);
            bounds[map_id].extend(this.getBounds());
            zoomHome[map_id].setHomeBounds(bounds[map_id]);
            maps[map_id].fitBounds(bounds[map_id]);
          } else if (typeof maps[map_id]._animateToZoom !== "undefined" ) { //zoom auf das element
            //console.log("geojson animate "+map_id+" "+maps[map_id]._animateToZoom);
            zoomHome[map_id].setHomeCoordinates(maps[map_id]._animateToCenter);
            zoomHome[map_id].setHomeZoom(maps[map_id]._animateToZoom);
          } else if ( ! bounds[map_id].isValid() ) {
            //console.log("geojson bounds invalid "+map_id); // weder noch
            //console.log(this.json);
            //console.log(this.json.type);
            if ( this.json.type == "Feature") {
              maps[map_id].on("zoomend", function(e) {
                //console.log("zoomend "+map_id+" funktion definiert "+ende[map_id]);
                if ( ende[map_id] ) {
                  //console.log("geojson "+map_id+ " ready zoomend");
                  //zoomHome[map_id].setHomeZoom(maps[map_id].getBounds());
                  //Uncaught Error: Attempted to load an infinite number of tiles
                  zoomHome[map_id].setHomeCoordinates(maps[map_id].getCenter());
                  zoomHome[map_id].setHomeZoom(maps[map_id].getZoom());
                  ende[map_id] = 0;
                }
              });
            } else {
              zoomHome[map_id].setHomeCoordinates(maps[map_id].getCenter());
              zoomHome[map_id].setHomeZoom(maps[map_id].getZoom());
            }
          } else {
            //console.log("kommt das vor?");
            //console.log("geojson bounds valid");
            //zoomHome[map_id].setHomeBounds(bounds[map_id]);
            //maps[map_id].fitBounds(bounds[map_id]);
          }
          if ( typeof allfit[map_id] !== "undefined") {
            //console.log("allfit geojson wird groesser");
            allfit[map_id].extend(this.getBounds());
            zoomHome[map_id].setHomeBounds(allfit[map_id]);
          }
        });
      }
    }
  }

  //elevation asynchron
  maps[map_id].on("eledata_loaded", function(e) {
    //console.log(map_id+"elevation loaded");
    //console.log("maps[map_id]._shouldFitBounds "+maps[map_id]._shouldFitBounds);
    if ( typeof maps[map_id]._shouldFitBounds !== "undefined") {
      bounds[map_id].extend(e.layer.getBounds());
      //console.log("ele getbounds");
    }
    if (bounds[map_id].isValid()) {
      //console.log("ele bounds valid");
      zoomHome[map_id].setHomeBounds(bounds[map_id]);
      maps[map_id].fitBounds(bounds[map_id]);
    } else {
      //console.log("ele bounds not valid");
      //console.log("animate "+maps[map_id]._zoom);
      zoomHome[map_id].setHomeCoordinates(maps[map_id].getCenter());
      zoomHome[map_id].setHomeZoom(maps[map_id].getZoom());
      //console.log(maps[map_id]);
    }
  });

  // maps[map_id].on("zoomend", function(e) {
  // 	console.log("zoomend zoom "+map_id+" "+maps[map_id].getZoom());
  // });
}
