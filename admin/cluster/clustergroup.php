<?php
/**
 * Help for Leaflet.FeatureGroup.SubGroup
 * extensions-leaflet-map
 */
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_clustergroup_help_text () {
	$firsttext = '
	<h2 id="leaflet.featuregroup.subgroup">Leaflet.FeatureGroup.SubGroup</h2>
	<img src="'.LEAFEXT_PLUGIN_PICTS.'clustergroup.png">
	<p>'.
	__('Dynamically add/remove groups of markers from Marker Cluster','extensions-leaflet-map').
	'.</p>';
	$text='<h3>'.__('Options for grouping markers','extensions-leaflet-map').'</h3>'.
	'<code>[markerClusterGroup feat="..." strings="..., ..." groups="..., ..." visible=...]</code>'.
	'<ul style="list-style: disc;">
	<li style="margin-left: 1.5em;"><code>feat</code> - '.
	__('distinguishing feature','extensions-leaflet-map').'. '.
	sprintf(__('You can use any meaningful option from %s or %s for %s, for example: %s','extensions-leaflet-map'),
	'<code>[leaflet-marker]</code>',
	'<code>[leaflet-extramarker]</code>',
	'<code>feat</code>',
	'<code>iconUrl</code>, <code>title</code>, <code>markerColor</code>')
	.', ... </li>
		<li style="margin-left: 1.5em;"><code>strings</code> - '.sprintf(
			__('comma separated strings to distinguish the markers, that means an unique string in %s',
			'extensions-leaflet-map'),"<code>feat</code>").'</li>
			<li style="margin-left: 1.5em;"><code>groups</code> - '.
			__('comma separated labels appear in the selection menu','extensions-leaflet-map').'</li>
		<li style="margin-left: 1.5em;"><code>visible</code> ('.__('optional','extensions-leaflet-map').') - '.
		sprintf(__('initial visibility of a group, default: %s. Either %s (valid for all groups) or a comma-separated list of %s and %s, where the number must match those of %s.',
		'extensions-leaflet-map'),
		'1',
		'0',
		'0',
		'1',
		'<code>groups</code>',
		).'</li>
		<li style="margin-left: 1.5em;">'.sprintf(
			__('The number of %s and %s must match.','extensions-leaflet-map'),"<code>strings</code>","<code>groups</code>").'
			</li></ul>
			<h3>'.__('Examples for grouping markers','extensions-leaflet-map').'</h3>
			<pre><code>[leaflet-marker iconUrl="...red..." ... ] ... [/leaflet-marker]
[leaflet-marker iconUrl="...green..." ... ] ... [/leaflet-marker]
//many markers
[markerClusterGroup feat=iconUrl strings="red,green" groups="rot,gruen"]</code></pre>'
			.__('or','extensions-leaflet-map').
			'<pre><code>[leaflet-extramarker ... markerColor=red] ... [/leaflet-extramarker]
[leaflet-extramarker ... markerColor=green] ... [/leaflet-extramarker]
//many markers
[markerClusterGroup feat=markerColor strings="red,green" groups="First Group,Second Group"]</code></pre>
			<h3>'.__('Options for grouping markers (points) in leaflet-geojson','extensions-leaflet-map').'</h3>
			<ul style="list-style: disc;">
			<li style="margin-left: 1.5em;"><code>feat</code> - '.
			__('distinguishing feature','extensions-leaflet-map').'. '
			.__('possible meaningful values','extensions-leaflet-map').': <code>iconUrl</code>, <code>properties.<i>property</i></code></li>
			<li style="margin-left: 1.5em;"><code>properties.<i>property</i></code> - '.
			__('is the name of a property in properties of the Point in the FeatureCollection','extensions-leaflet-map').
			', e.g. <code>properties.<span style="color: #d63638"><i>prop0</i></span></code> in
			<pre>{
       "type": "FeatureCollection",
       "features": [{
           "type": "Feature",
           "geometry": {
               "type": "Point",
               "coordinates": [102.0, 0.5]
           },
           "properties": {
               "<span style="color: #d63638">prop0</span>": "<span style="color: #4f94d4">value0</span>"
           }
       },...</pre>
			 '
			.'</li>
			<li style="margin-left: 1.5em;"><code>strings</code> - '.sprintf(
				__('comma separated strings to distinguish the markers, e.g. an unique substring in %s or the exact string (e.g.%s) in %s',
				'extensions-leaflet-map'),"<code>iconUrl</code>",
				'<code><span style="color: #4f94d4">value0</span></code>',
				"<code>properties.<i>property</i></code>").'</li>
				<li style="margin-left: 1.5em;"><code>groups</code> - '.
				__('comma separated labels appear in the selection menu','extensions-leaflet-map').'</li>

				<li style="margin-left: 1.5em;"><code>visible</code> ('.__('optional','extensions-leaflet-map').') - '.
				__('see options for grouping markers above','extensions-leaflet-map')
				.'</li>

				<li style="margin-left: 1.5em;">'.sprintf(
					__('The number of %s and %s must match.','extensions-leaflet-map'),"<code>strings</code>","<code>groups</code>").'
					</li></ul>

					<h3>'.__('Shortcode for grouping markers (points) in leaflet-geojson','extensions-leaflet-map').'</h3>
		<pre><code>[leaflet-geojson src="..." iconUrl="...red..." ... ] ... [/leaflet-geojson]
[leaflet-geojson src="..." iconUrl="...green..." ... ] ... [/leaflet-geojson]
//any more leaflet-geojson
[markerClusterGroup feat=iconUrl strings="red,green" groups="rot,gruen"]</code></pre>'
					.__('or','extensions-leaflet-map').
	'<pre>
<code>[leaflet-geojson src="..."  ... ] ... [/leaflet-geojson]
//any more leaflet-geojson
[markerClusterGroup feat="properties.<span style="color: #d63638">prop0</span>" strings="<span style="color: #4f94d4">value0</span>,..." groups="Description0,..."]</code>
</pre>
<h3>groups unknown '.__('and','extensions-leaflet-map').' others</h3><p>'.
sprintf(
__('If %s contains %s and %s, then markers (respectively Points) for which the property specified in %s does not apply, are placed in the %s group. Markers (respectively Points) whose property is not known are placed in the %s group. See also the developer console.','extensions-leaflet-map'),
			"<code>groups</code>",
			'"unknown"',
			'"others"',
			'<code>feat</code>',
			"others",
			"unknown"
			).
			'</p><h3>Shortcode groups unknown '.__('and','extensions-leaflet-map').' others</h3>
<pre><code>[markerClusterGroup feat="..." strings="...,...,others,unknown" groups="...,...,Other properties,Unknown properties"]</code></pre>';

	$textoptions = '<p>'.sprintf ( __('The parameter and settings for %s are valid too.','extensions-leaflet-map'),
			'<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=markercluster">Leaflet.markercluster</a>');
	$textoptions = $textoptions.'</p>';
	if (is_singular() || is_archive() ) {
		return $text;
	} else {
		echo $firsttext.$text.$textoptions;
	}
}
leafext_clustergroup_help_text ();
