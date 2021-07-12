<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

$text = '
<img src="'.LEAFEXT_PLUGIN_PICTS.'elevation.png">
<h2>Shortcode</h2>
<pre><code>[leaflet-map ....]
// at least one marker if you use it with zoomehomemap
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file" option=option ...]
</code></pre>'.
__("For options see the Elevation Charts Options tab.",'extensions-leaflet-map');

echo $text;
