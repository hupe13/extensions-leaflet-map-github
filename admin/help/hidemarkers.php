<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

$text=$text.'<h3>Hide Markers</h3>
<p>'.__('If a GPX track contains waypoints that you do not want to display','extensions-leaflet-map').'.</p>
<pre><code>[leaflet-map ...]
[leaflet-gpx src="//url/to/file.gpx" ... ]
[hidemarkers]
</code></pre>';
