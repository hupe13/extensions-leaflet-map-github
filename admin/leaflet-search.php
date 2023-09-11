<?php
function leafext_leafletsearch_help(){
  if (is_singular() || is_archive() ) {
    $text = '';
  } else {
    $text = '<h2>Leaflet Control Search</h2>';
  }

  $text=$text.'<p>'.__('A control for search Markers/Features location by custom property.',"extensions-leaflet-map").'</p>';

  $text=$text.'<h3>Shortcode</h3>
  <pre><code>&#091;leaflet-map fitbounds]
//any many
&#091;leaflet-marker      ...] ... [/leaflet-marker]
&#091;leaflet-extramarker ...] ... [/leaflet-extramarker]
&#091;leaflet-polygon     ...] ... [/leaflet-polygon]
&#091;leaflet-circle      ...] ... [/leaflet-circle]
&#091;leaflet-line        ...] ... [/leaflet-line]
&#091;leaflet-geojson     ...] ... [/leaflet-geojson]
//one or more
&#091;leaflet-search propertyName="..." ...]</code></pre>';
  $text = $text.'<p><h2>'.__('Options','extensions-leaflet-map').'</h2></p>';

  $options=leafext_search_params();
  $new = array();
  $new[] = array(
    'param' => "<strong>Option</strong>",
    'desc' => "<strong>".__('Description','extensions-leaflet-map').'</strong>',
    'default' => "<strong>".__('Default','extensions-leaflet-map').'</strong>',
    'values' => "<strong>".__('Values','extensions-leaflet-map').'</strong>',
  );
  foreach ($options as $option) {
    if ($option['default'] == '' && $option['param'] == "marker") $option['default'] = '('.__("red circle","extensions-leaflet-map").')';
    $new[] = array(
      'param' => $option['param'],
      'desc' => $option['desc'],
      'default' => (gettype($option['default']) == "boolean") ? ($option['default'] ? "true" : "false") : $option['default'],
      'values' => $option['values'],
    );
  }
  $text=$text.leafext_html_table($new);

  $text=$text.'<p>'.sprintf(__('See %sLeaflet Map Github page%s for more or less useful and possible options for %s.',"extensions-leaflet-map"),
  '<a href="https://github.com/bozdoz/wp-plugin-leaflet-map#leaflet-marker">','</a>','propertyName').'</p>';

  // $text=$text.'<h3>'.__('Examples for',"extensions-leaflet-map").' marker:</h3>';
  // $text=$text.'<p><a href="https://leafext.de/leafletsearch/leafletsearchmarker/">'.__('Examples',"extensions-leaflet-map").'</a></p>';
  $text=$text.'<h3>'.__('Option',"extensions-leaflet-map").' container</h3>';
  $text=$text.'<p>'.sprintf(__('If you want the search field to be outside the map, define a div element with a custom html block on the post / page and give it an id. This id you then specify in option %s.',"extensions-leaflet-map"),'container').'</p>';
  $text=$text.'<code>&lt;div id="myId" style="height:3em; border:2px solid gray; width:200px;">&lt;/div></code>';
  $text=$text.'<p>'.__('Define some css:',"extensions-leaflet-map").'</p>';
  $text=$text.'<pre><code>&lt;style>
.leaflet-control-search.search-exp { border: none !important;}
.search-input {width: 80%;}
&lt;/style></code></pre>';
$text=$text.'<style>.leaflet-control-search.search-exp { border: none !important;}.search-input {width: 80%;}</style>';
  $text=$text.'<p>'.__('Define a leaflet-search command with the option container:',"extensions-leaflet-map").'</p>';
  $text=$text.'<p><code>&#091;leaflet-search propertyname=... ... container=myId ...]</code>'.'</p>';
  $text=$text.'<div id="myId" style="height:3em; border:2px solid gray; width:200px;"></div>';
  $text=$text.do_shortcode('[leaflet-map !boxZoom !doubleClickZoom !dragging !keyboard !scrollwheel !attribution !touchZoom !show_scale height=200 width=200 fitbounds min_zoom=12 max_zoom=16]');
  $text=$text.do_shortcode('[leaflet-marker lat=0.0 lng=0.0]Marker[/leaflet-marker]');
  $text=$text.do_shortcode('[leaflet-search container=myId propertyName=popupContent textPlaceholder="M ..."]');
  $text=$text.'<p>'.sprintf(__('The specific style and css depends on your %stheme and taste%s',"extensions-leaflet-map"),'<a href="https://leafext.de/leafletsearch/searchcontainer/">','</a>');
  if (is_singular() || is_archive() ) {
    $text=$text.', '.__('as you can see on this page. This is the same code as in backend. There it looks better.',"extensions-leaflet-map");
  } else {
    $text=$text.'.';
  }
  $text=$text.'</p>'.'<h3>'.__('Note',"extensions-leaflet-map").'</h3>';
  $text=$text.'<p>'.sprintf(__('%s must be before %s.','extensions-leaflet-map'),
  '<code>leaflet-search</code>',
  '<code>leaflet-optiongroup, cluster</code>').'</p>';
  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
