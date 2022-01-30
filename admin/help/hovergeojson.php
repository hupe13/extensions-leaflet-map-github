<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_hover() {
$text='<h2 id="hovergeojson">Hovering</h2>
<img src="'.LEAFEXT_PLUGIN_PICTS.'hover.png" alt="hover">
<p>'.__('Use it to highlight a gpx, kml, geojson or marker and get a tooltip on mouse over.','extensions-leaflet-map').' '.
sprintf(
__('It works on %s and %s.','extensions-leaflet-map'),"leaflet-geojson, leaflet-gpx, leaflet-kml","leaflet-marker");
$text = $text. '</p>
<h2>Shortcode</h2>
<h4>'.__('Create Map','extensions-leaflet-map').'</h4>
<pre><code>[leaflet-map ...]</code></pre>
<h4>'.__('Load files with leaflet-geojson, leaflet-gpx, leaflet-kml or create markers with leaflet-marker','extensions-leaflet-map').'</h4>
<pre><code>[leaflet-geojson src="//url/to/file.geojson" color="..."]{name}[/leaflet-geojson]
//or / and
[leaflet-gpx src="//url/to/file.gpx" color="..."]{name}[/leaflet-gpx]
//or / and
[leaflet-kml src="//url/to/file.kml" color="..."]{name}[/leaflet-kml]
//or / and
[leaflet-marker ....]Marker ....[/leaflet-marker]</code></pre>
<h4>'.__('And hover','extensions-leaflet-map').'</h4>
<pre><code>[hover exclude="url-substring" tolerance=xx]</code></pre><p>
<h3>Parameters</h3>'.
__('Both parameters are optional.','extensions-leaflet-map').
'<ul style="list-style: disc;">
<li style="margin-left: 1.5em;"> <code>exclude</code>: '.sprintf(
__('This is a very special case. I would like to exclude some leaflet-geojson with a specific string in the src url from changing its style on hovering. If the url to the geojson file is e.g. %s, url-substring should be %s.','extensions-leaflet-map'), '"https://url/to/special.geojson"','"special"')
.'<li style="margin-left: 1.5em;"> <code>tolerance</code>: '.__('determines how much to extend click tolerance round a path/object on the map.','extensions-leaflet-map').'
</ul></p>';
if (is_singular() || is_archive() ) {
  return $text;
} else {
  echo $text;
}
}
//leafext_help_hover();
