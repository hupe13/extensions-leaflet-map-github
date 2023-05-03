<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_geojsonmarker() {
  $text='<h3>Design markers from geojson files according to their properties</h3>';
  $text=$text.'<p>'.
	sprintf(__('A %s in a geojson file is specified like this:',
	'extensions-leaflet-map'),
	'"Point"').
'<pre>{
  "type": "Feature",
  "geometry": {
    "type": "Point",
    "coordinates": [lat, lng]
  },
  "properties": {
    "<span style="color: #d63638">color</span>": "<span style="color: #4f94d4">valid color</span>"
    ....
  }
}</pre>
</p>';

	$text=$text.'<h3>Shortcode</h3>

<pre><code>[geojsonmarker property=<span style="color: #d63638">value</span> default=<span style="color: #4f94d4">blue</span> <i>cluster-options</i> values=... groups=... visible=...]</code></pre>

<ul><li><code>property</code> - '.sprintf(__('default:','extensions-leaflet-map'),).' <span style="color: #d63638">color</span></li>
<li><code>default</code> - '.sprintf(__('default:','extensions-leaflet-map'),).' <span style="color: #4f94d4">blue</span></li>
<li>'.sprintf(__('The markers were clustered. Optional you can specify options from %s','extensions-leaflet-map'),
'<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=markercluster"><code>cluster</code></a>').'</li>
<li>'.sprintf(__('If you specify %s from %s, the markers are grouped.','extensions-leaflet-map'),
'<code>values</code>, <code>groups</code>, <code>visible</code>',
'<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=featuregroup"><code>leaflet-featuregroup</code></a>').'</li>
</ul>
  <h3>circleMarker</h3>'.
  sprintf(__('%s is the only %s for circle markers.','extensions-leaflet-map'),'<code>color</code>','<code>property</code>')
  .'<pre><code>[leaflet-map fitbounds ...]
[leaflet-geojson src="//url/to/file.geojson" circleMarker color=blue]
[geojsonmarker property=<span style="color: #d63638">color</span> default=blue]
[zoomhomemap]</code></pre>

<h3>Marker with icon</h3>'.
sprintf(__('Here you can play with %s. Make sure that the %s must appear in the path of the %s.','extensions-leaflet-map'),
'<code>property</code>','<code>value</code>','<code>iconurl</code>').'<br> '
.sprintf(__('The markers should have the same %s, only %s differs.','extensions-leaflet-map'),
'<code>iconsize</code>, <code>iconanchor</code>, <code>shadowurl</code>, <code>shadowsize</code>, <code>shadowanchor</code>, <code>popupanchor</code>, <code>tooltipanchor</code>',
'<code>iconurl</code>')
.'<pre><code>[leaflet-map fitbounds ...]
[leaflet-geojson src="//url/to/file.geojson" iconurl="//url/to/marker-icon-blue.png" shadowurl="//url/to/marker-shadow.png" iconanchor="12,41" popupanchor="1,-34"  tooltipanchor="16,-28"]
[geojsonmarker property=<span style="color: #d63638">value</span> default=blue]
[zoomhomemap]</code></pre>

<h3>Extramarker</h3>'.
sprintf(__('For now, %s is mapped to %s from %s. Maybe there are other options.','extensions-leaflet-map'),
'<code>property</code>','<code>markerColor</code>','<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=extramarker">leaflet-extramarker</a>')
.'<pre><code>[leaflet-map fitbounds ...]
[leaflet-geojson src="//url/to/file.geojson"]
[geojsonmarker property=<span style="color: #d63638">color</span> default=blue markerColor=blue <i>extramarker-options</i>]
[zoomhomemap]</code></pre>';

  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
