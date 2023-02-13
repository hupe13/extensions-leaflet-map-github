<?php
function leafext_leafletsearch_help(){
  if (is_singular() || is_archive() ) {
    $text = '';
  } else {
    $text = '<h2>Leaflet Control Search</h2>';
  }

  $text=$text.'<p>'.__('A control for search Markers/Features location by custom property.',"extensions-leaflet-map").'</p>';

  $text=$text.'<h3>Shortcode</h3>
  <pre>
  [leaflet-map fitbounds]
  //any many
  [leaflet-marker      ...] ... [/leaflet-marker]
  [leaflet-extramarker ...] ... [/leaflet-extramarker]
  [leaflet-geojson     ...] ... [/leaflet-geojson]
  //one or more
  [leaflet-search propertyName="..." ...]
  </pre>
  ';

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
  $text=$text.'<div style="width:80%;">'.leafext_html_table($new).'</div>';

  $text=$text.'<h4>'.__('Examples for',"extensions-leaflet-map").' marker:</h4>';
  $text=$text.'<pre><code>marker="{icon: true}"
marker="{icon: false, animate: true, circle: {radius: 10, weight: 3, color: '."'#e03'".', stroke: true, fill: false}}"
marker="L.circleMarker([0,0],{radius:30})"
marker="{icon: new L.Icon({iconUrl:'."'data/custom-icon.png'".', iconSize: [20,20]}), circle: {radius: 20,color: '."'#0a0'".', opacity: 1}}"
</code></pre>';
$text=$text.'<h4>'.__('Note',"extensions-leaflet-map").'</h4>';
  $text=$text.'<p>'.sprintf(__('%s must be before %s.','extensions-leaflet-map'),
  '<code>leaflet-search</code>',
  '<code>leaflet-optiongroup, cluster</code>').'</p>';
  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
