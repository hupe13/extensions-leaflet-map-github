<?php
/**
* Functions for leaflet-search shortcode
* extensions-leaflet-map
* leaflet-search knows: L.Marker L.CircleMarker L.LayerGroup L.Path L.Polyline L.Polygon L.LayerGroup, GeoJson
* implemented: L.Marker, GeoJson
* Leaflet-Map knows CircleMarker only in leaflet-geojson
*/
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

//Parameter and Values
function leafext_search_params() {
  $params = array(
    // | url             | ''       | url for search by ajax request, ex: "search.php?q={s}". Can be function to returns string for dynamic parameter setting | |
    // | layer		      | null	 | layer where search markers(is a L.LayerGroup)				 |
    // | sourceData	  | null     | function to fill _recordsCache, passed searching text by first param and callback in second				 |
    // | jsonpParam	  | null	 | jsonp param name for search by jsonp service, ex: "callback" |
    // | propertyLoc	  | 'loc'	 | field for remapping location, using array: ['latname','lonname'] for select double fields(ex. ['lat','lon'] ) support dotted format: 'prop.subprop.title' |
    // | propertyName	  | 'title'	 | property in marker.options(or feature.properties for vector layer) trough filter elements in layer, |
    array(
      'param' => 'propertyName',
      'desc' => sprintf(__('a option / property for marker or a %s for geojson layer. Can also be a comma-separated list of options or properties.',"extensions-leaflet-map"),
      'feature.property'),
      'default' => 'title',
      'values' => sprintf(__('for example %s for marker, additional for example %s for extramarkers; %s depending on geojson layer; %s for all',"extensions-leaflet-map"),
      'title, iconclass',
      'number',
      'feature.property',
      'popupContent'),
    ),
    // | formatData	  | null	 | callback for reformat all data from source to indexed data object |
    // | filterData	  | null	 | callback for filtering data from text searched, params: textSearch, allRecords |
    // | moveToLocation  | null	 | callback run on location found, params: latlng, title, map |
    // | buildTip		  | null	 | function to return row tip html node(or html string), receive text tooltip in first param |
    // | container		  | ''	     | container id to insert Search Control		 |
    array(
      'param' => 'container',
      'desc' => __('container id to style the control panel (outside the map) with custom css',"extensions-leaflet-map"),
      'default' => '',
      'values' => __('see below',"extensions-leaflet-map"),
    ),
    // | zoom		      | null	 | default zoom level for move to location |
    array(
      'param' => 'zoom',
      'desc' => __('zoom level for move to location for searching markers',"extensions-leaflet-map"),
      'default' => get_option('leaflet_default_zoom', '15'),
      'values' => __('The default value is the same as the setting for the Leaflet Map plugin.',"extensions-leaflet-map"),
    ),
    // | minLength		  | 1	     | minimal text length for autocomplete |
    // | initial		  | true	 | search elements only by initial text |
    // | casesensitive   | false	 | search elements in case sensitive text |
    // | autoType		  | true	 | complete input with first suggested result and select this filled-in text. |
    // | delayType		  | 400	     | delay while typing for show tooltip |
    // | tooltipLimit	  | -1	     | limit max results to show in tooltip. -1 for no limit, 0 for no results |
    // | tipAutoSubmit	  | true	 | auto map panTo when click on tooltip |
    // | firstTipSubmit  | false	 | auto select first result con enter click |
    // | autoResize	  | true	 | autoresize on input change |
    // | collapsed		  | true	 | collapse search control at startup |
    // | autoCollapse	  | false	 | collapse search control after submit(on button or on tips if enabled tipAutoSubmit) |
    // | autoCollapseTime| 1200	 | delay for autoclosing alert and collapse after blur |
    array(
      'param' => 'autoCollapseTime',
      'desc' => __('delay for autoclosing alert and collapse after blur',"extensions-leaflet-map"),
      'default' => '1200',
      'values' => '',
    ),
    // | textErr		  | 'Location not found' |	error message |
    array(
      'param' => 'textErr',
      'desc' => __('error message',"extensions-leaflet-map"),
      'default' => __('Location not found',"extensions-leaflet-map"),
      'values' => '',
    ),
    // | textCancel	  | 'Cancel	 | title in cancel button		 |
    // | textPlaceholder | 'Search' | placeholder value			 |
    array(
      'param' => 'textPlaceholder',
      'desc' => __('placeholder value',"extensions-leaflet-map"),
      'default' => __('Search...',"extensions-leaflet-map"),
      'values' => '',
    ),
    // // | hideMarkerOnCollapse		 | false	 | remove circle and marker on search control collapsed		 |
    array(
      'param' => 'hideMarkerOnCollapse',
      'desc' => __('remove circle and marker on search control collapsed or search canceled',"extensions-leaflet-map"),
      'default' => false,
      'values' => "true, false",
    ),
    // // | position		  | 'topleft'| position in the map		 |
    array(
      'param' => 'position',
      'desc' => __('position in the map',"extensions-leaflet-map"),
      'default' => 'topleft',
      'values' => "topleft, topright, bottomleft, bottomright",
    ),
    // // | marker		  | {}	     | custom L.Marker or false for hide |
    array(
      'param' => 'marker',
      'desc' => __('show or hide marker at the position found',"extensions-leaflet-map"),
      'default' => '',
      'values' => sprintf(__("not specified for default (red circle), %s for no marker, or a definition like on an %sexample page%s.","extensions-leaflet-map"),
      'false',
      '<a href="https://leafext.de/leafletsearch/leafletsearchmarker/">',
      '</a>'
      ),
    ),
    // | marker.icon	  | false	 | custom L.Icon for maker location or false for hide |
    // | marker.animate  | true	 | animate a circle over location found |
    // | marker.circle	  | L.CircleMarker options |	draw a circle in location found |
    // hupe13 open popup when location found
    array(
      'param' => 'openPopup',
      'desc' => __('show or hide popup when position found',"extensions-leaflet-map"),
      'default' => true,
      'values' => "true, false",
    ),
    // hupe13 type
    array(
      'param' => 'type',
      'desc' => __('specify this parameter if both leaflet-marker and leaflet-geojson elements are on a post or page.',"extensions-leaflet-map"),
      'default' => 'marker',
      'values' => sprintf(__("%s (default) - search for marker, %s - search in geojson.","extensions-leaflet-map"),
      '"marker"',
      '"geojson"',
      '"all"'),
    ),

  );
  return $params;
}

