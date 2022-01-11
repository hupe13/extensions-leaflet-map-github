<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

$text='<h2 id="hovergeojson">hovering</h2>
<img src="'.LEAFEXT_PLUGIN_PICTS.'hover.png">
<p>'.__('Use it to highlight a gpx, kml, geojson or marker and get a tooltip on mouse over.','extensions-leaflet-map').' '.
sprintf(
__('It works on %s and %s.','extensions-leaflet-map'),"leaflet-geojson, leaflet-gpx, leaflet-kml","leaflet-marker");
$text = $text. '</p>
<h2>Shortcode</h2>
<pre><code>[leaflet-map ...]
[leaflet-geojson src="//url/to/file.geojson" color="..."]{name}[/leaflet-geojson]
//or / and
[leaflet-gpx src="//url/to/file.gpx" color="..."]{name}[/leaflet-gpx]
//or / and
[leaflet-kml src="//url/to/file.kml" color="..."]{name}[/leaflet-kml]
//or / and
[leaflet-marker ....]Marker ....[/leaflet-marker]

[hover]
//or
[hover exclude="url-substring"]
</code></pre>'.
sprintf(
__('The parameter %s is a very special case. I would like to exclude some leaflet-geojson with a specific string in the src url from changing its style on hovering. If the url to the geojson file is e.g. %s, url-substring should be %s.','extensions-leaflet-map'), "<code>exclude</code>", '"//url/to/special.geojson"','"special"');

echo $text;
