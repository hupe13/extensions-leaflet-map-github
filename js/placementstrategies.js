function leafext_placementstrategies_js(all_params) {
  var map = window.WPLeafletMapPlugin.getCurrentMap();
  //console.log(map);
  var map_id = map._leaflet_id;

  function getRandomColor() {
    var letters = "0123456789ABCDEF";
    var color = "#";
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }

  if ( WPLeafletMapPlugin.markers.length > 0 ) {
    //console.log("WPLeafletMapPlugin.markers.length "+WPLeafletMapPlugin.markers.length);
    var clmarkers = L.markerClusterGroup({
      spiderLegPolylineOptions: { weight: 0 },

      elementsPlacementStrategy: all_params['elementsPlacementStrategy'],
      helpingCircles: all_params['helpingCircles'],

      spiderfyDistanceSurplus: all_params['spiderfyDistanceSurplus'],
      spiderfyDistanceMultiplier: 1,

      elementsMultiplier: all_params['elementsMultiplier'],
      firstCircleElements: all_params['firstCircleElements'],

      //
      maxClusterRadius: all_params['maxClusterRadius'],
      spiderfyOnMaxZoom: all_params['spiderfyOnMaxZoom'],
    });

    for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
      if ( WPLeafletMapPlugin.markers[i]._map !== null ) {
        if (map_id == WPLeafletMapPlugin.markers[i]._map._leaflet_id) {
          var a = WPLeafletMapPlugin.markers[i];
          if (all_params['shapes'] == "circle") {
            map.createPane("locationMarker");
            map.getPane("locationMarker").style.zIndex = 610;
            var circle = L.circleMarker(a.getLatLng(), {
              fillOpacity: 0.7,
              radius: 8,
              fillColor: getRandomColor(),
              color: "grey",
              pane: "locationMarker",
            });
            //console.log(circle);
            circle.bindPopup(a.getPopup());
            clmarkers.addLayer(circle);
            //console.log(circle._popup);
          } else {
            clmarkers.addLayer(a);
          }
          map.removeLayer(a);
        }
      }
    }
    clmarkers.addTo( map );
    WPLeafletMapPlugin.markers.push( clmarkers );
  }
}
