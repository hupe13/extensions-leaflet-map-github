<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

$text='<h2 id="leaflet.zoomhome">leaflet.zoomhome</h2>
<img src="'.LEAFEXT_PLUGIN_PICTS.'home.png"><p>
&quot;Home&quot; '.__('button to reset the view. A must for clustering markers','extensions-leaflet-map').'.</p>
'.__('It resets the view to all markers (leaflet-marker), lines (leaflet-line), circles (leaflet-circle), geojsons (leaflet-geojson, leaflet-gpx, leaflet-kml) and a track (elevation).','extensions-leaflet-map').'

<h2>Shortcode</h2>
<pre><code>[leaflet-map ....]
  ...
[zoomhomemap]
//or
[zoomhomemap !fit]
</code></pre>

<h2>Howto</h2>

<h3>fit / !fit</h3>

<ul>
<li>
'.__('When the map is loaded, it zooms to all objects by default.','extensions-leaflet-map').'
 '.
__('You can change this with the attribute <code>fit</code> / <code>!fit</code>.','extensions-leaflet-map').' '.
__('If you are using <code>!fit</code>, you have to define how the map should fit, e.g.','extensions-leaflet-map').'

<pre>
<code>[leaflet-map lat=... lng=... zoom=... <span style="color: #d63638">!fitbounds</span>]
[leaflet-marker ....]
...
[zoomhomemap <span style="color: #d63638">!fit]</span></code>
</pre>

</li>

<li>'.__('You can also define to zoom at the first call to a geojson:','extensions-leaflet-map').'

<pre>
<code>[leaflet-map <span style="color: #d63638">!fitbounds</span>]
[leaflet-geojson src="//url/to/file.geojson" <span style="color: #d63638">fitbounds</span>]Name[/leaflet-geojson]
[leaflet-marker lat=... lng=... ]Name 1[/leaflet-marker]
[leaflet-marker lat=... lng=... ]Name 2[/leaflet-marker]
...
[leaflet-marker lat=... lng=... ]Name n[/leaflet-marker]
[cluster]
[zoomhomemap <span style="color: #d63638">!fit]</span></code>
</pre>
</li>

<li>'.
__('There are certainly more examples. Test it yourself with the parameters <code>fitbounds</code> (leaflet-) or <code>fit</code> (zoomhomemap).','extensions-leaflet-map').'
</li>
</ul>
<h3>Elevation Profile</h3>
<ul>
<li>
'.__('If you use the <code>elevation</code> shortcode, please use at least one marker (e.g. starting point).','extensions-leaflet-map').'
<pre><code>[leaflet-map ....]
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file" ...]
</code></pre>
</li>

</ul>
';

echo $text;
