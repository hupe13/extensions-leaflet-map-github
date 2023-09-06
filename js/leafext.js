/**
* Javascript functions for Extensions for Leaflet Map
* extensions-leaflet-map
*/

function leafext_make_overstyle(element) {
  if ( element.setStyle ) {
    //console.log(element.options);
    if (!element.options.mouseover) {
      element.options.mouseover = true;
      if ( ! element.options.weight ) {
        var highweight = 5; //leaflet default +2
      } else {
        element.options.origweight = element.options.weight;
        var highweight = element.options.weight + 2;
      }
      if ( ! element.options.fillOpacity ) {
        var highfillOpacity = 0.4; //leaflet default + 0.2
      } else {
        element.options.origfillOpacity = element.options.fillOpacity;
        var highfillOpacity = element.options.fillOpacity + 0.2;
      }
      element.setStyle({
        "fillOpacity" : highfillOpacity,
        "weight" : highweight,
      });
      element.bringToFront();
    }
  }
}

function leafext_make_styleback(element) {
  if ( element.setStyle ) {
    if (element.options.mouseover) {
      element.options.mouseover = false;
      if ( element.options.origweight ) {
        var origweight = element.options.origweight;
      } else {
        var origweight = 3; //leaflet default
      }
      if ( element.options.origfillOpacity ) {
        var origfillOpacity =  element.options.origfillOpacity;
      } else {
        var origfillOpacity = 0.2; //leaflet default
      }
      element.setStyle({
        "fillOpacity" : origfillOpacity,
        "weight" : origweight,
      });
    }
  }
  if (typeof element.bringToBack === "function") {
    element.bringToBack();
  }
}

function leafext_map_popups(map) {
  let popup = false;
  map.eachLayer(function(layer){
    if ( layer instanceof L.Popup ) {
      //console.log("popup is open");
      popup = true;
    }
  });
  return popup;
}
