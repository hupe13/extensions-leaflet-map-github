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
  <pre><code>[leaflet-map ...]</code></pre>
  <h4>'.sprintf(__('Load an element with any %s shortcode','extensions-leaflet-map'),
  'leaflet-*').'</h4>';
  $text = $text.'<ul>';
  $text = $text.'<li>';
  $text = $text.sprintf(__('The tooltip content is the same as the popup content and/or the content of %s option.','extensions-leaflet-map'),
  '<em>title</em>');
  $text = $text.'</li>';
  $text = $text.'<li>';
  $text = $text.sprintf(__('The %s option is valid for %s only and optional.','extensions-leaflet-map'),
  '<em>title</em>','<code>leaflet-marker</code>');
  $text = $text.'</li>';
  $text = $text.'<li>';
  $text = $text. sprintf(__('To customize the popup content for geojsons see %sgeojson options%s.','extensions-leaflet-map'),
  '<a href="https://github.com/bozdoz/wp-plugin-leaflet-map#leaflet-geojson-options">','</a>');
  $text = $text.'</li>';
  $text = $text.'</ul>';

  $text = $text.'<pre><code>// one or more of these
[leaflet-geojson ...]{name}[/leaflet-geojson]
[leaflet-gpx ...]{name}[/leaflet-gpx]
[leaflet-kml ...]{name}[/leaflet-kml]
[leaflet-marker title="..." ...]Marker ...[/leaflet-marker]
[leaflet-polygon ...]Polygon ...[/leaflet-polygon]
[leaflet-circle ...]Circle ...[/leaflet-circle]
[leaflet-line ...]Line ...[/leaflet-line]</code></pre>';

$text=$text.'<h4>'.__('And hover','extensions-leaflet-map').'</h4>'
  .'<pre><code>[hover exclude="url-substring" tolerance=xx]</code></pre>'.
  '<h3>'.__('Parameters','extensions-leaflet-map').'</h3><p>'.
  __('Both parameters are optional.','extensions-leaflet-map').
  '<ul><li> <code>exclude</code>: '.sprintf(
  __('This is a very special case. I would like to exclude some leaflet-geojson with a specific string in the src url from changing its style on hovering. If the url to the geojson file is e.g. %s, url-substring should be %s.','extensions-leaflet-map'), '"https://url/to/special.geojson"','"special"')
    .'</li>'
    .'<li> <code>tolerance</code>: '.__('determines how much to extend click tolerance round a path/object on the map.','extensions-leaflet-map').'</li>'.
    '</ul></p>';
  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
//leafext_help_hover();
