<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_hover() {
  $text='<h2 id="hover">Hovering</h2>
  <img src="'.LEAFEXT_PLUGIN_PICTS.'hover.png" alt="hover">
  <p>'.sprintf(__('Use it to highlight a %s element and get a tooltip on mouse over.','extensions-leaflet-map'),
  "leaflet-*");
  $text = $text. '</p>';

  $text = $text. '<h2>Shortcode</h2>
  <h4>'.__('Create Map','extensions-leaflet-map').'</h4>
  <pre><code>&#091;leaflet-map ...]</code></pre>
  <h4>'.sprintf(__('Load any elements with any %s shortcode','extensions-leaflet-map'),
  'leaflet-*').'</h4>';
  $text = $text.'<ul>';
  $text = $text.'<li>';
  $text = $text.__('The tooltip content is the same as the popup content.','extensions-leaflet-map');
  $text = $text.'</li>';
  $text = $text.'<li>';
  $text = $text. sprintf(__('To customize the popup content for geojsons, see %sgeojson options%s.','extensions-leaflet-map'),
  '<a href="https://github.com/bozdoz/wp-plugin-leaflet-map#leaflet-geojson-options">','</a>');
  $text = $text.'</li>';
  $text = $text.'</ul>';

  $text = $text.'<pre><code>// any many
&#091;leaflet-marker ...]Marker ...&#091;/leaflet-marker]
&#091;leaflet-marker title=... ...]Marker ...&#091;/leaflet-marker]
&#091;leaflet-extramarker ...]Marker ...&#091;/leaflet-extramarker]
&#091;leaflet-extramarker title=... ...]Marker ...&#091;/leaflet-extramarker]
&#091;leaflet-geojson ...]{name}&#091;/leaflet-geojson]
&#091;leaflet-gpx ...]{name}&#091;/leaflet-gpx]
&#091;leaflet-kml ...]{name}&#091;/leaflet-kml]
&#091;leaflet-polygon ...]Polygon ...&#091;/leaflet-polygon]
&#091;leaflet-circle ...]Circle ...&#091;/leaflet-circle]
&#091;leaflet-line ...]Line ...&#091;/leaflet-line]</code></pre>';

$text=$text.'<h4>'.__('And hover','extensions-leaflet-map').'</h4>'
.'<pre><code>&#091;hover]</code></pre>'.
'<h3>'.__('Options','extensions-leaflet-map').'</h3>';

$text=$text.'<p>'.__("By default, all elements react to hover. If you don't want this, you have the following options.",'extensions-leaflet-map').'</p>';

$options=leafext_hover_params();
$new = array();
$new[] = array(
  'param' => "<strong>Option</strong>",
  'desc' => "<strong>".__('Description','extensions-leaflet-map').'</strong>',
  'default' => "<strong>".__('Default','extensions-leaflet-map').'</strong>',
  'values' => "<strong>".__('Values','extensions-leaflet-map').'</strong>',
);

foreach ($options as $option) {
  if ($option['param'] == "tolerance") {
    $default = shortcode_atts(array('tolerance' => 0), get_option( 'leafext_canvas' ))['tolerance'];
  } else {
    if ($option['default'] === true) {
      $default = 'true';
    } else if ($option['default'] === false) {
      $default = 'false';
    } else {
      $default = $option['default'];
    }
  }
  $new[] = array(
    'param' => $option['param'],
    'desc' => $option['desc'],
    'default' =>  $default,
    'values' => $option['values'],
  );
}
$text=$text.leafext_html_table($new);
$text=$text.'<p>'.
'<ul>'
.'<li>'.'<code>true</code> - '.__('Show tooltip and change style on hover.','extensions-leaflet-map').'</li>'
.'<li>'.'<code>false</code> - '.__('Nothing happens.','extensions-leaflet-map').'</li>'
.'<li>'.'<code>tooltip</code> - '.__('Show tooltip on hover.','extensions-leaflet-map').'</li>'
.'<li>'.'<code>style</code> - '.__('Change style on hover.','extensions-leaflet-map').'</li>'
.'</ul></p>';

$do_only = leafext_hover_params('only');
$do_element = leafext_hover_params('element');
$text=$text.'<p>'.sprintf(__("If you use one or multiple options from %s, then the options %s will be ignored. ",
'extensions-leaflet-map'),
str_replace(array('","','"'),array(', ',''),trim(json_encode(leafext_hover_params('only')),'[]')),
str_replace(array('","','"'),array(', ',''),trim(json_encode(leafext_hover_params('element')),'[]')))
.'</p>';

$text=$text.'<p>'.__('So can you write:','extensions-leaflet-map').
'<ul>'
.'<li>'.__('hover only for geojsons, gpx, kml:','extensions-leaflet-map').' <code>[hover geojsontooltip geojsonstyle]</code>'.'</li>'
.'<li>'.__('show tooltips on hover:','extensions-leaflet-map').' <code>[hover markertooltip geojsontooltip markergrouptooltip]</code>'.'</li>'
.'<li>'.__('change style but do not show tooltips on hover (geojson, gpx, kml, circle, polygon, line):','extensions-leaflet-map').' <code>[hover geojsonstyle markergroupstyle]</code>'.'</li>'
.'<li>'.__('show a short tooltip, if the popup is too big:','extensions-leaflet-map').' <code>[hover geojsontooltip="{name}"]</code>'.'</li>'
.'</ul></p>';

$text = $text.'<p>'.__('For boolean values applies', "extensions-leaflet-map").':<br>';
$text = $text.'<code>false</code> = <code>!parameter</code> || <code>parameter="0"</code> || <code>parameter=0</code><br>';
$text = $text.'<code>true</code> = <code>parameter</code> || <code>parameter="1"</code> || <code>parameter=1</code>';
$text = $text.'</p>';

$text = $text.'<h4>'.__('Tooltip className', "extensions-leaflet-map").'</h4>';
$text = $text.'<p>'.sprintf(__('The tooltip has a %s, its default is %s. You can style the tooltip with css, e.g.', "extensions-leaflet-map"),'className','"leafext-tooltip"');
$text = $text.'<pre>
.leafext-tooltip {
  background-color: #eee !important;
  border: 1px solid #eee !important;
  white-space: normal !important;
  width: 100px;
  height: 100px;
}
/* the triangle */
.leaflet-tooltip-left.leafext-tooltip::before {
  border-left-color: #eee;
}
.leaflet-tooltip-right.leafext-tooltip::before {
  border-right-color: #eee;
}
</pre>';
$text = $text.'</p>';

  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
//leafext_help_hover();
