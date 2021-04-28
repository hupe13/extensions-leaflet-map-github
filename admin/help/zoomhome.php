<?php
$text=$text.'<h4 id="leaflet.zoomhome">leaflet.zoomhome</h4>
<img src="'.$leafext_picts.'home.png"><p>
&quot;Home&quot; '.__('button to reset the view. A must for clustering markers','extensions-leaflet-map').'. '.
__('You can define wether zoomhomemap should zoom to all objects when calling the map.','extensions-leaflet-map').'</p>
<pre>
<code>[leaflet-map lat=... lng=... zoom=... !fitbounds !zoomcontrol]
[leaflet-marker ....]
[zoomhomemap !fit]</code>
</pre>'.__('or','extensions-leaflet-map').'
<pre><code>[leaflet-map !zoomcontrol ....]
  ...
[zoomhomemap]
</code></pre>';
