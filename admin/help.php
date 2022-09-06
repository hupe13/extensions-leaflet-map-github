<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_table($leafext_plugin_name="") {
	$header = '<h3>'.
	__('Found an issue?','extensions-leaflet-map').'</h3>
    <p>'.
	__('Post it to the support forum','extensions-leaflet-map').': <a href="https://wordpress.org/support/plugin/extensions-leaflet-map/" target="_blank">Extensions for Leaflet Map</a></p>';
	//
	$header=$header.'<h3>'.
	__('Documentation','extensions-leaflet-map').'</h3><p>';
	$header=$header.sprintf(
		__('Detailed documentation and examples in %sGerman%s and %sEnglish%s',
			"extensions-leaflet-map"),
			'<a href="https://leafext.de/">',
			'</a>',
			'<a href="https://leafext.de/en/">',
			'</a>');
	$header=$header.'.</p>';
	//
	$text='<figure class="wp-block-table aligncenter is-style-stripes">
	<table class="form-table" border="1" >
	<thead><tr class="alternate"><th style="text-align:center">'.
	__('Function','extensions-leaflet-map').
	'</th><th style="text-align:center">Shortcode(s) / '.__('Function','extensions-leaflet-map').'</th><th style="text-align:center">'.
	__('Leaflet Plugins and Elements','extensions-leaflet-map').
	'</th></tr></thead>
	<tbody>

	<tr><td>'.
	__('track with an elevation profile','extensions-leaflet-map').
	'</td><td><a href="?page='.$leafext_plugin_name.'&tab=elevation">elevation</a></td>
	<td><a href="https://github.com/Raruto/leaflet-elevation">leaflet-elevation</a>, <a href="https://github.com/yohanboniface/Leaflet.i18n">Leaflet.i18n</a></td></tr>

	<tr class="alternate"><td>'
	.__('multiple tracks with elevation profiles','extensions-leaflet-map').
	'</td><td><a href="?page='.$leafext_plugin_name.'&tab=multielevation">elevation-track, elevation-tracks, multielevation</a></td>
	<td><a href="https://github.com/Raruto/leaflet-elevation">leaflet-elevation</a></td></tr>

	<tr><td>'.__('Files for Leaflet Map','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=filemgr'.(current_user_can('manage_options')?'':'-list').'">'.__('Files for Leaflet Map','extensions-leaflet-map').'</a></td><td></td></tr>

	<tr class="alternate"><td>'.__('Tracks from all files in a directory','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=filemgr-dir">leaflet-directory</a></td><td></td></tr>

	<tr><td>'.__('switching tile servers','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=tileshelp">layerswitch</a></td><td><a href="https://leafletjs.com/examples/layers-control/">L.control.layers</a>, <a href="https://github.com/dayjournal/Leaflet.Control.Opacity">Leaflet.Control.Opacity</a>, <a href="https://github.com/leaflet-extras/leaflet-providers">Leaflet-providers</a></td></tr>

	<tr class="alternate"><td>'.__('marker clustering','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=markercluster">cluster</a></td><td><a href="https://github.com/Leaflet/Leaflet.markercluster">Leaflet.markercluster</a></td></tr>

	<tr><td>'.__('clustering and grouping of markers','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=clustergroup">markerClusterGroup</a></td><td><a href="https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup">Leaflet.FeatureGroup.SubGroup</a></td></tr>

	<tr  class="alternate"><td>'.__('styling markercluster','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=clusterplacementstrategies">placementstrategies</a></td><td><a href="https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies">Leaflet.MarkerCluster.PlacementStrategies</a></td></tr>

	<tr><td>'.__('more markers','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=extramarker">extramarker</a></td><td><a href="https://github.com/coryasilva/Leaflet.ExtraMarkers">Leaflet.ExtraMarkers</a></td></tr>

	<tr><td>'.__('reset the map','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=zoomhome">zoomhomemap</a></td><td><a href="https://github.com/torfsen/leaflet.zoomhome">leaflet.zoomhome</a></td></tr>

	<tr  class="alternate"><td>'.__('fullscreen','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=other">fullscreen</a></td><td><a href="https://github.com/brunob/leaflet.fullscreen">leaflet.fullscreen</a></td></tr>

	<tr><td>'.__('gesture handling','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=gesture" >gestures</a></td><td><a href="https://github.com/Raruto/leaflet-gesture-handling">Leaflet.GestureHandling</a></td></tr>

	<tr  class="alternate"><td>'.__('Hovering and Tooltips','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=hover" >hover</a></td><td></td></tr>

	<tr><td>'.__('hide markers','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=other">hidemarkers</a></td><td></td></tr>

	<tr  class="alternate"><td>'.__('Migration from','extensions-leaflet-map').' <a href="https://wordpress.org/plugins/wp-gpx-maps/">WP GPX Maps</a></td><td><a href="?page='.$leafext_plugin_name.'&tab=sgpxelevation">sgpx</a></td><td></td></tr>

	</tbody></table></figure>';

	$ende = '<p>'.__('You may be interested in','extensions-leaflet-map').
		' <a href="https://github.com/hupe13/leafext-dsgvo">DSGVO/GDPR Snippet for Extensions for Leaflet Map</a>.</p>';

	// $ende = $ende.'<p>'.sprintf(__('If you want to help me, you can test to %smanage the files%s.','extensions-leaflet-map'),
	// 	'<a href="https://github.com/hupe13/extensions-leaflet-map-testing/">',
	// 	'</a>').'</p>';

	if (is_singular() || is_archive() ) {
		return $text;
	} else {
		echo $header.$text.$ende;
	}

}
