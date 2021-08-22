<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();
	$text = '<h3>'.
	__('Found an issue?','extensions-leaflet-map').'</h3>
    <p>'.
	__('Post it to the support forum','extensions-leaflet-map').': <a href="https://wordpress.org/support/plugin/extensions-leaflet-map/" target="_blank">Extensions for Leaflet Map</a></p>';
	//
	$text=$text.'<h3>'.
	__('Documentation','extensions-leaflet-map').'</h3><p>';
	$text=$text.sprintf(
		__('Detailed documentation and examples in %sGerman%s and %sEnglish%s',
			"extensions-leaflet-map"),
			'<a href="https://leafext.de/">',
			'</a>',
			'<a href="https://leafext.de/en/">',
			'</a>');
	$text=$text.'.</p>';
	//
	$text=$text.'<figure>
	<table class="form-table" border="1" >
	<thead><tr><th style="text-align:center">Function</th><th style="text-align:center">Shortcode(s)</th><th style="text-align:center">Leaflet Plugins and Elements</th></tr></thead>
	<tbody>

	<tr><td>'.__('track with an elevation profile','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=elevation">elevation</a></td><td><a href="https://github.com/Raruto/leaflet-elevation">leaflet-elevation</a></td></tr>

	<tr><td>'.__('multiple tracks with elevation profiles','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=multielevation">elevation-track, elevation-tracks</a></td><td><a href="https://github.com/mpetazzoni/leaflet-gpx">leaflet-gpx</a>, <a href="https://github.com/Raruto/leaflet-elevation/blob/master/libs/leaflet-gpxgroup.js">leaflet-gpxgroup</a></td></tr>

	<tr><td>'.__('switching tile servers','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=tilelayers">layerswitch</a></td><td><a href="https://leafletjs.com/examples/layers-control/">L.control.layers</a></td></tr>

	<tr><td>'.__('marker clustering','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=markercluster">cluster</a></td><td><a href="https://github.com/Leaflet/Leaflet.markercluster">Leaflet.markercluster</a></td></tr>

	<tr><td>'.__('clustering and grouping of markers','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=clustergroup">markerClusterGroup</a></td><td><a href="https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup">Leaflet.FeatureGroup.SubGroup</a></td></tr>

	<tr><td>'.__('styling markercluster','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=clusterplacementstrategies">placementstrategies</a></td><td><a href="https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies">Leaflet.MarkerCluster.PlacementStrategies</a></td></tr>

	<tr><td>'.__('reset the map','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=zoomhome">zoomhomemap</a></td><td><a href="https://github.com/torfsen/leaflet.zoomhome">leaflet.zoomhome</a></td></tr>

	<tr><td>'.__('fullscreen','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=other">fullscreen</a></td><td><a href="https://github.com/brunob/leaflet.fullscreen">leaflet.fullscreen</a></td></tr>

	<tr><td>'.__('gesture handling','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=gesture" >gestures</a></td><td><a href="https://github.com/Raruto/leaflet-gesture-handling">Leaflet.GestureHandling</a></td></tr>

	<tr><td>'.__('Hovering and Tooltips','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=hover" >hover</a></td><td></td></tr>

	<tr><td>'.__('hide markers','extensions-leaflet-map').'</td><td><a href="?page='.$leafext_plugin_name.'&tab=other">hidemarkers</a></td><td></td></tr>

	</tbody></table></figure>';
	echo $text;
