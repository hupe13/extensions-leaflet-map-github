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
	$firsttext=$firsttext.'<h3>'.__('Note','extensions-leaflet-map').'</h3>'.
	'<p>'.sprintf(__('The shortcode %s is a special case of %s respectively %s.','extensions-leaflet-map'),
	'<code>markerClusterGroup</code>',
	'<code>leaflet-optiongroup</code>',
	'<code>leaflet-featuregroup</code>')
	.'<br>'.sprintf(__("If you don't use this shortcode yet, start your work with %sthese two%s.",'extensions-leaflet-map'),
	'<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=featuregroup">',
	'</a>').'</p>';

	$note='<h3>'.__('Note','extensions-leaflet-map').'</h3>'.
	'<p>'.sprintf(__('The shortcode %s is a special case of %s respectively %s.','extensions-leaflet-map'),
	'<code>markerClusterGroup</code>',
	'<code>leaflet-optiongroup</code>',
	'<code>leaflet-featuregroup</code>')
	.'<br>'.sprintf(__("If you don't use this shortcode yet, start %sthere%s.",'extensions-leaflet-map'),
	'<a href="/doku/featuregroup/">',
	'</a>').'</p>';

	$text='<h3>'.__('Options for grouping leaflet-markers','extensions-leaflet-map').'</h3>
	<ul>
	<li><code>feat</code> - '.__('possible meaningful values','extensions-leaflet-map').': <code>iconUrl</code>, <code>title</code></li>
		<li><code>strings</code> - '.sprintf(
		__('comma separated strings to distinguish the markers, e.g. an unique string in %s or %s',
		'extensions-leaflet-map'),"<code>iconUrl</code>","<code>title</code>").'</li>
			<li><code>groups</code> - '.
			__('comma separated labels appear in the selection menu','extensions-leaflet-map').'</li>
		<li>'.sprintf(
			__('The number of %s and %s must match.','extensions-leaflet-map'),"<code>strings</code>","<code>groups</code>").'
			</li>
			<li><code>visible</code> ('.__('optional','extensions-leaflet-map').') - '.
			sprintf(__('initial visibility of a group, default: %s. Either %s (valid for all groups) or a comma-separated list of %s and %s, where the number must match those of %s.',
			'extensions-leaflet-map'),
			'1',
			'0',
			'0',
			'1',
			'<code>groups</code>',
			).'</li>
			</ul>

			<h3>'.__('Shortcode for grouping leaflet-markers','extensions-leaflet-map').'</h3>
			<pre><code>&#091;leaflet-marker iconUrl="...red..." ... ] ... &#091;/leaflet-marker]
&#091;leaflet-marker iconUrl="...green..." ... ] ... &#091;/leaflet-marker]
//many markers
&#091;markerClusterGroup feat="iconUrl" strings="red,green" groups="rot,gruen" visible=...]</code></pre>'
			.__('or','extensions-leaflet-map').
			'<pre><code>&#091;leaflet-marker title="first ..." ... ] ... &#091;/leaflet-marker]
&#091;leaflet-marker title="second ..." ... ] ... &#091;/leaflet-marker]
//many markers
&#091;markerClusterGroup feat="title" strings="first,second" groups="First Group,Second Group" visible=...]</code></pre>
			<h3>'.__('Options for grouping markers (points) in leaflet-geojson','extensions-leaflet-map').'</h3>
			<ul>
			<li><code>feat</code> - '.__('possible meaningful values','extensions-leaflet-map').': <code>iconUrl</code>, <code>properties.<i>property</i></code></li>
			<li><code>properties.<i>property</i></code> - '.__('is the name of a property in properties of the Point in the FeatureCollection','extensions-leaflet-map').
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
			<li><code>strings</code> - '.sprintf(
				__('comma separated strings to distinguish the markers, e.g. an unique substring in %s or the exact string (e.g.%s) in %s',
				'extensions-leaflet-map'),"<code>iconUrl</code>",
				'<code><span style="color: #4f94d4">value0</span></code>',
				"<code>properties.<i>property</i></code>").'</li>
				<li><code>groups</code> - '.
				__('comma separated labels appear in the selection menu','extensions-leaflet-map').'</li>
				<li>'.sprintf(
					__('The number of %s and %s must match.','extensions-leaflet-map'),"<code>strings</code>","<code>groups</code>").'
					</li>
					<li><code>visible</code> ('.__('optional','extensions-leaflet-map').') - '.
					sprintf(__('initial visibility of a group, default: %s. Either %s (valid for all groups) or a comma-separated list of %s and %s, where the number must match those of %s.',
					'extensions-leaflet-map'),
					'1',
					'0',
					'0',
					'1',
					'<code>groups</code>',
					).'</li></ul>

					<h3>'.__('Shortcode for grouping markers (points) in leaflet-geojson','extensions-leaflet-map').'</h3>
		<pre><code>&#091;leaflet-geojson src="..." iconUrl="...red..." ... ] ... &#091;/leaflet-geojson]
&#091;leaflet-geojson src="..." iconUrl="...green..." ... ] ... &#091;/leaflet-geojson]
//any more leaflet-geojson
&#091;markerClusterGroup feat="iconUrl" strings="red,green" groups="rot,gruen" visible=...]</code></pre>'
					.__('or','extensions-leaflet-map').
	'<pre><code>&#091;leaflet-geojson src="..."  ... ] ... &#091;/leaflet-geojson]
//any more leaflet-geojson
&#091;markerClusterGroup feat="properties.<span style="color: #d63638">prop0</span>" strings="<span style="color: #4f94d4">value0</span>,..." groups="Description0,..." visible=...]</code></pre>
<h3>groups unknown '.__('and','extensions-leaflet-map').' others</h3><p>'.
sprintf(
__('If %s contains %s and %s, then markers (respectively Points) for which the property %s does not apply are placed in the %s group. Markers (respectively Points) whose property is not known are placed in the %s group. See also the developer console.','extensions-leaflet-map'),
			"<code>groups</code>",
			'"unknown"',
			'"others"',
			'(<code>iconUrl</code>, <code>title</code>, <code>properties.<i>property</i></code>)',
			"others",
			"unknown"
			).
			'</p><h3>Shortcode groups unknown '.__('and','extensions-leaflet-map').' others</h3>
<pre><code>&#091;markerClusterGroup feat="..." strings="...,...,others,unknown" groups="...,...,Other properties,Unknown properties"]</code></pre>';

	$textoptions = '<p>'.sprintf ( __('The parameter and settings for %s are valid too.','extensions-leaflet-map'),
			'<a href="?page='.LEAFEXT_PLUGIN_SETTINGS.'&tab=markercluster">Leaflet.markercluster</a>');
	$textoptions = $textoptions.'</p>';
	if (is_singular() || is_archive() ) {
		return $note.$text;
	} else {
		echo $firsttext.$text.$textoptions;
	}
}
leafext_clustergroup_help_text ();
