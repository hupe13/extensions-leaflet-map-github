<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

$text=$text.'<h4 id="hide-markers">Hide Markers</h4>
<p>'.__('If a GPX track contains waypoints that you do not want to display','extensions-leaflet-map').'.</p>
<pre><code>[leaflet-map ...]
[leaflet-gpx src="//url/to/file.gpx" ... ]
[hidemarkers]
</code></pre>';
