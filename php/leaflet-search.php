<?php
/**
* Functions for leaflet-search shortcode
* extensions-leaflet-map
* leaflet-search knows: L.Marker L.CircleMarker L.LayerGroup L.Path L.Polyline L.Polygon L.LayerGroup, GeoJson
* implemented: L.Marker, GeoJson, L.Polygon, L.Circle L.Polyline
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
      'desc' => sprintf(__('a option / property for marker, polygon, circle, line or a %s for geojson layer. Can also be a comma-separated list of options or properties.',"extensions-leaflet-map"),
      'feature.property'),
      'default' => 'title',
      'values' => sprintf(__('for example %s for marker, additional for example %s for extramarkers; %s for polygon, circle, line; %s depending on geojson layer; %s for all',"extensions-leaflet-map"),
      'title, iconclass',
      'number',
      'color, className',
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
    //hupe13: default true
    array(
      'param' => 'hideMarkerOnCollapse',
      'desc' => __('remove circle and marker on search control collapsed or search canceled',"extensions-leaflet-map"),
      'default' => true,
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
      $pattern = array("/:\s*'\s*(-?\d+)\s*,\s*(-?\d+)\s*'/");
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
      searchcontrol[map_id][att_propertyName] = att_propertyName;
      console.log("mapid: "+map_id);
      console.log(all_properties);
      console.log(<?php echo json_encode($jsoptions);?>);
      searchLayer = new L.LayerGroup();	//layer contain searched elements
      var duplicates = [];
    }

    for (var i = 0; i < WPLeafletMapPlugin.markers.length; i++) {
      if ( WPLeafletMapPlugin.markers[i]._map !== null ) {
        if (map_id == WPLeafletMapPlugin.markers[i]._map._leaflet_id) {
          //console.log("Marker");
          let a = WPLeafletMapPlugin.markers[i];
          //console.log(a);

          let leafextsearch = "";
          for (let i = 0; i < all_properties.length; i++) {
            //console.log(leafextsearch);
            att_property = all_properties[i];
            //console.log(att_property);
            //console.log(a.options);
            let this_options = a.getIcon().options;
            //console.log(this_options);
            if (this_options.hasOwnProperty(att_property)) {
              if ( a.options[att_property] ) {
                //console.log("a.options.property " +att_property);
                leafextsearch = leafextsearch.concat(' | ',a.options[att_property]);
              } else {
                //console.log("this_options.property "+att_property);
                leafextsearch = leafextsearch.concat(' | ',this_options[att_property]);
              }
            } else
            if (att_property == "popupContent") {
              if ( a.getPopup() ) {
                if ( a.getPopup().getContent() ) {
                  let this_content = a.getPopup().getContent();
                  // replace - strip out HTML
                  this_content=this_content.replace( /<p>/ig, ' ');
                  this_content=this_content.replace( /<br>/ig, ' ');
                  this_content=this_content.replace( /(<([^>]+)>)/ig,'');
                  this_content=this_content.replace( / \| /g, '');
                  this_content=this_content.replace( / +/g, ' ');
                  leafextsearch = leafextsearch.concat(' | ', this_content );
                }
              }
              //console.log(leafextsearch);
            }
            else {
              if (a.options[att_property] && a.options[att_property] != "") {
                //console.log("a.options[att_property] "+att_property);
                leafextsearch = leafextsearch.concat(' | ',a.options[att_property]);
              } else {
                //console.log("was nun?");
              }
            }
          }

          if (leafextsearch != "") {
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
            searchLayer.addLayer(a);
          }
          //map.removeLayer(a);
        } // _map._leaflet_id
      } // has markers[i]._map
    }

    //console.log("after marker searchLayer.length "+Object.keys(searchLayer._layers).length);

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
              //console.log("markergroup");

              let leafextsearch = "";
              for (let i = 0; i < all_properties.length; i++) {
                att_property = all_properties[i];

                if (layer.hasOwnProperty(att_property)) {
                  if ( layer.options[att_property] ) {
                    leafextsearch = leafextsearch.concat(' | ',layer.options[att_property]);
                  }
                } else
                if (att_property == "popupContent") {
                  //console.log("popupContent");
                  if ( layer.getPopup() ) {
                    if ( layer.getPopup().getContent() ) {
                      let this_content = layer.getPopup().getContent();
                      // replace - strip out HTML
                      this_content=this_content.replace( /<p>/ig, ' ');
                      this_content=this_content.replace( /<br>/ig, ' ');
                      this_content=this_content.replace( /(<([^>]+)>)/ig,'');
                      this_content=this_content.replace( / \| /g, '');
                      this_content=this_content.replace( / +/g, ' ');
                      leafextsearch = leafextsearch.concat(' | ', this_content );
                    }
                  }
                }
                else {
                  if (layer.options[att_property] && layer.options[att_property] != "") {
                    leafextsearch = leafextsearch.concat(' | ',layer.options[att_property]);
                  } else {
                    //console.log("was nun?");
                  }
                }

                if (leafextsearch != "") {
                  let search = leafextsearch;
                  if (typeof duplicates[search] == "undefined" ) {
                    duplicates[search] = 1;
                  } else {
                    duplicates[search] = duplicates[search] + 1;
                  }
                }

                if (leafextsearch != "") {
                  layer.options["searchindex"] = i;
                  layer.options[att_propertyName] = leafextsearch;
                  searchLayer.addLayer(layer);
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

    //console.log("after markergroup searchLayer.length "+Object.keys(searchLayer._layers).length);

    // console.log("geojsons");
    var geojsons = window.WPLeafletMapPlugin.geojsons;
    for (var j = 0, len = geojsons.length; j < len; j++) {
      if (map_id == geojsons[j]._map._leaflet_id) {
        // geojsons[j].options[all_properties]
        // console.log("geojson");
        // console.log(geojsons[j]);
        geojsons[j].on("ready", function (e) {
          //console.log("geojson ready");
          //console.log(e);
          let duplicates = [];
          j = 0;
          e.target.eachLayer(function(layer) {
            //console.log(layer.options);
            let leafextsearch = "";
            if (layer.options) {
              for (let i = 0; i < all_properties.length; i++) {
                att_property = all_properties[i];
                if (layer.options[att_property] && layer.options[att_property] != "") {
                  leafextsearch = leafextsearch.concat(' | ',layer.options[att_property]);
                }
              }
            }
            for (let i = 0; i < all_properties.length; i++) {
              att_property = all_properties[i];
              //console.log(att_property);
              // console.log(layer.feature.properties.hasOwnProperty(att_property));
              // console.log(layer.feature.properties);
              //if (layer.feature.properties.hasOwnProperty(att_property)) {
              if (att_property == "popupContent") {
                if ( layer.getPopup() ) {
                  // console.log("geojson popup");
                  //layer.feature.properties['popupContent'] = layer.getPopup().getContent();
                  let this_content = layer.getPopup().getContent();
                  // replace - strip out HTML
                  this_content=this_content.replace( /<p>/ig, ' ');
                  this_content=this_content.replace( /<br>/ig, ' ');
                  this_content=this_content.replace( /(<([^>]+)>)/ig,'');
                  this_content=this_content.replace( / \| /g, '');
                  this_content=this_content.replace( / +/g, ' ');
                  leafextsearch = leafextsearch.concat(' | ', this_content );
                  //console.log(leafextsearch);
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
            layer.feature.properties[att_propertyName]=layer.feature.properties[att_propertyName].replace( /\| /, '');
          });
        });
        geojsons[j].addTo(searchLayer);
      }
    }

    //console.log("after geojson searchLayer.length "+Object.keys(searchLayer._layers).length);

    if (Object.keys(searchLayer._layers).length > 0) {
      searchLayer.eachLayer(function(layer) {
        if (layer.options[att_propertyName]) {
          let search = layer.options[att_propertyName];
          //console.log(search);
          if (duplicates[search] > 1) {
            layer.options[att_propertyName] = layer.options[att_propertyName] + " | "+ layer.options["searchindex"];
          }
          layer.options[att_propertyName]=layer.options[att_propertyName].replace( /\| /, '');
          //console.log(layer.options[att_propertyName]);
        }
      });

      var SearchControl = new L.Control.Search({
        layer: searchLayer,
        <?php echo $jsoptions;?>
        initial: false,
        moveToLocation: function(latlng, title, map) {
          //console.log("moveToLocation");
          // console.log(latlng, title);
          // console.log(latlng.layer);
          if (latlng.layer.feature && latlng.layer.feature.geometry && latlng.layer.feature.geometry.type == "Point") {
            map.fitBounds(L.latLngBounds([latlng]));
            map.setZoom(att_zoom);
          } else if ( latlng.layer instanceof L.Marker ) {
            //console.log("has not Bounds");
            map.fitBounds(L.latLngBounds([latlng]));
            map.setZoom(att_zoom);
          } else if ( latlng.layer instanceof L.Circle ) {
            //console.log("circle gefunden");
            //console.log(latlng.layer);
            //https://github.com/Leaflet/Leaflet/issues/4978
            if ( latlng.layer._map ) {
              console.log("has map");
              map.fitBounds( latlng.layer.getBounds() );
              var zoom = map.getBoundsZoom(latlng.layer.getBounds());
              map.setView(latlng, zoom);
            } else {
              //console.log("has no map");
              map.addLayer(latlng.layer);
              map.fitBounds( latlng.layer.getBounds() );
              var zoom = map.getBoundsZoom(latlng.layer.getBounds());
              map.setView(latlng, zoom);
              map.removeLayer(latlng.layer);
            }
          } else {
            map.fitBounds( latlng.layer.getBounds() );
            var zoom = map.getBoundsZoom(latlng.layer.getBounds());
            map.setView(latlng, zoom);
          }
        }
      }
    );

    map.addControl( SearchControl );

    SearchControl.on("search:locationfound", function(e) {
      //console.log(e);
      //console.log("search:locationfound");
      if(e.target._map.hasLayer(e.layer)){
        //console.log("layer is visible, do nothing");
      } else {
        //console.log("layer is not on map, _adding");
        e.layer.addTo(map);
        this._markerSearch._markeradd = e.layer;
      }
      <?php
      if ($options['openPopup']) {?>
        if ( e.layer.getPopup() )// e.layer.openPopup();
        e.layer.openPopup([e.latlng.lat, e.latlng.lng]);
        <?php
      } ?>
      e.sourceTarget._input.blur();
    });
    SearchControl.on("search:cancel", function(e) {
      //console.log("search:cancel");
      //console.log(e);
      //console.log(this);
      //console.log(this._markerSearch);
      //console.log(this._markerSearch._markeradd);
      if ( this._markerSearch && this._markerSearch._markeradd ) {
        e.target._map.removeLayer(this._markerSearch._markeradd);
      }
      <?php
      if ($options['openPopup']) {?>
        map.closePopup();
        //e.layer.openPopup([e.latlng.lat, e.latlng.lng]);
        <?php
      } ?>
      if (e.target.options.hideMarkerOnCollapse && this._markerSearch) {
        //console.log(e.target._map);
        e.target._map.removeLayer(this._markerSearch);
      }
      e.sourceTarget._input.blur();
    });
    map.addLayer(searchLayer);
  }
});

<?php
$javascript = ob_get_clean();
$text = $text . $javascript . '//-->'."\n".'</script>';
$text = \JShrink\Minifier::minify($text);
return "\n".$text."\n";
}
