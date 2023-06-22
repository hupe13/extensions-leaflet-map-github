/**
* Javascript functions for Extensions for Leaflet Map
* extensions-leaflet-map
*/

function leafext_choropleth_js(map,att_valueProperty,att_scale,att_steps,att_mode,att_popup,att_legend,att_hover,att_fillOpacity) {
  map.eachLayer(function(layer) {
    //console.log(layer.options.type);
    if (layer.options.type == "json" ) {
      layer.on("ready", function (layer){
        // console.log(layer.target.json);
        map.removeLayer(layer);
        choropleth = L.choropleth(layer.sourceTarget.json, {
          valueProperty: att_valueProperty,
          scale: att_scale,
          steps: att_steps,
          mode: att_mode,
          style: {
            color: "#fff",
            weight: 2,
            fillOpacity: att_fillOpacity
          },
          onEachFeature: function (feature, layer) {
            let this_popup = "";
            for (var j = 0, len = att_popup.length; j < len; j++) {
              //console.log(att_popup[j]);
              if (att_popup[j].indexOf("{") !== -1) { // true; found
                let property = att_popup[j].replace(/{|}/g, '');
                //console.log(property);
                this_popup = this_popup + feature.properties[property];
              } else {
                this_popup = this_popup + att_popup[j];
              }
            }
            layer.bindPopup(this_popup);

            layer.on("mouseover", function (e) {
              e.target.setStyle({
                weight: 4,
                color: "#666",
              });
              e.target.bringToFront();
            }),
            layer.on("mouseout", function (e) {
              e.target.setStyle({
                weight: 2,
                color: "#fff",
              });
            }),

            layer.on("mouseover", function (e) {
              if (att_hover) {
                if ( layer.getPopup() ) {
                  //console.log("popup defined");
                  if (layer.getPopup().isOpen()) {
                    //console.log("mouseover open "+layer.getPopup().isOpen());
                    layer.unbindTooltip();
                  } else {
                    //console.log("need tooltip");
                    var content = layer.getPopup().getContent();
                    layer.bindTooltip(content);
                  }
                }
              }
            }),

            layer.on("mousemove", function (e) {
              if (att_hover) {
                if ( layer.getPopup() ) {
                  if (layer.getPopup().isOpen()) {
                    //console.log("mousemove open "+layer.getPopup().isOpen());
                    layer.unbindTooltip();
                  } else {
                    //console.log("mousemove close "+layer.getPopup().isOpen());
                    map.closePopup();
                    layer.openTooltip(e.latlng);
                  }
                }
              }
            }),

            layer.on("click", function (e) {
              if (att_hover) {
                if ( layer.getPopup() ) {
                  if (layer.getPopup().isOpen())
                  layer.unbindTooltip();
                }
              }
            })

          }
        }); // choropleth
        choropleth.addTo(map);

        // Add legend (don't forget to add the CSS from index.html)
        if (att_legend) {
          var legend = L.control({ position: 'bottomright' });
          legend.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'info legend');
            var limits = choropleth.options.limits;
            var colors = choropleth.options.colors;
            var labels = [];
            // Add min & max
            div.innerHTML = '<div class="labels"><div class="min">' + limits[0] + '</div> \
            <div class="max">' + limits[limits.length - 1] + '</div></div>';
            limits.forEach(function (limit, index) {
              labels.push('<li style="background-color: ' + colors[index] + '; opacity: '+ att_fillOpacity +';"></li>');
            })
            div.innerHTML += '<ul>' + labels.join('') + '</ul>';
            return div;
          }
          legend.addTo(map);
        }

      }); // on ready
    }; // if
  }); // map.eachLayer
}
