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

	$text=$text.'<p><figure class="wp-block-table aligncenter is-style-stripes">
	<table class="form-table" border="1">';

	$text = $text.'<thead><tr><th style="text-align:center">'.__('Function','extensions-leaflet-map').'</th>'.
	'<th style="text-align:center">Shortcode(s) / '.__('Function','extensions-leaflet-map').'</th>';
	// '<th style="text-align:center">'.'Leaflet Plugins'.'</th>'.
	// '<th style="text-align:center">'.__('Documentation','extensions-leaflet-map').'</th>'.
	if (is_singular() || is_archive()){
		// $text = $text.'<th style="text-align:center">'.__('Examples','extensions-leaflet-map').'</th>';
		$text = $text.'<th style="text-align:center">'.'&nbsp;'.'</th>';
	}
	$text = $text.'</tr></thead><tbody>';

	$table = array();

// choropleth
// cluster
// elevation
// elevation-track
// elevation-tracks
// extramarker - doppelt (historisch)
// fullscreen
// geojsonmarker
// gestures
// hidemarkers
// hover
// hoverlap
// layerswitch
// leaflet-directory
// leaflet-extramarker
// leaflet-featuregroup
// leaflet-optiongroup
// leaflet-search
// markerClusterGroup
// multielevation
// overviewmap
// placementstrategies
// sgpx
// zoomhomemap

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
			'function' => __('Marker Clustering','extensions-leaflet-map'),
			'shortcode' => 'cluster',
			'tab' => 'markercluster',
			'plugins' => '<a href="https://github.com/Leaflet/Leaflet.markercluster">Leaflet.markercluster</a>',
			'doku' => '/doku/markercluster/',
			'kategorie' => 'cluster',
			'examples' => '',
		),
		array(
			'function' => __('Clustering and Grouping of Markers','extensions-leaflet-map'),
			'shortcode' => 'markerClusterGroup',
			'tab' => 'markerclustergroup',
			'plugins' => '<a href="https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup">Leaflet.FeatureGroup.SubGroup</a>',
			'doku' => '/doku/markerclustergroup/',
			'kategorie' => '',
			'examples' => '',
		),
		array(
			'function' => __('Styling Markercluster','extensions-leaflet-map'),
			'shortcode' => 'placementstrategies',
			'tab' => 'clusterplacementstrategies',
			'plugins' => '<a href="https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies">Leaflet.MarkerCluster.PlacementStrategies</a>',
			'doku' => '',
			'kategorie' => '',
			'examples' => '/cluster/placementstrategies/',
		),
		array(
			'function' => 'Font Awesome Icons',
			'shortcode' => 'leaflet-extramarker',
			'tab' => 'extramarker',
			'plugins' => '<a href="https://github.com/coryasilva/Leaflet.ExtraMarkers">Leaflet.ExtraMarkers</a>',
			'doku' => '/doku/extramarker/',
			'kategorie' => '',
			'examples' => '',
		),
		array(
			'function' => __('Design and Group markers from geojson files','extensions-leaflet-map'),
			'shortcode' => 'geojsonmarker',
			'tab' => 'geojsonmarker',
			'plugins' => '',
			'doku' => '/doku/geojsonmarker/',
			'kategorie' => '',
			'examples' => '/marker/geojsonmarker/',
		),
		array(
			'function' => __('Overview Map','extensions-leaflet-map'),
			'shortcode' => 'overviewmap',
			'tab' => 'overviewmap',
			'plugins' => '',
			'doku' => '/doku/overviewmap/',
			'kategorie' => '',
			'examples' => '/extra/category/overviewmap/',
		),
		array(
			'function' => __('Hide Markers','extensions-leaflet-map'),
			'shortcode' => 'hidemarkers',
			'tab' => 'hidemarkers',
			'plugins' => '',
			'doku' => '/doku/hidemarkers/',
			'kategorie' => '',
			'examples' => '',
		),
	);

	// $table[__('Further Leaflet Plugins','extensions-leaflet-map')] = array(
	$table[__('More Functions','extensions-leaflet-map')] = array(
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
			'function' => __('Switching Tile Servers','extensions-leaflet-map'),
			'shortcode' => 'layerswitch',
			'tab' => 'tiles',
			'plugins' => '<a href="https://leafletjs.com/examples/layers-control/">L.control.layers</a>,
			<a href="https://github.com/leaflet-extras/leaflet-providers">Leaflet-providers</a>,
			<a href="https://github.com/dayjournal/Leaflet.Control.Opacity">Leaflet.Control.Opacity</a>',
			'doku' => '/doku/tileshelp/',
			'kategorie' => '',
			'examples' => '',
		),
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
		array(
			'function' => __('Reset the map','extensions-leaflet-map'),
			'shortcode' => 'zoomhomemap',
			'tab' => 'zoomhome',
			'plugins' => '<a href="https://github.com/torfsen/leaflet.zoomhome">leaflet.zoomhome</a>',
			'doku' => '/doku/zoomhome/',
			'kategorie' => '',
			'examples' => '/zoomhome/zoomhome/',
		),
		array(
			'function' => __('Fullscreen','extensions-leaflet-map'),
			'shortcode' => 'fullscreen',
			'tab' => 'fullscreen',
			'plugins' => '<a href="https://github.com/brunob/leaflet.fullscreen">leaflet.fullscreen</a>',
			'doku' => '/doku/fullscreen/',
			'kategorie' => '',
			'examples' => '',
		),
		array(
			'function' => __('Gesture Handling','extensions-leaflet-map'),
			'shortcode' => 'gestures',
			'tab' => 'gesture',
			'plugins' => '<a href="https://github.com/Raruto/leaflet-gesture-handling">Leaflet.GestureHandling</a>',
			'doku' => '/doku/gesture/',
			'kategorie' => '',
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

	foreach ($table as $key => $entries) {
		$text=$text.'<tr><td colspan="3" style="text-align:center"><b>'.$key.'</b></td></tr>';
		foreach ($entries as $entry) {
			$text=$text.'<tr><td>'.$entry['function'].'</td>';
			if (is_singular() || is_archive() ) {
				// $text=$text.'<td>'.$entry['shortcode'];
				// if ($entry['doku'] != "") $text=$text.' - <a href="'.$entry['doku'].'">'.__('Documentation','extensions-leaflet-map').'</a>';
				// if ($entry['kategorie'] != "") $text=$text.' - <a href="/examples/'.$entry['kategorie'].'/">'.__('Examples','extensions-leaflet-map').'</a>';
				// if ($entry['examples'] != "") $text=$text.' - <a href="'.$entry['examples'].'">'.__('Examples','extensions-leaflet-map').'</a>';
				// $text=$text.'</td>';
				//
				$text=$text.'<td><a href="'.$entry['doku'].'">'.$entry['shortcode'].'</a></td>';
				if ($entry['kategorie'] != "") {
					$text=$text.'<td><a href="/examples/'.$entry['kategorie'].'/">'.__('Examples','extensions-leaflet-map').'</a></td>';
				} else if ($entry['examples'] != "") {
					$text=$text.'<td><a href="'.$entry['examples'].'">'.__('Examples','extensions-leaflet-map').'</a></td>';
				} else {
					$text=$text.'<td>&nbsp;</td>';
				}

			} else {
				$text=$text.'<td><a href="?page='.$leafext_plugin_name.'&tab='.$entry['tab'].'">'.$entry['shortcode'].'</a></td>';
			}
			// $text=$text.'<td>'.$entry['plugins'].'</td>';
			$text=$text.'</tr>';
		}
	}

	$text=$text.'</tbody></table></figure></p>';

	$text = $text.
	'<h4 id="leaflet-plugins">'.__('Included and used Leaflet Plugins','extensions-leaflet-map').'</h4>
<ul>
<li><a href="https://github.com/Raruto/leaflet-elevation">leaflet-elevation</a>: '.__('A Leaflet plugin that allows to add elevation profiles using d3js.','extensions-leaflet-map').'</li>
<li><a href="https://github.com/makinacorpus/Leaflet.GeometryUtil">Leaflet.GeometryUtil</a></li>
<li><a href="https://github.com/yohanboniface/Leaflet.i18n">Leaflet.i18n</a>: '.__('Internationalisation module for Leaflet plugins.','extensions-leaflet-map').'</li>
<li><a href="https://github.com/Raruto/leaflet-rotate">leaflet-rotate</a>: '.__('A Leaflet plugin that allows to add rotation functionality to map tiles','extensions-leaflet-map').'</li>
<li><a href="https://github.com/makinacorpus/Leaflet.AlmostOver">Leaflet.AlmostOver</a>: '.__('This plugin allows to detect mouse click and overing events on lines, with a tolerance distance.','extensions-leaflet-map').'</li>
<li><a href="https://www.npmjs.com/package/@tmcw/togeojson">@tmcw/togeojson</a>: '.__('Convert KML, GPX, and TCX to GeoJSON.','extensions-leaflet-map').'</li>
<li><a href="https://github.com/d3/d3">d3js</a>: Data-Driven Documents</li>
<li><a href="https://github.com/leaflet-extras/leaflet-providers">Leaflet-providers</a>: '.__('An extension that contains configurations for various tile providers.','extensions-leaflet-map').'</li>
<li><a href="https://github.com/dayjournal/Leaflet.Control.Opacity">Leaflet.Control.Opacity</a>: '.__('Makes multiple tile layers transparent.','extensions-leaflet-map').'</li>
<li><a href="https://github.com/Leaflet/Leaflet.markercluster">Leaflet.markercluster</a>: '.__('Provides Beautiful Animated Marker Clustering functionality.','extensions-leaflet-map').'</li>
<li><a href="https://github.com/adammertel/Leaflet.MarkerCluster.PlacementStrategies">Leaflet.MarkerCluster.PlacementStrategies</a>: '.__('Styling Markerclusters.','extensions-leaflet-map').'</li>
<li><a href="https://github.com/coryasilva/Leaflet.ExtraMarkers">Leaflet.ExtraMarkers</a>: '.__('Shameless copy of Awesome-Markers with more shapes and colors.','extensions-leaflet-map').'</li>
<li><a href="https://github.com/ghybs/Leaflet.FeatureGroup.SubGroup">Leaflet.FeatureGroup.SubGroup</a>: '.__('Grouping of Leaflet elements by options and features.','extensions-leaflet-map').'</li>
<li><a href="https://github.com/stefanocudini/leaflet-search">Leaflet Control Search</a>: '.__('Search Markers/Features location by option or custom property.','extensions-leaflet-map').'</li>
<li><a href="https://github.com/timwis/leaflet-choropleth">leaflet-choropleth</a>: '.__('Choropleth plugin for Leaflet (color scale based on value).','extensions-leaflet-map').'</li>
<li><a href="https://github.com/torfsen/leaflet.zoomhome">leaflet.zoomhome</a>: '.sprintf(__('Provides a zoom control with a %sHome%s button to reset the view.','extensions-leaflet-map'),'&quot;','&quot;').'</li>
<li><a href="https://github.com/brunob/leaflet.fullscreen">leaflet.fullscreen</a>: '.__('Simple plugin for Leaflet that adds fullscreen button to your maps.','extensions-leaflet-map').'</li>
<li><a href="https://github.com/Raruto/leaflet-gesture-handling">Leaflet.GestureHandling</a>: '.__('A Leaflet plugin that allows to prevent default map scroll/touch behaviours.','extensions-leaflet-map').'</li>
<li><a href="https://github.com/Turfjs/turf">turf</a>: '.__('Advanced geospatial analysis for browsers and Node.js','extensions-leaflet-map').'</li>
</ul>';

	$ende = '<p>'.__('You may be interested in','extensions-leaflet-map').
	' <a href="https://github.com/hupe13/leafext-dsgvo">DSGVO/GDPR Snippet for Extensions for Leaflet Map</a>.</p>';

	if (is_singular() || is_archive() ) {
		return $text;
	} else {
		echo $header.$text.$ende;
	}

}
