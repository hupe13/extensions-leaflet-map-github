<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_hoverlap() {
  if (is_singular() || is_archive() ) {
    $text='';
  } else {
    $text='<h2 id="hover">'.__('Popups and highlighting overlapping elements','extensions-leaflet-map').'</h2>';
  }

  $text = $text. '<h2>Shortcode</h2>
  <h4>'.__('Create Map','extensions-leaflet-map').'</h4>
  <pre><code>[leaflet-map ...]</code></pre>
  <h4>'.sprintf(__('Load any elements with any %s shortcode (except marker).','extensions-leaflet-map'),
  'leaflet-*').'</h4>';

  $text = $text.'<pre><code>// any many
[leaflet-geojson ...]{name}[/leaflet-geojson]
[leaflet-gpx ...]{name}[/leaflet-gpx]
[leaflet-kml ...]{name}[/leaflet-kml]
[leaflet-polygon ...]Polygon ...[/leaflet-polygon]
[leaflet-circle ...]Circle ...[/leaflet-circle]
[leaflet-line ...]Line ...[/leaflet-line]</code></pre>';

$text=$text.'<h4>'.__('And hoverlap','extensions-leaflet-map').'</h4>'
.'<pre><code>[hoverlap]</code></pre>'.
'<h3>'.__('Options','extensions-leaflet-map').'</h3>';

$text=$text.'<p>'.sprintf(__('The only option is %s, see the %s shortcode.','extensions-leaflet-map'),
'<code>exclude</code>','<code>hover</code>');
$text=$text.' '.sprintf(__('For overlapping markers see %s or %s.','extensions-leaflet-map'),
'<code>[cluster]</code>','<a href="https://leafext.de/extra/spiderfier/">Overlapping Marker Spiderfier for Leaflet</a>')
.'</p>';

$text=$text.
'<h3>Development</h3><p>'.
__('All this is not ready yet.','extensions-leaflet-map').
'<ul><li>'.
__('How to deal with the sometimes large contents of popups?','extensions-leaflet-map').
'<li>'.
__('Geojson GeometryCollections do not (yet) work in all senses.','extensions-leaflet-map').
'<li>'.
__('and maybe other things.','extensions-leaflet-map').
'</ul>'.
__('I am glad about (code-)suggestions.','extensions-leaflet-map').
'</p>';

if (is_singular() || is_archive() ) {
  return $text;
} else {
  echo $text;
}
}
//leafext_help_hoverlap();
