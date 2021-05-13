<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

$text='<h2 id="leaflet.zoomhome">leaflet.zoomhome</h2>
<img src="'.LEAFEXT_PLUGIN_PICTS.'home.png"><p>
&quot;Home&quot; '.__('button to reset the view. A must for clustering markers','extensions-leaflet-map').'.</p>

<h2>Shortcode</h2>
<pre><code>[leaflet-map ....]
  ...
[zoomhomemap]
//or
[zoomhomemap !fit]
</code></pre>

<h2>Howto</h2>

<ul>
<li>
'.__('This Shortcode works only on one map per page. It could work on multiple maps per page, you can test it for your application.','extensions-leaflet-map').'
</li>

<li>
'.__('It zooms to markers (leaflet-marker), geojsons (leaflet-geojson, leaflet-gpx, leaflet-kml), lines (leaflet-line).','extensions-leaflet-map').'
</li>

<li>
'.__('If you use the <code>elevation</code> shortcode, please use at least one marker (e.g. starting point).','extensions-leaflet-map').'
<pre><code>[leaflet-map ....]
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file" ...]
</code></pre>
</li>

<li>'.
__('You can define wether <code>[zoomhomemap]</code> should zoom to all objects when calling the map.','extensions-leaflet-map').' '.
__('If you are using the <code>!fit</code> attribute, you have to define how the map should fit, e.g.','extensions-leaflet-map').'

<pre>
<code>[leaflet-map lat=... lng=... zoom=... !fitbounds]
[leaflet-marker ....]
[zoomhomemap !fit]</code>
</pre>

</li>

<li>'.__('You can also define to zoom at the first call to a geojson:','extensions-leaflet-map').'

<pre>
<code>[leaflet-map fitbounds]
[leaflet-geojson src="//url/to/file.geojson" fitbounds]Name[/leaflet-geojson]
[leaflet-marker lat=... lng=... ]Name 1[/leaflet-marker]
[leaflet-marker lat=... lng=... ]Name 2[/leaflet-marker]
...
[leaflet-marker lat=... lng=... ]Name n[/leaflet-marker]
[cluster]
[zoomhomemap]</code>
</pre>
</li>

<li>'.
__('There are certainly more examples. Test it yourself with the parameters <code>fitbounds</code> (leaflet-) or <code>fit</code> (zoomhomemap).','extensions-leaflet-map').'
</li>

</ul>
';

echo $text;
