/**
* Javascript functions for shortcode markercluster
* extensions-leaflet-map
*/

function leafext_markercluster_js(clmarkers) {
  var map = window.WPLeafletMapPlugin.getCurrentMap();
  var map_id = map._leaflet_id;
  if ( WPLeafletMapPlugin.markers.length > 0 ) {
  //console.log("map.options.maxZoom "+map.options.maxZoom);
  if (! map.options.maxZoom )
  map.options.maxZoom = 19;
  //console.log("WPLeafletMapPlugin.markers.length "+WPLeafletMapPlugin.markers.length);
  for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
    if ( WPLeafletMapPlugin.markers[i]._map !== null ) {
      if (map_id == WPLeafletMapPlugin.markers[i]._map._leaflet_id) {
        var a = WPLeafletMapPlugin.markers[i];
        //console.log(a);
        clmarkers.addLayer(a);
        map.removeLayer(a);
      }
    }
  }
}

//geojsons
var geojsons = window.WPLeafletMapPlugin.geojsons;
if (geojsons.length > 0) {
  var geocount = geojsons.length;
  for (var j = 0, len = geocount; j < len; j++) {
    var geojson = geojsons[j];
    //console.log(geojson);
    if (map_id == geojsons[j]._map._leaflet_id) {
      geojson.on("ready", function () {
        //console.log(this.layer);
        this.layer.eachLayer(function(layer) {
          //console.log(layer.feature);
          //console.log(layer.feature.properties);
          if (layer.feature.geometry.type == "Point" ) {
            //console.log(layer);
            //console.log(layer.feature.properties.name);
            var content = layer.feature.properties.name;
            //console.log(layer.getPopup());
            if ( layer.getPopup() ) {
              //
            } else if (typeof content != "undefined") {
              layer.bindTooltip(content);
              layer.bindPopup(content);
              //console.log(content);
            } else {
              //What should popup?? Default: "Point"
              //console.log(layer.feature.properties);
              //var popupContent = [];
              //for (var key in layer.feature.properties) {
              //if (layer.feature.properties.hasOwnProperty(key)) {
              //var stringLine = key + ": " + layer.feature.properties[key];
              //popupContent.push(stringLine);
              //}
              //}
              //console.log(popupContent.join(" <br>"));
              //layer.bindTooltip(popupContent.join(" <br>"));
              //layer.bindPopup(popupContent.join(" <br>"));
              layer.bindTooltip("Point");
              layer.bindPopup("Point");
            }
            map.removeLayer(layer);
            clmarkers.addLayer(layer);
          } else {
            //console.log(layer);
          }
        });
      });
    }
  }
}
clmarkers.addTo( map );
}
