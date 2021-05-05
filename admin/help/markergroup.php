<?php
$text=$text.'<h4 id="leaflet.featuregroup.subgroup">Leaflet.FeatureGroup.SubGroup</h4>
<img src="'.$leafext_picts.'clustergroup.png"><p>'.
__('dynamically add/remove groups of markers from Marker Cluster','extensions-leaflet-map').
'. Parameter:</p>
<ul>
<li>feat - '.__('possible meaningful values','extensions-leaflet-map').': iconUrl, title, ('.__('other','extensions-leaflet-map').'???)</li>
<li>strings - '.
__('comma separated strings to distinguish the markers, e.g. an unique string in iconUrl or title',
'extensions-leaflet-map').'</li>
<li>groups - '.
__('comma separated labels appear in the selection menu','extensions-leaflet-map').'</li>
<li>'.
__('The number of strings and groups must match','extensions-leaflet-map').'
.</li></ul>
<pre><code>[leaflet-marker title="..." iconUrl="...red..." ... ] ... [/leaflet-marker]
[leaflet-marker title="..." iconUrl="...green..." ... ] ... [/leaflet-marker]
//many markers
[markerClusterGroup feat="iconUrl" strings="red,green" groups="rot,gruen"]
</code></pre>
<p>'.__('Here the groups are differentiated according to the color of the markers','extensions-leaflet-map').'.</p>';