function leafext_leafletsearch_function($atts,$content,$shortcode) {
  $text = leafext_should_interpret_shortcode($shortcode,$atts);
  if ( $text != "" ) {
    return $text;
  } else {
    $defaults=array();
    $params = leafext_search_params();
    foreach($params as $param) {
      $defaults[$param['param']] = $param['default'];
    }
    $atts1=leafext_case(array_keys($defaults),leafext_clear_params($atts));
    $options = shortcode_atts($defaults, $atts1);
    //var_dump($options);wp_die();
    if ($options['marker'] == '') {
      unset($options['marker']);
    } else {
      $pattern = array("/<p>/","/<br>/","/:\s*'\s*(-?\d+)\s*,\s*(-?\d+)\s*'/");
      $replacement = array(":[$1,$2]");
      $options['marker'] = preg_replace($pattern, $replacement, $options['marker']);
    }
    if ($options['container'] == '') {
      unset($options['container']);
    } else {
      $options['collapsed'] = false;
    }
    if (strpos($options['textPlaceholder'],'"') !== false) {
      $options['textPlaceholder'] = str_replace('"','\"',$options['textPlaceholder']);
    }
    $allproperties = array_map('trim', explode(',', $options['propertyName']));
    $options['propertyName'] = implode($allproperties).rand(1, 20); //"leafextsearch";
    leafext_enqueue_leafletsearch ();
    //var_dump(leafext_java_params($options));wp_die();
    return leafext_leafletsearch_script($options,trim(preg_replace('/\s+/', ' ',leafext_java_params($options))),$allproperties);
  }
}
add_shortcode('leaflet-search', 'leafext_leafletsearch_function' );

