<?php
$text=$text.'<h4 id="display-a-track-with-elevation-profile">'.__('Display a track with elevation profile','extensions-leaflet-map').'</h4>
<img src="'.$leafext_picts.'elevation.png">
<p>'.
__('You may go to','extensions-leaflet-map').' <a href="admin.php?page=extensions-leaflet-map&tab=elevation">'.__('Settings','extensions-leaflet-map').'</a> '.__('and select a color theme','extensions-leaflet-map').'.</p>
<pre><code>[leaflet-map ....]
// at least one marker if you use it with zoomehomemap
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file"]
// or
[elevation gpx="url_gpx_file" summary=1]
</code></pre>';
