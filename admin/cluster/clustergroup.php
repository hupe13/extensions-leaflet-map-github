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
	$text='<h3>Options and Shortcode</h3>
	<ul style="list-style: disc;">
	<li style="margin-left: 1.5em;"><code>feat</code> - '.__('possible meaningful values','extensions-leaflet-map').': iconUrl, title</li>
	<li style="margin-left: 1.5em;"><code>strings</code> - '.sprintf(
		__('comma separated strings to distinguish the markers, e.g. an unique string in %s or %s',
		'extensions-leaflet-map'),"<code>iconUrl</code>","<code>title</code>").'</li>
		<li style="margin-left: 1.5em;"><code>groups</code> - '.
		__('comma separated labels appear in the selection menu','extensions-leaflet-map').'</li>
		<li style="margin-left: 1.5em;">'.sprintf(
			__('The number of %s and %s must match.','extensions-leaflet-map'),"<code>strings</code>","<code>groups</code>").'
			</li></ul>
<pre><code>[leaflet-marker iconUrl="...red..." ... ] ... [/leaflet-marker]
[leaflet-marker iconUrl="...green..." ... ] ... [/leaflet-marker]
//many markers
[markerClusterGroup feat="iconUrl" strings="red,green" groups="rot,gruen"]
</code></pre>'
.__('or','extensions-leaflet-map').
'<pre><code>[leaflet-marker title="first ..." ... ] ... [/leaflet-marker]
[leaflet-marker title="second ..." ... ] ... [/leaflet-marker]
//many markers
[markerClusterGroup feat="title" strings="first,second" groups="First Group,Second Group"]
</code></pre>';
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