function leafext_leafletsearch_script($options,$jsoptions,$allproperties){
  $text = '<script><!--';
  ob_start();
  ?>/*<script>*/
  window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
  window.WPLeafletMapPlugin.push(function () {
    let all_properties = <?php echo json_encode($allproperties); ?>;
    let att_zoom = <?php echo json_encode($options['zoom']); ?>;
    let att_propertyName = <?php echo json_encode($options['propertyName']); ?>;

    if (typeof searchcontrol == "undefined" ) {
      var maps=[];
      var searchcontrol = [];
    }
    var map = window.WPLeafletMapPlugin.getCurrentMap();
    var map_id = map._leaflet_id;
    if (typeof maps[map_id] == "undefined" ) {
      maps[map_id] = map;
      searchcontrol[map_id] = [];
    }
    if (typeof searchcontrol[map_id][att_propertyName] == "undefined") {
      searchcontrol[map_id][att_propertyName] = "found";
      console.log("mapid: "+map_id);
      console.log(all_properties);
      console.log(<?php echo json_encode($jsoptions);?>);
      let count_marker = WPLeafletMapPlugin.markers.length;
      let count_geojson = window.WPLeafletMapPlugin.geojsons.length;
      let type = <?php echo json_encode($options['type']);?>;
      //console.log(count_marker,count_geojson,type);

      if ( count_marker > 0 && (count_geojson == 0 || type != "geojson")) { //type "marker" or "all"
        //console.log("markers "+WPLeafletMapPlugin.markers.length);
        var markersLayer = new L.LayerGroup();	//layer contain searched elements
        let duplicates = {};
        for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
          if ( WPLeafletMapPlugin.markers[i]._map !== null ) {
            if (map_id == WPLeafletMapPlugin.markers[i]._map._leaflet_id) {
              //console.log("Marker");
              let a = WPLeafletMapPlugin.markers[i];
              //console.log(a);

              let leafextsearch = "";
              for (let i = 0; i < all_properties.length; i++) {

                att_property = all_properties[i];
                //console.log(a.options);
                let this_options = a.getIcon().options;
                //console.log(this_options);
                if (this_options.hasOwnProperty(att_property)) {
                  if (typeof a.options[att_property] != "undefined") {
                    //console.log("a.options.property " +att_property);
                    leafextsearch = leafextsearch.concat(' | ',a.options[att_property]);
                  } else {
                    //console.log("this_options.property "+att_property);
                    leafextsearch = leafextsearch.concat(' | ',this_options[att_property]);
                  }
                } else
                if (att_property == "popupContent") {
                  //console.log("popupContent");
                  if (typeof a.getPopup() != "undefined") {
                    if ( typeof a.getPopup().getContent() != "undefined" ) {
                      leafextsearch = leafextsearch.concat(' | ', a.getPopup().getContent());
                    }
                  }
                }
                else {
                  if (a.options[att_property] != "undefined" && a.options[att_property] != "") {
                    //console.log("a.options[att_property] "+att_property);
                    leafextsearch = leafextsearch.concat(' | ',a.options[att_property]);
                  } else {
                    console.log("was nun?");
                  }
                }
              }

              if (leafextsearch != "") {
                // replace - strip out HTML
                leafextsearch=leafextsearch.replace( /(<([^>]+)>)/ig,'');
                leafextsearch=leafextsearch.replace( / \| /, '');

                let search = leafextsearch;
                if (typeof duplicates[search] == "undefined" ) {
                  duplicates[search] = 1;
                } else {
                  duplicates[search] = duplicates[search] + 1;
                }
              }

              if (leafextsearch != "") {
                a.options["searchindex"] = i;
                a.options[att_propertyName] = leafextsearch;
                markersLayer.addLayer(a);
              }
              //map.removeLayer(a);
            } // _map._leaflet_id
          } // has markers[i]._map
        } // loop markers

        //console.log(markersLayer);
        //console.log("markerslayer.length "+Object.keys(markersLayer._layers).length);
        if (Object.keys(markersLayer._layers).length > 0) {
          if (searchcontrol[map_id][att_propertyName] == "found") {
            searchcontrol[map_id][att_propertyName] = "added";
            markersLayer.eachLayer(function(layer) {
              let search = layer.options[att_propertyName];
              //console.log(search);
              if (duplicates[search] > 1) {
                layer.options[att_propertyName] = layer.options[att_propertyName] + " | "+ layer.options["searchindex"];
              }
              //console.log(layer.options[att_propertyName]);
            });

            map.addLayer(markersLayer);
            var markerSearchControl = new L.Control.Search({
              layer: markersLayer,
              <?php echo $jsoptions;?>
              initial: false,
              moveToLocation: function(latlng, title, map) {
                //console.log( title);
                map.fitBounds(L.latLngBounds([latlng.layer.getLatLng()]));
                map.setZoom(att_zoom);
                // },
                // buildTip: function (text, val) {
                // // strip HTML out
                // string=text.replace( /(<([^>]+)>)/ig,'');
                // return '<li class="">'+string+'</li>';
              }
            }
          );
          map.addControl( markerSearchControl );

          markerSearchControl.on("search:locationfound", function(e) {
            <?php
            if ($options['openPopup']) {?>
              if (typeof e.layer.getPopup() != "undefined") e.layer.openPopup();
              <?php
            } ?>
            e.sourceTarget._input.blur();
          });
          markerSearchControl.on("search:cancel", function(e) {
            //console.log(e);
            if (e.target.options.hideMarkerOnCollapse) {
              e.target._map.removeLayer(this._markerSearch);
            }
            e.sourceTarget._input.blur();
          });
        }
      } else {
        console.log("Nothing to search in Markers");
      }
    } // markers.length

    //

    if ( count_geojson > 0 && (count_marker == 0 || type != "marker")) { //type "geojson" or "all"
      //console.log("geojsons");
      var geojsons = window.WPLeafletMapPlugin.geojsons;
      var geojsonLayers = new L.layerGroup();

      for (var j = 0, len = count_geojson; j < len; j++) {
        if (map_id == geojsons[j]._map._leaflet_id) {
          //console.log(geojsons[j]);
          geojsons[j].options[all_properties]
          geojsons[j].on("ready", function (e) {
            let duplicates = {};
            j = 0;
            e.target.eachLayer(function(layer) {
              let leafextsearch = "";
              for (let i = 0; i < all_properties.length; i++) {
                att_property = all_properties[i];
                //console.log(att_property);
                // console.log(layer.feature.properties.hasOwnProperty(att_property));
                // console.log(layer.feature.properties);
                //if (layer.feature.properties.hasOwnProperty(att_property)) {
                if (att_property == "popupContent") {
                  if (typeof layer.getPopup() != "undefined") {
                    //layer.feature.properties['popupContent'] = layer.getPopup().getContent();
                    leafextsearch = leafextsearch.concat(' | ',layer.getPopup().getContent());
                  }
                } else {
                  if (layer.feature.properties.hasOwnProperty(att_property)) {
                    leafextsearch = leafextsearch.concat(' | ',layer.feature.properties[att_property]);
                  }
                }
              }
              let search = leafextsearch;
              if (typeof duplicates[search] == "undefined" ) {
                duplicates[search] = 1;
              } else {
                duplicates[search] = duplicates[search] + 1;
              }
              layer.feature.properties["searchindex"] = j;
              j++;
              //console.log(leafextsearch);
              layer.feature.properties[att_propertyName] = leafextsearch;
            });
            //console.log(duplicates);
            e.target.eachLayer(function(layer) {
              let search = layer.feature.properties[att_propertyName];
              if (duplicates[search] > 1) {
                layer.feature.properties[att_propertyName] = layer.feature.properties[att_propertyName] + " | "+ layer.feature.properties["searchindex"];
                //console.log(layer.feature.properties);
              }
              layer.feature.properties[att_propertyName]=layer.feature.properties[att_propertyName].replace( / \| /, '');
            });
          });
          geojsons[j].addTo(geojsonLayers);
        }
        //
        if (Object.keys(geojsonLayers._layers).length > 0) {
          //console.log(Object.keys(geojsonLayers._layers).length);
          if (searchcontrol[map_id][att_propertyName] == "found") {
            searchcontrol[map_id][att_propertyName] = "added";
            map.addLayer(geojsonLayers);
            var geojsonSearchControl = new L.control.search({
              layer: geojsonLayers,
              <?php echo $jsoptions;?>
              initial: false,
              moveToLocation: function(latlng, title, map) {
                //console.log(latlng, title, map);
                //console.log(latlng.layer);
                //console.log(latlng.layer.feature.geometry.type);
                if (latlng.layer.feature.geometry.type == "Point") {
                  map.fitBounds(L.latLngBounds([latlng]));
                  map.setZoom(att_zoom);
                } else {
                  map.fitBounds( latlng.layer.getBounds() );
                  var zoom = map.getBoundsZoom(latlng.layer.getBounds());
                  map.setView(latlng, zoom); // access the zoom
                }
                // },
                // buildTip: function (text, val) {
                //   // strip HTML out
                //   string=text.replace( /(<([^>]+)>)/ig,'');
                //   return '<li class="">'+string+'</li>';
              }
            });
            map.addControl( geojsonSearchControl );  //inizialize search control
            geojsonSearchControl.on("search:locationfound", function(e) {
              //console.log("search:locationfound" );
              <?php
              if ($options['openPopup']) {?>
                if(e.layer._popup) e.layer.openPopup([e.latlng.lat, e.latlng.lng]);
                <?php
              } ?>
              e.sourceTarget._input.blur();
            });
            geojsonSearchControl.on("search:cancel", function(e) {
              //console.log(e);
              if (e.target.options.hideMarkerOnCollapse) {
                e.target._map.removeLayer(this._markerSearch);
              }
              e.sourceTarget._input.blur();
            });
          } else {
            console.log("Nothing to search in Geojsons");
          }
        }
      }
    }
  }
});

<?php
$javascript = ob_get_clean();
$text = $text . $javascript . '//-->'."\n".'</script>';
$text = \JShrink\Minifier::minify($text);
return "\n".$text."\n";
}
