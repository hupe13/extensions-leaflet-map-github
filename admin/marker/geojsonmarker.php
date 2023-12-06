<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_geojsonmarker() {

  if (is_singular() || is_archive() ) {
    if (strpos($_SERVER["REQUEST_URI"], "/en/") !==  false) {
      $lang = '/en';
    } else {
      $lang = '';
    }
    $featuregroup = $lang."/doku/featuregroup/";
    $cluster = $lang."/doku/markercluster/";
    $extramarker = $lang."/doku/extramarker/";
  } else {
    $featuregroup = '?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=featuregroup';
    $cluster = '?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=markercluster';
    $extramarker = '?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=extramarker';
  }

  $text='<h3>'.__('Design and group markers from geojson files according to their properties',
	'extensions-leaflet-map').'</h3>';
  $text=$text.'<p>'.
	sprintf(__('A %1$s in a geojson file is specified like this:',
	'extensions-leaflet-map'),
	'"Point"').
'<pre>{
  "type": "Feature",
  "geometry": {
    "type": "Point",
    "coordinates": [lat, lng]
  },
  "properties": {
    "<span style="color: #d63638">property</span>": "value"
    ....
  }
}</pre>
</p>';

	$text=$text.'<h3>Shortcode</h3>

<pre><code>&#091;geojsonmarker property=<span style="color: #d63638">property</span> values=... iconprops=... icondefault=<span style="color: #4f94d4">blue</span> groups=... visible=... <i>cluster-options</i>]</code></pre>

<pre><code>&#091;geojsonmarker property=<span style="color: #d63638">property</span> auto <i>cluster-options</i>]</code></pre>

<h3>circleMarker</h3>'
.sprintf(__('If you want to use CircleMarker you must specify %1$s and optional %2$s and %3$s in %4$s shortcode.','extensions-leaflet-map'),
'<code>circlemarker</code>','<code>color</code>','<code>radius</code>','<code>leaflet-geojson</code>')
.'<pre><code>&#091;leaflet-geojson src="//url/to/file.geojson" circleMarker color=<span style="color: #4f94d4">blue</span> radius=...]
&#091;geojsonmarker property=<span style="color: #d63638">property</span> icondefault=<span style="color: #4f94d4">blue</span> ...]</code></pre>

<h3>'.__('Marker with icon','extensions-leaflet-map').'</h3>'.

sprintf(__('The markers should have the same %1$s, only %2$s differs.','extensions-leaflet-map'),
'<code>iconsize</code>, <code>iconanchor</code>, <code>popupanchor</code>, <code>tooltipanchor</code>, <code>shadowurl</code>, <code>shadowsize</code>, <code>shadowanchor</code>',
'<code>iconurl</code>').' '.
sprintf(__('The substring %1$s will be replaced in the path of the %2$s with any of %3$s.','extensions-leaflet-map'),
'<code>icondefault</code>','<code>iconurl</code>','<code>iconprops</code>')
.'<pre><code>&#091;leaflet-geojson src="//url/to/file.geojson" iconurl="//url/to/marker-icon-<span style="color: #4f94d4">blue</span>.png" iconsize=... iconanchor="..,.." popupanchor="..,.."  tooltipanchor="..,.." shadowurl="//url/to/marker-shadow.png" shadowsize=... shadowanchor=...]
&#091;geojsonmarker property=<span style="color: #d63638">property</span> values=... iconprops=... icondefault=<span style="color: #4f94d4">blue</span> ...]</code></pre>

<h3>Extramarker</h3>'.
sprintf(__('If you want to use ExtraMarkers you can specify %1$s options.','extensions-leaflet-map'),
'<a href="'.$extramarker.'">leaflet-extramarker</a>').' '.

sprintf(__('For now, %1$s are mapped to %2$s. Maybe there are other options.','extensions-leaflet-map'),
'<code>values</code>','<code>markerColor</code>')

.'<pre><code>&#091;leaflet-map fitbounds ...]
&#091;leaflet-geojson src="//url/to/file.geojson"]
&#091;geojsonmarker property=<span style="color: #d63638">property</span> icondefault=<span style="color: #4f94d4">blue</span> markerColor=<span style="color: #4f94d4">blue</span> <i>extramarker-options</i>] ...</code></pre>


<h3>Options</h3>

<ul>
<li><code>property</code> - '.__('required','extensions-leaflet-map').'</li>

<li><code>values</code>
<ul>
<li> '.__('comma separated strings of property values','extensions-leaflet-map').'</li>
<li> '.__('if not specified collect values from property','extensions-leaflet-map').'</li>
<li> '.sprintf(__('required for markers with %1$s','extensions-leaflet-map'),'<code>iconurl</code>').'</li>
<li> '.sprintf(__('required if you want to group like with %1$s','extensions-leaflet-map'),
'<a href="'.$featuregroup.'"><code>leaflet-featuregroup</code></a>').'</li>
</ul></li>

<li><code>iconprops</code>
<ul>
<li> '.sprintf(__('comma separated colors for marker or substrings in %1$s for marker to distinguish the values','extensions-leaflet-map'),'<code>iconurl</code>').'</li>
<li> '.sprintf(__('required for markers with %1$s','extensions-leaflet-map'),'<code>iconurl</code>').'</li>
<li> '.__('if not specified colors are generated','extensions-leaflet-map').' '.
sprintf(__('(max 16 %1$s for %2$s, 14 for %3$s)','extensions-leaflet-map'),'<code>values</code>','circleMarker','ExtraMarker').'</li>
<li> '.sprintf(__('if specified the count must match the count of %1$s','extensions-leaflet-map'),'<code>values</code>').'</li>
</ul></li>

<li><code>icondefault</code> - '.sprintf(__('default color','extensions-leaflet-map'),).
' (<span style="color: #4f94d4">blue</span>), '
.__('resp. substring of ','extensions-leaflet-map').'<code>iconurl</code></li>

<li><code>groups</code> - '.sprintf(__('required if you want to group like with %1$s','extensions-leaflet-map'),
'<a href="'.$featuregroup.'"><code>leaflet-featuregroup</code></a>').'</li>

<li> <code>visible</code> - '.__('for grouping','extensions-leaflet-map').'</li>

<li> <code>auto</code> <ul>
<li> '.sprintf(__('automatic generation of %1$s, %2$s (color) and %3$s','extensions-leaflet-map'),
'<code>values</code>','<code>iconprops</code>','<code>groups</code>').'</li>
<li>'.__('not for markers with iconUrl','extensions-leaflet-map').'</li>
<li>'.sprintf(__('no more than 16 different %1$s for %2$s','extensions-leaflet-map'),'<code>values</code>','circleMarker').'</li>
<li>'.sprintf(__('no more than 14 different %1$s for %2$s','extensions-leaflet-map'),'<code>values</code>','ExtraMarker').'</li>
</ul></li>


<li>'.sprintf(__('The markers are clustered. Optional you can specify options from %1$s.','extensions-leaflet-map'),
'<a href="'.$cluster.'"><code>cluster</code></a>').'</li>

<li>'.
__('optional: <code>position</code> - position of group control','extensions-leaflet-map').': topleft, topright, bottomleft or bottomright.
</li>
<li>'.
__('optional: <code>collapsed</code> - group control collapsed or not:','extensions-leaflet-map').' true or false.
</li>

</ul>';

  if (is_singular() || is_archive() ) {
    return $text;
  } else {
    echo $text;
  }
}
