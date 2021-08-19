<?php

	echo '
	<h2 id="leaflet.featuregroup.subgroup">Leaflet.FeatureGroup.SubGroup</h2>
	<img src="'.LEAFEXT_PLUGIN_PICTS.'clustergroup.png">
	
<p>'.
__('dynamically add/remove groups of markers from Marker Cluster','extensions-leaflet-map').
'.</p><h3>Options and Shortcode</h3>
<ul>
<li>feat - '.__('possible meaningful values','extensions-leaflet-map').': iconUrl, title</li>
<li>strings - '.
__('comma separated strings to distinguish the markers, e.g. an unique string in iconUrl or title',
'extensions-leaflet-map').'</li>
<li>groups - '.
__('comma separated labels appear in the selection menu','extensions-leaflet-map').'</li>
<li>'.
__('The number of strings and groups must match.','extensions-leaflet-map').'
</li></ul>
<pre><code>[leaflet-marker iconUrl="...red..." ... ] ... [/leaflet-marker]
[leaflet-marker iconUrl="...green..." ... ] ... [/leaflet-marker]
//many markers
[markerClusterGroup feat="iconUrl" strings="red,green" groups="rot,gruen"]
</code></pre>
'.__('or','extensions-leaflet-map').'
<pre><code>[leaflet-marker title="first ..."  ... ] ... [/leaflet-marker]
[leaflet-marker title="second ..." ... ] ... [/leaflet-marker]
//many markers
[markerClusterGroup feat="title" strings="first,second" groups="First Group,Second Group"]
</code></pre>
';
