<?php
$text=$text.'<h4 id="leaflet.markercluster">Leaflet.markercluster</h4>
<img src="'.$leafext_picts.'cluster.png">
<p>'.__('Many markers on a map become confusing. That is why they are clustered','extensions-leaflet-map').'.</p>'.__('
You can define some options in <a href="admin.php?page=extensions-leaflet-map&tab=cluster">Settings</a> or per map.'
,'extensions-leaflet-map').
'<pre><code>[leaflet-map ....]
// many markers
[leaflet-marker lat=... lng=... ...]poi1[/leaflet-marker]
[leaflet-marker lat=... lng=... ...]poi2[/leaflet-marker]
 ...
[leaflet-marker lat=... lng=... ...]poixx[/leaflet-marker]
[cluster]
// or
[cluster radius="..." zoom="..." spiderfy=0]
[zoomhomemap]
</code></pre>';
