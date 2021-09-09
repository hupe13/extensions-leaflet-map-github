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
 '.sprintf(
__('You can change this with the attribute %s.','extensions-leaflet-map'),"<code>fit</code> / <code>!fit</code>").' '.
sprintf(__('If you are using %s, you have to define how the map should fit, e.g.','extensions-leaflet-map'),"<code>!fit</code>").'

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

<li>'.sprintf(
__('There are certainly more examples. Test it yourself with the parameters %s or %s.','extensions-leaflet-map'),"<code>fitbounds</code> (leaflet-)","<code>fit</code> (zoomhomemap)").'
</li>
</ul>
<h3>Elevation Profile</h3>
<ul>
<li>'.
sprintf(__('If you use the %s shortcode, please use at least one marker (e.g. starting point).','extensions-leaflet-map'),"<code>elevation</code>").'
<pre><code>[leaflet-map ....]
[leaflet-marker lat=... lng=... ...]Start[/leaflet-marker]
[elevation gpx="url_gpx_file" ...]
</code></pre>
</li>

</ul>
';

echo $text;
