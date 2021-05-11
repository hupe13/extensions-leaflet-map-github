<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

$text=$text.'<h4 id="hovergeojson">hovergeojson</h4>
<img src="'.LEAFEXT_PLUGIN_PICTS.'hover.png">
<p>'.__('Use it to highlight a geojson area or line on mouse over','extensions-leaflet-map').'.</p>
<pre><code>[leaflet-map ...]
[leaflet-geojson src="//url/to/file.geojson" color="..."]...[/leaflet-geojson]
//or / and
[leaflet-gpx src="//url/to/file.gpx" color="..."]...[/leaflet-gpx]
//or / and
[leaflet-kml src="//url/to/file.kml" color="..."]...[/leaflet-kml]
[hover]
</code></pre>';
