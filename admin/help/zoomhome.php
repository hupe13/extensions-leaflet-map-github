<?php
$text=$text.'<h4 id="leaflet.zoomhome">leaflet.zoomhome</h4>
<img src="'.$leafext_picts.'home.png"><p>
&quot;Home&quot; '.__('button to reset the view. A must for clustering markers','extensions-leaflet-map').'.</p><p>'.
__('You can define wether zoomhomemap should zoom to all objects when calling the map. But this is valid for synchron loaded objects like markers only.
For asynchron loaded object, like geojsons, use the leaflet-map attribute fitbounds. If you use the elevation shortcode,
please use at least one marker (e.g. starting point).','extensions-leaflet-map').'</p>
<pre>
<code>[leaflet-map lat=... lng=... zoom=... !fitbounds !zoomcontrol]
[leaflet-marker ....]
[zoomhomemap !fit]</code>
</pre>'.__('or','extensions-leaflet-map').'
<pre><code>[leaflet-map !zoomcontrol ....]
  ...
[zoomhomemap]
</code></pre>';
