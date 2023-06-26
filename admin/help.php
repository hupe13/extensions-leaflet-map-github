<?php
// Direktzugriff auf diese Datei verhindern:
defined( 'ABSPATH' ) or die();

function leafext_help_table($leafext_plugin_name="") {
	$header = '<h3>'.
	__('Found an issue? Do you have a question?','extensions-leaflet-map').'</h3>
	<p>'.
	__('Post it to the support forum','extensions-leaflet-map').
	': <a href="https://wordpress.org/support/plugin/extensions-leaflet-map/" target="_blank">Extensions for Leaflet Map</a></p>';
	//
	$header=$header.'<h3>'.
	__('Documentation','extensions-leaflet-map').'</h3><p>';
	$header=$header.
	sprintf(
		__('Detailed documentation and examples in %sGerman%s and %sEnglish%s',
		"extensions-leaflet-map"),
		'<a href="https://leafext.de/">',
		'</a>',
		'<a href="https://leafext.de/en/">',
		'</a>'
	);
	$header=$header.'.</p>';
	//

	$text='<style>tr:nth-child(even) { background-color: #fcfcfc; }</style>';
	if (is_singular() || is_archive()){
		$text=$text.'<style>td,th { border:1px solid #195b7a !important; }</style>';
	}

	$text=$text.'<figure class="wp-block-table aligncenter is-style-stripes">
	<table class="form-table" border="1">
	<thead><tr><th style="text-align:center">'.
	__('Function','extensions-leaflet-map').
	'</th><th style="text-align:center">Shortcode(s) / '.__('Function','extensions-leaflet-map').'</th><th style="text-align:center">'.
	__('Leaflet Plugins','extensions-leaflet-map').
	'</th></tr></thead>
	<tbody>';

	$table = array();

	$table[__('Elevation Profile','extensions-leaflet-map')] = array(
		array(
			'function' => __('track with an elevation profile','extensions-leaflet-map'),
			'shortcode' => 'elevation',
			'tab' => 'elevation',
			'plugins' => '<a href="https://github.com/Raruto/leaflet-elevation">leaflet-elevation</a>,
			<a href="https://github.com/yohanboniface/Leaflet.i18n">Leaflet.i18n</a>',
			'doku' => '/doku/elevation/',
			'kategorie' => 'elevation',
			'examples' => '',
		),
		array(
			'function' => __('multiple tracks with elevation profiles','extensions-leaflet-map'),
			'shortcode' => 'elevation-track, elevation-tracks, multielevation',
			'tab' => 'multielevation',
			'plugins' => '<a href="https://github.com/Raruto/leaflet-elevation">leaflet-elevation</a>,
			<a href="https://github.com/makinacorpus/Leaflet.GeometryUtil">Leaflet.GeometryUtil</a>',
			'doku' => '/doku/multielevation/',
			'kategorie' => 'multielevation',
			'examples' => '',
		),
		array(
			'function' => __('Migration from','extensions-leaflet-map').' <a href="https://wordpress.org/plugins/wp-gpx-maps/">WP GPX Maps</a>',
			'shortcode' => 'sgpx',
			'tab' => 'sgpxelevation',
			'plugins' => '',
			'doku' => '/doku/sgpxelevation/',
			'kategorie' => '',
			'examples' => '/extra/category/sgpx/',
		),
	);

	$table[__('Functions for Markers','extensions-leaflet-map')] = array(
		array(
			'function' => __('marker clustering','extensions-leaflet-map'),
			'shortcode' => 'cluster',
			'tab' => 'markercluster',
			'plugins' => '<a href="https://github.com/Leaflet/Leaflet.markercluster">Leaflet.markercluster</a>',
			'doku' => '/doku/markercluster/',
			'kategorie' => 'cluster',
			'examples' => '',
		),
		array(
			'function' => __('clustering and grouping of markers','extensions-leaflet-map'),
			'shortcode' => 'markerClusterGroup',
			'tab' => 'markerclustergroup',
			'plugins' => '<a href="https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup">Leaflet.FeatureGroup.SubGroup</a>',
			'doku' => '/doku/markerclustergroup/',
			'kategorie' => '',
			'examples' => '',
		),
		array(
			'function' => __('styling markercluster','extensions-leaflet-map'),
			'shortcode' => 'placementstrategies',
			'tab' => 'clusterplacementstrategies',
			'plugins' => '<a href="https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies">Leaflet.MarkerCluster.PlacementStrategies</a>',
			'doku' => '',
			'kategorie' => '',
			'examples' => '/cluster/placementstrategies/',
		),
		array(
			'function' => 'ExtraMarkers',
			'shortcode' => 'leaflet-extramarker',
			'tab' => 'extramarker',
			'plugins' => '<a href="https://github.com/coryasilva/Leaflet.ExtraMarkers">Leaflet.ExtraMarkers</a>',
			'doku' => '/doku/extramarker/',
			'kategorie' => '',
			'examples' => '',
		),
		array(
			'function' => __('geojson markers','extensions-leaflet-map'),
			'shortcode' => 'geojsonmarker',
			'tab' => 'geojsonmarker',
			'plugins' => '',
			'doku' => '/doku/geojsonmarker/',
			'kategorie' => '',
			'examples' => '/marker/geojsonmarker/',
		),
		array(
			'function' => __('hide markers','extensions-leaflet-map'),
			'shortcode' => 'hidemarkers',
			'tab' => 'hidemarkers',
			'plugins' => '',
			'doku' => '/doku/hidemarkers/',
			'kategorie' => '',
			'examples' => '',
		),
	);

	$table[__('Further Leaflet Plugins','extensions-leaflet-map')] = array(
		array(
			'function' => __('Grouping by options and features','extensions-leaflet-map'),
			'shortcode' => 'leaflet-optiongroup, leaflet-featuregroup',
			'tab' => 'featuregroup',
			'plugins' => '<a href="https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup">Leaflet.FeatureGroup.SubGroup</a>',
			'doku' => '/doku/featuregroup/',
			'kategorie' => 'grouping',
			'examples' => '',
		),
		array(
			'function' => __('Search markers/features','extensions-leaflet-map'),
			'shortcode' => 'leaflet-search',
			'tab' => 'leafletsearch',
			'plugins' => '<a href="https://github.com/stefanocudini/leaflet-search">Leaflet Control Search</a>',
			'doku' => '/doku/leafletsearch/',
			'kategorie' => 'leafletsearch',
			'examples' => '',
		),
		array(
			'function' => __('Leaflet Choropleth','extensions-leaflet-map'),
			'shortcode' => 'choropleth',
			'tab' => 'choropleth',
			'plugins' => '<a href="https://github.com/timwis/leaflet-choropleth">Leaflet Choropleth</a>',
			'doku' => '/doku/choropleth/',
			'kategorie' => '',
			'examples' => '',
		),
		array(
			'function' => __('switching tile servers','extensions-leaflet-map'),
			'shortcode' => 'layerswitch',
			'tab' => 'tileshelp',
			'plugins' => '<a href="https://leafletjs.com/examples/layers-control/">L.control.layers</a>,
			<a href="https://github.com/leaflet-extras/leaflet-providers">Leaflet-providers</a>,
			<a href="https://github.com/dayjournal/Leaflet.Control.Opacity">Leaflet.Control.Opacity</a>',
			'doku' => '/doku/tileshelp/',
			'kategorie' => '',
			'examples' => '',
		),
		array(
			'function' => __('reset the map','extensions-leaflet-map'),
			'shortcode' => 'zoomhomemap',
			'tab' => 'zoomhome',
			'plugins' => '<a href="https://github.com/torfsen/leaflet.zoomhome">leaflet.zoomhome</a>',
			'doku' => '/doku/zoomhome/',
			'kategorie' => '',
			'examples' => '/zoomhome/zoomhome/',
		),
		array(
			'function' => __('fullscreen','extensions-leaflet-map'),
			'shortcode' => 'fullscreen',
			'tab' => 'fullscreen',
			'plugins' => '<a href="https://github.com/brunob/leaflet.fullscreen">leaflet.fullscreen</a>',
			'doku' => '/doku/fullscreen/',
			'kategorie' => '',
			'examples' => '',
		),
		array(
			'function' => __('gesture handling','extensions-leaflet-map'),
			'shortcode' => 'gestures',
			'tab' => 'gesture',
			'plugins' => '<a href="https://github.com/Raruto/leaflet-gesture-handling">Leaflet.GestureHandling</a>',
			'doku' => '/doku/gesture/',
			'kategorie' => '',
			'examples' => '',
		),
	);

	$table[__('Files for Leaflet Map','extensions-leaflet-map')] = array(
		array(
			'function' => __('Files for Leaflet Map','extensions-leaflet-map'),
			'shortcode' => __('Files for Leaflet Map','extensions-leaflet-map'),
			'tab' => 'filemgr'.(current_user_can('manage_options')?'':'-list'),
			'plugins' => '',
			'doku' => '/doku/filemgr/',
			'kategorie' => '',
			'examples' => '',
		),
		array(
			'function' => __('Tracks from all files in a directory','extensions-leaflet-map'),
			'shortcode' => 'leaflet-directory',
			'tab' => 'filemgr-dir',
			'plugins' => '',
			'doku' => '/doku/filemgr/',
			'kategorie' => '',
			'examples' => '',
		),
	);

	$table[__('More Functions','extensions-leaflet-map')] = array(
		array(
			'function' => __('Hovering and Tooltips','extensions-leaflet-map'),
			'shortcode' => 'hover',
			'tab' => 'hover',
			'plugins' => '',
			'doku' => '/doku/hover/',
			'kategorie' => 'hover',
			'examples' => '',
		),
		array(
			'function' => __('Hovering overlapping elements','extensions-leaflet-map'),
			'shortcode' => 'hoverlap',
			'tab' => 'hoverlap',
			'plugins' => '<a href="https://github.com/Turfjs/turf">Turf</a>, <a href="https://github.com/makinacorpus/Leaflet.GeometryUtil">Leaflet.GeometryUtil</a>',
			'doku' => '/doku/hoverlap/',
			'kategorie' => 'hoverlap',
			'examples' => '',
		),
	);

	foreach ($table as $key => $entries) {
		$text=$text.'<tr><td colspan="3" style="text-align:center"><b>'.$key.'</b></td></tr>';
		foreach ($entries as $entry) {
			$text=$text.'<tr><td>'.$entry['function'].'</td>';
			if (is_singular() || is_archive() ) {
				$text=$text.'<td>'.$entry['shortcode'];
				if ($entry['doku'] != "") $text=$text.' - <a href="'.$entry['doku'].'">'.__('Documentation','extensions-leaflet-map').'</a>';
				if ($entry['kategorie'] != "") $text=$text.' - <a href="/examples/'.$entry['kategorie'].'/">'.__('Examples','extensions-leaflet-map').'</a>';
				if ($entry['examples'] != "") $text=$text.' - <a href="'.$entry['examples'].'">'.__('Examples','extensions-leaflet-map').'</a>';
				$text=$text.'</td>';
			} else {
				$text=$text.'<td><a href="?page='.$leafext_plugin_name.'&tab='.$entry['tab'].'">'.$entry['shortcode'].'</a></td>';
			}
			$text=$text.'<td>'.$entry['plugins'].'</td></tr>';
		}
	}

	$text=$text.'</tbody></table></figure>';

	$ende = '<p>'.__('You may be interested in','extensions-leaflet-map').
	' <a href="https://github.com/hupe13/leafext-dsgvo">DSGVO/GDPR Snippet for Extensions for Leaflet Map</a>.</p>';

	if (is_singular() || is_archive() ) {
		return $text;
	} else {
		echo $header.$text.$ende;
	}

}
